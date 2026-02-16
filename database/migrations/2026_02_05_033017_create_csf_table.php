<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csf', function (Blueprint $table) {
            $table->id();
            $table->string('philhealth_number')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();

            $table->date('birthdate');
            $table->dateTime('date_admitted')->nullable();
            $table->dateTime('date_discharged')->nullable();
            $table->json('oral_examination')->nullable();
            $table->integer('institution_fees');
            $table->string(column: 'diagnosis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csf');
    }
};

