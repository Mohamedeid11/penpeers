<?php
namespace App\Services;

use App\Models\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BlogsService {

    private $blogRepository;
    public function __construct(BlogRepositoryInterface $blogRepository){
        $this->blogRepository = $blogRepository;
    }

    public function getAllPosts(){
        return Blog::where('user_id', auth()->id())->get();
    }

    public function createNewPost($request){
        $user = auth()->user();
//        dd($request->all());
        if ($request->has('image_revertImage') || !$request->hasFile('image') )
        {
            $post_image = 'uploads/' . $user->id . '/blog-posts/blog.jpg';
            if(!Storage::disk('public')->exists($post_image))
            {
                Storage::disk('public')->copy('defaults/front.png' , $post_image);
            }
        }else{
            $post_image = $request->file('image')->store('uploads/'.$user->id.'/blog-posts/', ['disk'=>'public']);
        }
        $this->blogRepository->create(array_merge($request->only(['title', 'description']), ['user_id'=>$user->id, 'image' => $post_image, 'approved'=> 1]));
    }

    public function updatePost($request, Blog $post){
        $post_image = $request->hasFile('image')?
                    $request->file('image')->store('uploads/'.$post->user_id.'/blog-posts/', ['disk'=>'public']) :
                    $post->image;
        if ($request->has('image_revertImage'))
        {
            $post_image = 'uploads/' . $post->user_id . '/blog-posts/blog.jpg';
            if(!Storage::disk('public')->exists($post_image))
            {
                Storage::disk('public')->copy('defaults/front.png' , $post_image);
            }
        }
        $post->update(array_merge($request->only(['title', 'description']), ['image' => $post_image]));
    }
}
