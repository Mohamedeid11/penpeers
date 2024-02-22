<?php
namespace App\Services\Admin;

use App\Models\Faq;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class FaqsCrudService {
    private $faqRepository;
    public function __construct(FaqRepositoryInterface $faqRepository){
        $this->faqRepository = $faqRepository;
    }
    public function getAllFaqs()
    {
       return $this->faqRepository->paginate(100);
    }

    public function createFaq(array $data)
    {
        $data =  Arr::only($data, ['question', 'answer']);
        $this->faqRepository->create($data);
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.faq')]) );
    }

    public function updateFaq(Faq $faq, array $data)
    {
        $data =  Arr::only($data, ['question', 'answer']);
        $faq->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.faq')])  );
    }

    public function deleteFaq(Faq $Faq)
    {
        $Faq->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.faq')]) );
    }

    public function batchDeleteFaqs(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $target_faqs = $this->faqRepository->getMany($ids);
        $this->faqRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.faq')]) );
    }
}
