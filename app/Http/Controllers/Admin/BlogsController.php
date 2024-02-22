<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blogs\ApprovePostRequest;
use App\Http\Requests\Admin\Blogs\CreatePostRequest;
use App\Http\Requests\Admin\Blogs\DeletePostRequest;
use App\Http\Requests\Admin\Blogs\UpdatePostRequest;
use App\Services\Admin\AdminBlogService;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogsController extends Controller
{
    private $adminBlogService;
    public function __construct(AdminBlogService $adminBlogService)
    {
        $this->authorizeResource(Blog::class);
        $this->adminBlogService = $adminBlogService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = $this->adminBlogService->getAllBlogs();
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blog = new Blog();
        return view('admin.blogs.create-edit', compact('blog'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $this->adminBlogService->createNewPost($request);
        return redirect(route('admin.blogs.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.create-edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Blog $blog)
    {
        $this->adminBlogService->updatePost($request, $blog);
        return redirect(route('admin.blogs.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePostRequest $request, Blog $blog)
    {
        $this->adminBlogService->deletePost($blog);
        return redirect(route('admin.blogs.index'));
    }

    public function batchDestroy(DeletePostRequest $request){
        $this->adminBlogService->batchDeleteblogs($request->all());
        return redirect(route('admin.blogs.index'));
    }

    public function approvePost(Blog $blog){
        $this->authorize('approve', $blog);
        $this->adminBlogService->approvePost($blog);
        return redirect(route('admin.blogs.index'));
    }
}
