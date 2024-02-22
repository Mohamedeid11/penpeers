<?php
namespace App\Services\Admin;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class PagesCrudService {
    private $pageRepository;
    public function __construct(PageRepositoryInterface $pageRepository){
        $this->pageRepository = $pageRepository;
    }
    public function getAllPages(): LengthAwarePaginator
    {
        return $this->pageRepository->paginate(100);
    }

    public function createPage(array $data)
    {
        $this->pageRepository->create(Arr::only($data, ['name', 'content']));
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.page')]) );
    }

    public function updatePage(Page $page, array $data)
    {
        if($page->is_system){
            $page->update(['content'=>$data['content']]);
        }
        else {
            $page->update(Arr::only($data, ['content', 'name']));
        }
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.page')]) );
    }

    public function deletePage(Page $page)
    {
        $page->delete();
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.page')]) );
    }

    public function batchDeletePages(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->pageRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.page')]) );
    }
}
