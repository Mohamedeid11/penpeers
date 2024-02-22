<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Web\BaseWebController;
use App\Http\Requests\Web\Dashboard\Blogs\CreatePostRequest;
use App\Http\Requests\Web\Dashboard\Blogs\UpdatePostRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Services\BlogsService;

class DashboardBlogsController extends BaseWebController
{
    private $blogsService;
    public function __construct(BlogsService $blogsService)
    {
        parent::__construct();
        $this->blogsService = $blogsService;

        $this->middleware('check_plan_validity')->except('index');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->blogsService->getAllPosts();
        return view('web.dashboard.blogs.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.dashboard.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $this->blogsService->createNewPost($request);
        return redirect(route('web.dashboard.blogs.index'))->with('success',  __('global.post_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $post = $blog->where('user_id', auth()->id())->findOrFail($blog->id);
        return view('web.dashboard.blogs.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Blog $blog)
    {
        $post = $blog->where('user_id', auth()->id())->findOrFail($blog->id);
        $this->blogsService->updatePost($request, $post);
        return redirect(route('web.dashboard.blogs.index'))->with('success',  __('global.post_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $post = $blog->where('user_id', auth()->id())->findOrFail($blog->id);
        $post->delete();
        return back()->with('success',__('global.post_deleted') );
    }
}
