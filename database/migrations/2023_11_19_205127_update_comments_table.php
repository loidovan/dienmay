<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function ($table) {
            $table->dropForeign('comments_customer_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
            $table->dropColumn('customer_id');
            $table->dropColumn('user_id');
            $table->string('customer_name')->nullable()->after('product_id');
            $table->string('customer_phone')->nullable()->after('product_id');
            $table->string('content')->nullable()->after('product_id');
            $table->integer('like_qty')->default(0)->after('product_id');
            $table->integer('unlike_qty')->default(0)->after('product_id');
            $table->integer('status')->default(0)->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function ($table) {
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->dropColumn('customer_name');
            $table->dropColumn('customer_phone');
            $table->dropColumn('content');
            $table->dropColumn('like_qty');
            $table->dropColumn('unlike_qty');
            $table->dropColumn('status');
        });
    }
}
