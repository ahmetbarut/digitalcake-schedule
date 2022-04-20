<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageColumnToEmailSendSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_send_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('email_send_settings', 'subject')) {
                $table->string('subject')->nullable();
            }
            
            if (!Schema::hasColumn('email_send_settings', 'message')) {
                $table->text('message')->nullable();
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
        Schema::table('email_send_settings', function (Blueprint $table) {
            $columns = [];

            Schema::hasColumn('email_send_settings', 'subject') && $columns[] = 'subject';
            Schema::hasColumn('email_send_settings', 'message') && $columns[] = 'message';
            
            $table->dropColumn($columns);
        });
    }
}
