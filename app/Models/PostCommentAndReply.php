<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentAndReply extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
     'type', 'user_id' , 'comment' , 'blog_id' , 'comment_id'
    ];

   protected $table = 'post_comments_and_relpies';

   protected $appends = ['created_at_formated'];

   public function replies()
   {
    return $this->hasMany(PostCommentAndReply::class,'comment_id','id');
   }

   public function user()
   {
    return $this->belongsTo(User::class);
   }

   public function getCreatedAtFormatedAttribute()
   {
        return $this->created_at->format('F j, Y, g:i a');
   }
}
