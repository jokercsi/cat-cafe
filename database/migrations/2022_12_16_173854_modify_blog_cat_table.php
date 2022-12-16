<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_cat', function (Blueprint $table) {
            $table->dropForeign('blog_id');
            $table->dropIndex('blog_id');
            $table->dropForeign('cat_id');
            $table->dropIndex('cat_id');
            // $table->id()->onDelete('cascade');
            // $table->foreignId('blog_id')->constrained()->onDelete('cascade')->change();
            // $table->foreignId('cat_id')->constrained()->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
