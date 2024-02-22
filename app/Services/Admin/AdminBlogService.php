<?php
namespace App\Services\Admin;

use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;

use Illuminate\Support\Facades\Storage;

class AdminBlogService {
    private $blogRepository;
    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getAllBlogs(){
        return $this->blogRepository->paginate(100);
    }

    public function createNewPost($request){
        $blog_image = $request->file('image')->store('uploads/'.$request->user_id.'/blog-posts/', ['disk'=>'public']);
        $this->blogRepository->create(array_merge($request->only(['user_id', 'title', 'description']), ['image' => $blog_image]));
        session()->flash('success', __('admin.success_add', ['thing'=>__('global.post')]));
    }

    public function updatePost($request, Blog $blog){
        $blog_image = $request->hasFile('image')? 
            $request->file('image')->store('uploads/'.$request->user_id.'/blog-posts/', ['disk'=>'public']) : 
            $blog->image;
        $blog->update(array_merge($request->only(['user_id', 'title', 'description']), ['image' => $blog_image]));
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.post')]));
    }

    public function deletePost(Blog $blog){
        $blog->delete();
        session()->flash('success', __('admin.success_delete', ['thing'=>__('global.post')]));

    }

    public function batchDeleteblogs(array $data)
    {
        $ids = json_decode($data['bulk_delete'], true);
        $this->blogRepository->deleteMany($ids);
        session()->flash('success',  __('admin.success_delete', ['thing'=>__('global.posts')]) );
    }

    public function approvePost(Blog $blog){
        $blog->update(['approved' => true]);
        session()->flash('success',  __('admin.success_approve', ['thing'=>__('global.post')]) );
    }
}