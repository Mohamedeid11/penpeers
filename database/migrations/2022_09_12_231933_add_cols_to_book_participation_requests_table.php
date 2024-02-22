<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToBookParticipationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_participation_requests', function (Blueprint $table) {
            $table->text('name')->nullable()->after('user_id');
            $table->text('email')->nullable()->after('user_id');
            $table->text('message')->nullable(false)->after('user_id');
            $table->text('bio')->nullable()->after('user_id');
            $table->dateTime('accepted_at')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_participation_requests', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('message');
            $table->dropColumn('bio');
        });
    }
}
