<?php
namespace App\Services\Admin;

use App\Models\ContactMessage;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;

class ContactMessagesCrudService {
    private $contactMessageRepository;
    public function __construct(ContactMessageRepositoryInterface $contactMessageRepository){
        $this->contactMessageRepository = $contactMessageRepository;
    }
    public function getAllContactMessages()
    {
        return $this->contactMessageRepository->paginateCustomOrder('read', 100);

    }

    public function tagRead(ContactMessage $contact_message)
    {
        if (!$contact_message->read ){
            $contact_message->update(['read'=>true]);
        }
    }

    public function deleteContactMessage(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.contact_message')]) );
    }

    public function batchDeleteContactMessages(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->contactMessageRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.contact_messages')]) );
    }
}
