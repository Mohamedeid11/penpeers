<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Book;
use App\Models\BookEdition;
use App\Models\BookParticipant;
use App\Models\BookPublishRequest;
use App\Models\Consult;
use App\Models\ContactMessage;
use App\Models\Country;
use App\Models\Faq;
use App\Models\Role;
use App\Models\User;
use App\Models\Genre;
use App\Models\Guide;
use App\Models\NewsletterEmail;
use App\Models\Occupation;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Models\Subscription;
use App\Policies\Admin\AdminPolicy;
use App\Policies\Admin\BlogPolicy;
use App\Policies\Admin\BookEditionPolicy;
use App\Policies\Admin\BookPolicy;
use App\Policies\Admin\ConsultPolicy;
use App\Policies\Admin\ContactMessagePolicy;
use App\Policies\Admin\CountryPolicy;
use App\Policies\Admin\RolePolicy;
use App\Policies\Admin\UserPolicy;
use App\Policies\Admin\GenrePolicy;
use App\Policies\Admin\PagePolicy;
use App\Policies\Admin\FaqPolicy;
use App\Policies\Admin\GuidePolicy;
use App\Policies\Admin\NewsletterEmailPolicy;
use App\Policies\Admin\OccupationPolicy;
use App\Policies\Admin\PlanPolicy;
use App\Policies\Admin\PublishRequestPolicy;
use App\Policies\Admin\SettingPolicy;
use App\Policies\Admin\SocialLinkPolicy;
use App\Policies\Admin\SubscriptionPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Role::class => RolePolicy::class,
        Plan::class => PlanPolicy::class,
        Country::class => CountryPolicy::class,
        Admin::class => AdminPolicy::class,
        User::class => UserPolicy::class,
        Genre::class => GenrePolicy::class,
        Page::class => PagePolicy::class,
        Faq::class => FaqPolicy::class,
        Guide::class => GuidePolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        NewsletterEmail::class => NewsletterEmailPolicy::class,
        Setting::class => SettingPolicy::class,
        SocialLink::class => SocialLinkPolicy::class,
        ContactMessage::class => ContactMessagePolicy::class,
        BookPublishRequest::class => PublishRequestPolicy::class,
        Book::class => BookPolicy::class,
        BookEdition::class => BookEditionPolicy::class,
        Consult::class => ConsultPolicy::class,
        Occupation::class => OccupationPolicy::class,
        Blog::class => BlogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
       
        $this->registerPolicies();

        Gate::define('publish-edition', function($user, Book $book, BookEdition $edition){
            return BookParticipant::leadAuthor($book->id)->first()->user_id == Auth::id() && $edition->book_id == $book->id;
        });
    }
}
