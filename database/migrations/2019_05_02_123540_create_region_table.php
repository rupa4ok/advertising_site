<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionTable extends Migration
{
    public function up()
    {
        Schema::create('region', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            $table->integer('parent_id')->nullable()->references('id')->on('regions')->onDelete('CASCADE');
            $table->timestamps();
            $table->unique(['parent_id', 'slug']);
            $table->unique(['parent_id', 'name']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('region');
    }
}
