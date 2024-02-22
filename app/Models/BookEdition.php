<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookEdition extends Model
{
    use HasFactory, HasAdminForm;

    protected $fillable = ['edition_number', 'original_price', 'discount_price', 'visibility', 'publication_date','status' ,'status_changed_at', 'title', 'description', 'language', 'front_cover', 'back_cover', 'genre_id', 'is_hidden', 'is_hidden_changed_at'];
    public function book_publish_requests()
    {
        return $this->hasMany(BookPublishRequest::class)->orderByDesc('created_at');
    }
    public function chapters()
    {
        return $this->hasMany(BookChapter::class);
    }
    public function special_chapters()
    {
        return $this->hasMany(BookSpecialChapter::class);
    }
    public function publish_requests()
    {
        return $this->hasMany(BookPublishRequest::class);
    }
    public function scopePublished($query)
    {
        // $query->whereHas('publish_requests', function (Builder $in_q) {
        //     $in_q->where('publication_date', '!=', null)->where('approved', '=', true);
        // });
        $query->where('status', 1);
    }
    protected function form_fields()
    {
        return collect([
            'original_price' => collect(['type' => 'number', 'required' => 3]),
            'discount_price' => collect(['type' => 'number', 'required' => 2]),
            'visibility' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => [
                    'public' => __('admin.public'),
                    'private' => __('admin.private'),
                    'shared' => __('admin.shared'),
                ]
            ]),
        ]);
    }
}
