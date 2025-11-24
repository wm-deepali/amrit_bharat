<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToVideoCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('video_comments', function (Blueprint $table) {
            $table->enum('status', ['Approved', 'Blocked'])
                  ->default('Approved')
                  ->after('comment'); // adjust column position as needed
        });
    }

    public function down()
    {
        Schema::table('video_comments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
