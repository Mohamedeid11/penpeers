<?php

namespace App\Providers;

use App\Repositories\Eloquent\BlogRepository;
use App\Repositories\Eloquent\BookEditionRepository;
use App\Repositories\Eloquent\BookParticipantRepository;
use App\Repositories\Eloquent\BookPublishRequestRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\BookRoleRepository;
use App\Repositories\Eloquent\ConsultRepository;
use App\Repositories\Eloquent\ContactMessageRepository;
use App\Repositories\Eloquent\CorporateEmployeeRepository;
use App\Repositories\Eloquent\CountryRepository;
use App\Repositories\Eloquent\EmailInvitationRepository;
use App\Repositories\Eloquent\GenreRepository;
use App\Repositories\Eloquent\PageRepository;
use App\Repositories\Eloquent\SpecialChapterRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\GuideRepository;
use App\Repositories\Eloquent\InterestRepository;
use App\Repositories\Eloquent\NewsletterEmailRepository;
use App\Repositories\Eloquent\OccupationRepository;
use App\Repositories\Eloquent\PlanRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\SocialLinkRepository;
use App\Repositories\Eloquent\SubscriptionRepository;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use App\Repositories\Interfaces\BookEditionRepositoryInterface;
use App\Repositories\Interfaces\BookParticipantRepositoryInterface;
use App\Repositories\Interfaces\BookPublishRequestRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\BookRoleRepositoryInterface;
use App\Repositories\Interfaces\ConsultRepositoryInterface;
use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\CorporateEmployeeRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\EmailInvitationRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\SpecialChapterRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\Interfaces\GuideRepositoryInterface;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\NewsletterEmailRepositoryInterface;
use App\Repositories\Interfaces\OccupationRepositoryInterface;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\SocialLinkRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
        $this->app->bind(SpecialChapterRepositoryInterface::class, SpecialChapterRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookRoleRepositoryInterface::class, BookRoleRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(GuideRepositoryInterface::class, GuideRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(NewsletterEmailRepositoryInterface::class, NewsletterEmailRepository::class);
        $this->app->bind(SocialLinkRepositoryInterface::class, SocialLinkRepository::class);
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
        $this->app->bind(EmailInvitationRepositoryInterface::class, EmailInvitationRepository::class);
        $this->app->bind(BookParticipantRepositoryInterface::class, BookParticipantRepository::class);
        $this->app->bind(BookEditionRepositoryInterface::class, BookEditionRepository::class);
        $this->app->bind(BookPublishRequestRepositoryInterface::class, BookPublishRequestRepository::class);
        $this->app->bind(ConsultRepositoryInterface::class, ConsultRepository::class);
        $this->app->bind(OccupationRepositoryInterface::class, OccupationRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(CorporateEmployeeRepositoryInterface::class, CorporateEmployeeRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(InterestRepositoryInterface::class, InterestRepository::class);
    }
}
