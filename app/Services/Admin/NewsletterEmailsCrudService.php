<?php
namespace App\Services\Admin;

use App\Jobs\SendNewsletterEmails;
use App\Models\NewsletterEmail;
use App\Repositories\Eloquent\NewsletterEmailRepository;
use App\Repositories\Interfaces\NewsletterEmailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class NewsletterEmailsCrudService {
    private $newsletterEmailRepository;
    public function __construct(NewsletterEmailRepositoryInterface $newsletterEmailRepository){
        $this->newsletterEmailRepository  = $newsletterEmailRepository;
    }
    public function getAllNewsletterEmails()
    {
       return $this->newsletterEmailRepository->paginate(100);
    }

    public function SendNewsletterEmail(array $data)
    {
        $data =  Arr::only($data, ['subject', 'content']);
        $job = (new SendNewsletterEmails($data))
            	->delay(now()->addSeconds(2));
        dispatch($job);
        $data['date'] =  Carbon::now()->format('Y-m-d');
        $this->newsletterEmailRepository ->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.newsletter_email')]));
    }

    public function DeleteNewsletterEmail(NewsletterEmail $newsletterEmail)
    {
        $newsletterEmail->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.newsletter_email')]) );
    }

    public function batchDeleteNewsletterEmails(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_NewsletterEmails = $this->newsletterEmailRepository ->getMany($ids);
        $this->newsletterEmailRepository ->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.newsletter_emails')]) );
    }
}
