<?php

namespace App\Services;

use App\Mail\InvitationEmail;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookAuthorsService
{
    protected $userRepository;
    protected $genresRepository;
    protected $specialChaptersRepository;
    protected $bookRepository;
    protected $bookRoleRepository;
    public function __construct(
        UserRepositoryInterface $userRepository,
        GenreRepositoryInterface $genresRepository,
        SpecialChapterRepositoryInterface $specialChaptersRepository,
        BookRepositoryInterface $bookRepository,
        BookRoleRepositoryInterface $bookRoleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->genresRepository = $genresRepository;
        $this->specialChaptersRepository = $specialChaptersRepository;
        $this->bookRepository = $bookRepository;
        $this->bookRoleRepository = $bookRoleRepository;
    }
    public function getBook($book_id)
    {
        return $this->userRepository->getBook(auth()->user(), $book_id);
    }
    public function getBookRoles()
    {
        return $this->bookRoleRepository->all();
    }
    public function inviteAuthorToBook($user, $book, $book_role)
    {
        $book->participants()->attach(
            $user,
            ['book_role_id' => $book_role->id]
        );
    }
    public function inviteAuthorToBookByEmail($name, $email, $book, $book_role)
    {
        $book->email_invitations()->create([
            'invited_by' => auth()->user()->id,
            'email' => $email,
            'name' => $name,
            'invited_at' => now(),
            'book_role_id' => $book_role->id,
        ]);

        $book->requests()->create([
            'book_id' => $book->id,
            'email' => $email,
            'name' => $name,
        ]);

    }
    public function inviteAuthor($request, $book_id)
    {
        $book = $this->bookRepository->get($book_id);
        $bookRoleValidator = Validator::make(['book_role_id' => $request->input('book_role_id')], [
            'book_role_id' => 'required|exists:book_roles,id'
        ]);

        if ($bookRoleValidator->fails()) {
            return back()->withErrors($bookRoleValidator)->withInput();
        } else {
            $book_role = $this->bookRoleRepository->get($request->input('book_role_id'));
        }

        $userNameOrEmail = Str::of($request->input('user'))->replaceMatches('/^@/', '')->__toString();
        $emailValidator = Validator::make(['email' => $userNameOrEmail], [
            'email' => 'email:rfc,dns'
        ]);
        if ($emailValidator->fails()) {
            $usernameValidator = Validator::make(['username' => $userNameOrEmail], [
                'username' => 'exists:users,username'
            ]);
            if ($usernameValidator->fails()) {
                return back()->WithErrors($usernameValidator)->withInput();
            } else {
                $user = $this->userRepository->getBy('username', $userNameOrEmail);
                $this->inviteAuthorToBook($user, $book, $book_role);
                Mail::to($userNameOrEmail)->send(new InvitationEmail(Auth::user()->name, $book, 'byUserName', $request->email));
            }
        } else {
            $emailExistsValidator = Validator::make(['email' => $userNameOrEmail], [
                'email' => 'exists:users,email|email:rfc,dns'
            ]);
            if ($emailExistsValidator->fails()) {
                $this->inviteAuthorToBookByEmail($userNameOrEmail, $request->email, $book, $book_role);
                Mail::to($userNameOrEmail)->send(new InvitationEmail(Auth::user()->name, $book, 'byEmail', $request->email));
            } else {
                $user = $this->userRepository->getBy('email', $userNameOrEmail);
                $this->inviteAuthorToBook($user, $book, $book_role);
                Mail::to($userNameOrEmail)->send(new InvitationEmail(Auth::user()->name, $book, 'byUserName', $request->email));
            }
        }

        return back()->with('success', __('global.invitation.sent_successfully') );
    }
}
