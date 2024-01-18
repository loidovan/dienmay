<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCommentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_details', function ($table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('comment_id');
            $table->string('customer_name')->nullable()->after('comment_id');
            $table->string('customer_phone')->nullable()->after('comment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_details', function ($table) {
            $table->dropColumn('user_id');
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_phone');
        });
    }
}
