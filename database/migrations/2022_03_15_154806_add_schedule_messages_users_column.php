<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleMessagesUsersColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_messages_users', function (Blueprint $table) {
            if (!Schema::hasColumn('schedule_messages_users', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('schedule_messages_users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_messages_users', function (Blueprint $table) {
            if (Schema::hasColumn('schedule_messages_users', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('schedule_messages_users', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
}
