<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csf_dependents', function (Blueprint $table) {
            $table->id();

            // Foreign key to CSF member
            $table->foreignId('csf_id')
                  ->constrained('csf')
                  ->onDelete('cascade');

            $table->string('philhealth_number');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->string('relationship');
            $table->date('birthdate');
            $table->dateTime('date_admitted');
            $table->dateTime('date_discharged');
            $table->integer('institution_fees');
            $table->json('oral_examination')->nullable();
            $table->string(column: 'diagnosis')->nullable();

            $table->string('representative')->nullable();
            $table->string('representative_type')->nullable();

            $table->string('member_incapacitated')->nullable();



            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csf_dependents');
    }
};
