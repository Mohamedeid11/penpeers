<?php
namespace App\Services;

use App\Mail\informLeadAuthorForAcceptParticipation;
use App\Mail\informLeadAuthorInvitationRejected;
use App\Models\BookParticipant;
use App\Models\EmailInvitation;
use App\Notifications\RealTimeNotification;
use App\Repositories\Interfaces\BookParticipantRepositoryInterface;
use App\Repositories\Interfaces\EmailInvitationRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthorsService
{
    protected $emailInvitationRepository;
    protected $bookParticipantRepository;
    public function __construct(
                                EmailInvitationRepositoryInterface $emailInvitationRepository,
                                BookParticipantRepositoryInterface $bookParticipantRepository
    )
    {
        $this->emailInvitationRepository = $emailInvitationRepository;
        $this->bookParticipantRepository = $bookParticipantRepository;
    }
    public function getEmailInvitations(){
        return $this->emailInvitationRepository->getManyBy('invited_by', Auth::id());
    }
    public function getParticipants(){
        $books_ids = Auth::user()->lead_books->pluck('id')->toArray();
        return BookParticipant::whereIn('book_id', $books_ids)->where('user_id', '!=', Auth::id())->get();
    }
    public function getReceivedInvitations(){
        return BookParticipant::currentUser()->notALeadAuthor()->where('status' ,'!=' ,2)->orderBy('id', 'desc')->get();
    }
    public function acceptParticipation($data){
        $participantValidator = Validator::make(['id'=>$data->participant_id], [
            'id' => 'required|exists:book_participants,id'
        ]);

        if ($participantValidator->fails()) {
            return back()->WithErrors($participantValidator)->withInput();
        }
        $participant = BookParticipant::find($data->participant_id);
        $participant->update([
            'status' => 1,
            'answered_at' => now(),
        ]);
        //Send Email inform lead author participant accepted
        $lead_author = $participant->book->lead_author;

        $url = route('web.dashboard.author_participants');
        $url_type = "accept_registered_author";
        $book_url = '<a class="text-underline text-secondary font-weight-bold" href="'. $url .'">'. $participant->book->title  .'</a>';

        $lead_author->notify(new RealTimeNotification(  '<strong style="color: #ce7852;">'. $participant->user->name.'</strong>' . ' has accepted  participating in your book ' . $book_url  , $url ,$url_type));

        Mail::to($lead_author->email)->send(new  informLeadAuthorForAcceptParticipation($participant));
        return $participant->book_id;
    }
    public function deleteParticipation($data){
        $participantValidator = Validator::make(['id'=>$data->participant_id], [
            'id' => 'required|exists:book_participants,id'
        ]);
        if ($participantValidator->fails()) {
            return back()->WithErrors($participantValidator)->withInput();
        }
        $participant = BookParticipant::find($data->participant_id);
        $participant->user->unreadNotifications()->where('data','LIKE','%"url_type":"received_invitations"%')->delete();
        $participant->delete();
    }

    public function rejectParticipation($data){

        $type = $data['type'];
        $id = base64_decode($data['id']);
        $user_data = [];
        if($type == 'byEmail'){
            $record = EmailInvitation::findOrFail($id)->get()->last();
            $book = $record->book;

            $user_data['name']  = $record->name;
            $user_data['email'] = $record->email;
            $user_data['type'] =  "non-registred";

        } else {
            $record = BookParticipant::findOrFail($id);
            $book = $record->book;
            $user_data['type'] =  "registred";
            $user_data['data'] =  $record->user;
        }

        //send email to lead author
        $url = route('web.dashboard.author_participants');
        $url_type = $type == 'byEmail' ? 'reject_unregistered_author' : 'reject_registered_author';
        $book_url = '<a class="text-underline text-secondary font-weight-bold"
                                    href="'. $url .'">'. $record->book->title .'</a>';

        $book->lead_author->notify(new RealTimeNotification( '<strong style="color: #ce7852;">'. ($record->user->name ?? $user_data['name'] )  .'</strong>'. ' has rejected participating in your book ' .  $book_url  , $url , $url_type));

        Mail::to($book->lead_author->email)->send(new informLeadAuthorInvitationRejected($book, $user_data));

        // Delete Record
//            $record->delete();
        $record->update([
            'status' => 2,
            'answered_at' => now(),
        ]);
    }
}
