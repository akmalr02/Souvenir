<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSouvenirsTable extends Migration
{
    public function up()
    {
        Schema::create('souvenirs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            $table->double('total');
            $table->json('image')->nullable();
            $table->string('pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('souvenirs');
    }
}
