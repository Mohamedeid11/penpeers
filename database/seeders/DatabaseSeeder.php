<?php

namespace Database\Seeders;

use App\Models\BookReview;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            GenreSeeder::class,
            PermissionCategorySeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            AdminSeeder::class,
            PlanSeeder::class,
            UserSeeder::class,
            BookRoleSeeder::class,
            BookSeeder::class,
            SpecialChapterSeeder::class,
            EmailInvitationSeeder::class,
            PageSeeder::class,
            FaqSeeder::class,
            GuideSeeder::class,
            GuideSeeder::class,
            SubscriptionSeeder::class,
            SettingSeeder::class,
            SocialLinkSeeder::class,
            ContactMessageSeeder::class,
            OccupationSeeder::class,
            ConsultSeeder::class,
            BlogSeeder::class,
            BookSpecialChapterSeeder::class,
            BookSpecialChapterAuthorSeeder::class,
            BookChapterSeeder::class,
            BookChapterAuthorSeeder::class,
            InterestSeeder::class,
            BookReviewSeeder::class,
            BookBuyRequestSeeder::class,
            PostCommentsAndRepliesSeeder::class,
            UserInterestSeeder::class,
        ]);
    }
}
