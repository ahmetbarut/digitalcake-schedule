<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailFromColumnToScheduleMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_messages', function (Blueprint $table) {
            $table->string('mail_from')->nullable()->after('attachment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_messages', function (Blueprint $table) {
            $table->dropColumn('mail_from');
        });
    }
}
