<?php

namespace App\Models;

use App\Traits\Translatable;
use App\Traits\HasAdminForm;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Null_;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Translatable, HasAdminForm,  HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role_id',
        'profile_pic',
        'language',
        'account_type',
        'country_id',
        'company_id',
        'email_verified_at',
        'bio',
        'active',
        'mobile_number',
        'gender',
        'plan_id'
    ];
    public $trans_fields = ['bio'];
    protected $appends = ['plan', 'validity', 'items', 'can_renew', 'can_upgrade', 'free_period_after_expiry', 'is_public'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'social_links' => 'array',
    ];
    protected $attributes = [
        'profile_pic' => 'defaults/user.png'
    ];
    protected function form_fields()
    {
        $countries = Country::where(['active' => true])->get();

        $countries_choices = [];
        foreach ($countries as $country) {
            $countries_choices[$country->id] = $country->trans('name');
        }

        $roles = Role::where(['name' => 'author'])->get();

        $roles_choices = [];
        foreach ($roles as $role) {
            $roles_choices[$role->id] = $role->name;
        }

        return collect([
            'country_id' => collect(['type' => 'select', 'required' => 3, 'choices' => $countries_choices]),
            'role_id' => collect(['type' => 'select', 'required' => 3, 'choices' => $roles_choices]),
            'account_type' => collect([
                'type' => 'select', 'required' => 3,
                'choices' => ['personal' => __('global.personal'), 'corporate' => __('global.corporate')]
            ]),
            'name' => collect(['type' => 'text', 'required' => 3]),
            'username' => collect(['type' => 'text', 'required' => 3]),
            'email' => collect(['type' => 'email', 'required' => 3]),
            'password' => collect(['type' => 'password', 'required' => 2]),
            'profile_pic' => collect(['type' => 'file', 'required' => 0]),
            'active' => collect(['type' => 'checkbox']),
            'mobile_number' => collect(['type' => 'text', 'required' => 0]),
            'bio' => collect(['type' => 'text', 'required' => 0]),
            'gender' => collect(['type' => 'select', 'required' => 3, 'choices' => ['m', 'f']]),

        ]);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function user_plans()
    {
        return $this->company ? $this->company->user_plans() : $this->hasMany(UserPlan::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function scopeAuthors($query)
    {
        $query->whereHas('role', function (Builder $q) {
            $q->where(['name' => 'author']);
        });
    }
    public function scopeReaders($query)
    {
        $query->whereHas('role', function (Builder $q) {
            $q->where(['name' => 'reader']);
        });
    }
    public function ScopePublishers($query)
    {
        $query->whereHas('role', function (Builder $q) {
            $q->where(['name' => 'publisher']);
        });
    }
    public function scopePublic($query)
    {
        $query->whereNotNull('email_verified_at')
            ->where(function ($q) {
                $trial_period = Setting::where(['name' => 'trial_days'])->first()->value;
                $q->whereRaw("exists (select * from user_plans where users.id = user_plans.user_id and end_date >= NOW()) or exists (select * from user_plans where users.company_id = user_plans.user_id and end_date >= NOW())")
                    ->orWhere('created_at', '>=', Carbon::now()->subdays($trial_period));
            });
    }
    public function getIsPublicAttribute()
    {
        return (bool) self::public()->where(['id' => $this->id])->first();
    }
    public function books()
    {
        return $this->belongsToMany(Book::class, BookParticipant::class)->withPivot(['book_role_id'])->with('roles', function ($query) {
            $query->where(['book_participants.user_id' => $this->id]);
        })->where('book_participants.status' , 1);
    }

    public function lead_books()
    {
        return $this->books()->whereHas('roles', function (Builder $query) {
            $query->where(['name' => 'lead_author', 'user_id' => $this->id]);
        });
    }
    public function co_books()
    {
        return $this->books()->whereHas('roles', function (Builder $query) {
            $query->where(['name' => 'co_author', 'user_id' => $this->id]);
        });
    }
    public function review_books()
    {
        return $this->books()->whereHas('roles', function (Builder $query) {
            $query->where(['name' => 'reviewer', 'user_id' => $this->id]);
        });
    }
    public function published_books()
    {
        return $this->lead_books()->whereHas('editions', function (Builder $query) {
            $query->where('status',1);
        });
    }

    public function draft_books()
    {
        return $this->books()->where('completed', 0);
    }

    public function completed_books()
    {
        return $this->books()->where('completed', 1);
    }

    public function rediting_books()
    {
        return $this->books()->where('completed', 2);
    }

    public function shown_books()
    {
        return $this->books()->where('books.status', 1);
    }

    public function hidden_books()
    {
        return $this->books()->where('books.status', 0);
    }
    public function invitations()
    {
        return $this->hasMany(BookParticipant::class);
    }
    public function interests()
    {
        return $this->hasMany(UserInterest::class, 'user_id');
    }
    public function email_invitations()
    {
        return $this->hasMany(EmailInvitation::class, 'invited_by');
    }
    public function requests()
    {
        return $this->hasMany(BookParticipationRequest::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(CorporateEmployee::class, "user_id");
    }
    public function getPlanAttribute()
    {
        return $this->user_plans()->where('end_date', '>=', Carbon::now())->orderByDesc('created_at')->first();
    }
    public function getItemsAttribute()
    {
        if ($this->account_type == 'corporate') {
            if ($this->isInTrial()) {
                return Setting::where(['name' => 'trial_days'])->first()->value;
            }
            return $this->plan && $this->plan->plan->items > 0 ? $this->plan->plan->items : 0;
        }
        return 0;
    }

    public function isInTrial($returnValue = false)
    {
        if ($this->company) {
            return $this->company->isInTrial();
        }
        if (count($this->user_plans) > 0) {
            return false;
        }
        $now = Carbon::now();
        $diff = $this->created_at ? $this->created_at->diffInDays($now) : 0;
        $trial_period = Setting::where(['name' => 'trial_days'])->first()->value;
        if ($returnValue) {
            return $diff <= $trial_period ? $trial_period - $diff : false;
        } else {
            return $diff <= $trial_period;
        }
    }

    public function getValidityAttribute()
    {
        if ($this->isInTrial()) {
            return true;
        } elseif ($this->plan && $this->plan->remaining > -1 ) {
            return true;
        }
        return false;
    }
    public function getFreePeriodAfterExpiryAttribute()
    {
        $user_plans = $this->user_plans;
        if(count($user_plans) > 0){
            $end_date = Carbon::parse($user_plans->pluck('end_date')->last());
            $two_free_months = $end_date->addMonths(2)->format('Y-m-d');

            return $two_free_months >= date('Y-m-d');
        }

        return false;
    }
    public function getCanRenewAttribute()
    {
        return $this->plan ? ($this->plan->plan->account_type == $this->account_type) && $this->plan->remaining < 30 : false;
    }
    public function getCanUpgradeAttribute()
    {
        return $this->plan ? ($this->plan->plan->account_type == $this->account_type) && (bool) Plan::where(['account_type' => $this->account_type])->where('price', '>', $this->plan->plan->price)->get() : false;
    }
    public function getAvailablePlans($upgrade = false)
    {
        $operand = $upgrade ? '>' : '>=';
        $plan = $this->user_plans()->orderBy('created_at', 'desc')->first();
        if ($plan) {
            return Plan::where(['account_type' => $this->account_type])->where('price', $operand, $plan->plan->price)->get();
        } else {
            return Plan::where(['account_type' => $this->account_type])->get();
        }
    }
    public function company()
    {
        return $this->belongsTo(__CLASS__, 'company_id');
    }

    public function buyRequests()
    {
        return $this->hasMany(BookBuyRequest::class);
    }
    public function sender()
    {
        return $this->hasMany(TracingEmail::class , 'sender_id');
    }
    public function receiver()
    {
        return $this->hasMany(TracingEmail::class , 'receiver_id');
    }

    public function scopePersonal($query)
    {
        $query->where(['account_type' => 'personal']);
    }
    public function scopeCorporate($query)
    {
        $query->where(['account_type' => 'corporate']);
    }
    public function scopeEmployee($query)
    {
        $query->where(['account_type' => 'employee']);
    }
    public function scopeInvited($query, $book_id)
    {
        $query->whereHas('books', function (Builder $q) use($book_id) {
            $q->where(['book_id' => $book_id, 'user_id' => $this->id]);
        });
    }

}
