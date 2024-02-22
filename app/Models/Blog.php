<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory, Translatable, HasAdminForm;
    public $trans_fields = ['title', 'description'];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(PostCommentAndReply::class)->where('comment_id',null)->orderBy('id','ASC');
    }


    public function scopeApproved($query){
        $query->where('approved', 1);
    }

    protected function form_fields(){
        $users = User::get(['id', 'name']);
        $usersChoices = [];
        foreach($users as $user){
            $usersChoices[$user['id']] = $user->name;
        }
        return collect([
            'user_id' => collect(['type'=>'select', 'required'=>3, 'choices' => $usersChoices]),
            'title' => collect(['type'=>'text', 'required'=>3]),
            'description' => collect(['type'=>'textarea', 'required'=>3]),
            'image' => collect(['type'=>'file', 'required'=>2]),
        ]);
    }
}
