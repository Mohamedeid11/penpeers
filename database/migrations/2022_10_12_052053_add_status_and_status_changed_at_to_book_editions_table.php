<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndStatusChangedAtToBookEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_editions', function (Blueprint $table) {
            $table->date('status_changed_at')->nullable()->after('publication_date');
            $table->tinyInteger('status')->default(0)->after('publication_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_editions', function (Blueprint $table) {
            $table->dropColumn('status_changed_at');
            $table->dropColumn('status');

        });
    }
}
