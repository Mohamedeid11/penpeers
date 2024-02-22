<?php

namespace App\Models;

use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Book extends Model
{
    use HasFactory, Translatable, Sluggable, HasAdminForm;
    protected $fillable = [
        'title', 'description', 'language', 'front_cover', 'back_cover', 'genre_id', 'visibility',
        'label_first_edition', 'slug', 'receive_requests', 'completed', 'editing_status_changed_at',
        'lead_author', 'status', 'sample' , 'price' , 'status_changed_at' , 'deleted_at'
    ];
    protected $casts = [
        'receive_requests' => 'boolean',
        'label_first_edition' => 'boolean',
        'popular' => 'boolean',
    ];
    protected $appends = ['is_published', 'rate'];
    public $trans_fields = ['title', 'description'];
    /**
     * @return BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, BookParticipant::class)->where('status', 1)->orderBy('book_role_id', 'asc')->withTimestamps();
    }
    public function email_invitations(): HasMany
    {
        return $this->hasMany(EmailInvitation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BookReview::class);
    }

    public function book_participants()
    {
        return $this->hasMany(BookParticipant::class)->orderBy('book_role_id', 'asc');
    }
    /**
     * @return HasMany
     */
    public function editions(): HasMany
    {
        return $this->hasMany(BookEdition::class);
    }
    public function roles()
    {
        return $this->belongsToMany(BookRole::class, BookParticipant::class)->withPivot(['user_id']);
    }
    public function special_chapters(): BelongsToMany
    {
        return $this->belongsToMany(SpecialChapter::class, BookSpecialChapter::class)->withPivot(['book_edition_id', 'deadline']);
    }
    public function book_special_chapters()
    {
        return $this->hasMany(BookSpecialChapter::class);
    }
    public function book_chapters()
    {
        return $this->hasMany(BookChapter::class);
    }

    public function special_chapters_authors()
    {
        return $this->belongsToMany(User::class, 'book_special_chapter_authors', 'book_id', 'user_id')
            ->withPivot(['book_special_chapter_id', 'book_edition_id']);
    }
    public function book_chapter_authors()
    {
        return $this->belongsToMany(User::class, 'book_chapter_authors', 'book_id', 'user_id')
            ->withPivot(['book_edition_id']);
    }

    public function getBookSpecialChapter($special_chapter, $book_edition)
    {
        $book = $this;
        return BookSpecialChapter::whereHas('book', function (Builder $query) use ($book) {
            $query->where(['books.id' => $book->id]);
        })->whereHas('book_edition', function (Builder $query) use ($book_edition) {
            $query->where(['book_editions.id' => $book_edition->id]);
        })->whereHas('special_chapter', function (Builder $query) use ($special_chapter) {
            $query->where(['special_chapters.id' => $special_chapter->id]);
        })->first();
    }
    public function getSpecialChapterAuthor($special_chapter, $book_edition)
    {
        $book_special_chapter = $this->getBookSpecialChapter($special_chapter, $book_edition);
        return $this->special_chapters_authors()->wherePivot('book_special_chapter_id', $book_special_chapter->id)
            ->wherePivot('book_edition_id', $book_edition->id)->first();
    }
    public function scopePublic($query)
    {
        $query->where(['visibility' => 'public']);
    }
    public function scopeShared($query)
    {
        $query->where(['visibility' => 'shared']);
    }
    public function scopePopular($query)
    {
        $query->where(['popular' => 1]);
    }
    public function scopeCompleted($query)
    {

        if (count($this->book_chapters) > 0) {
            foreach ($this->book_chapters as $chapter) {
                if (!$chapter->completed) {
                    return false;
                }
            };
        }

        if (count($this->book_special_chapters) > 0) {
            foreach ($this->book_special_chapters as $special_chapter) {
                if (!$special_chapter->completed) {
                    return false;
                }
            };
        }

        if ($this->completed != 1) {
            return false;
        }

        return true;
    }

    public function scopeAllChaptersCompleted($query)
    {

        if (count($this->book_chapters) > 0) {
            foreach ($this->book_chapters as $chapter) {
                if (!$chapter->completed) {
                    return false;
                }
            };
        }

        if (count($this->book_special_chapters) > 0) {
            foreach ($this->book_special_chapters as $special_chapter) {
                if (!$special_chapter->completed) {
                    return false;
                }
            };
        }

        return true;
    }

    public function scopeAllEditionsCompleted($query)
    {
        $editions = $this->editions;
        if (count($editions) > 0) {
            foreach ($editions as $edition) {
                if ($edition->status != 1) {
                    return false;
                }
            };
        }

        return true;
    }

    public function scopeReceivable($query)
    {
        // $query->where(['receive_requests'=>true])->whereHas('editions', function (Builder $q){
        //     $q->whereDoesntHave('publish_requests', function(Builder $in_q) {
        //         $in_q->where('publication_date', '!=', null)->where('approved', '=',true);
        //     });
        // });
        $query->where(['receive_requests' => true]);
    }
    public function getIsPublishedAttribute()
    {
        return $this->exists ?  (bool)  self::published()->where(['id' => $this->id])->first() : false;
    }
    public function getRateAttribute()
    {
        $reviews = $this->reviews();

        return $reviews->count() > 0 ? (int) round($reviews->sum('rate') / $reviews->count()) : 0;
    }
    public function getLeadAuthorAttribute()
    {
        return  BookParticipant::where(['book_id' => $this->id, 'book_role_id' => 1])->first()->user ?? [];
    }
    public function scopePublished($query)
    {
        $query->whereHas('editions', function (Builder $q) {
            // $q->whereHas('publish_requests', function(Builder $in_q) {
            $q->where('status',1);
            // });
        });
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    public function requests()
    {
        return $this->hasMany(BookParticipationRequest::class);
    }

    public function buyRequests()
    {
        return $this->hasMany(BookBuyRequest::class);
    }

    public function tracing_email()
    {
        return $this->hasMany(TracingEmail::class);
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title'],
            ]
        ];
    }

    protected function form_fields()
    {
        $users = User::get(['id', 'name']);
        $usersChoices = [];
        foreach ($users as $user) {
            $usersChoices[$user['id']] = $user->trans('name');
        }

        $genres = Genre::get(['id', 'name']);
        $genresChoices = [];
        foreach ($genres as $genre) {
            $genresChoices[$genre['id']] = $genre->trans('name');
        }

        $languagesChoices = [];
        foreach (locales() as $key => $locale) {
            $languagesChoices[$key] = $locale['native'];
        }

        return collect([
            'author_id' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => $usersChoices
            ]),
            'genre_id' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => $genresChoices
            ]),
            'title' => collect(['type' => 'text', 'required' => 3]),
            'description' => collect(['type' => 'textarea', 'required' => 3]),
//            'front_cover' => collect(['type' => 'file', 'required' => 2]),
//            'back_cover' => collect(['type' => 'file', 'required' => 2]),
            'language' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => $languagesChoices
            ]),
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
