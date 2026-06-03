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
            
            $table->json('oral_examination')->nullable();
           

            $table->string('representative')->nullable();
            $table->string('representative_type')->nullable();

            $table->string('member_incapacitated')->nullable();

            $table->string('sex');
            // REFFERAL
            $table->boolean('is_referred')->default(false);
            // If YES, these must be filled
            $table->string('hci_name')->nullable();
            $table->string('pan_referring_hci')->nullable();
            $table->date('date_of_referral')->nullable();
            $table->string('referral_transaction_code')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('type_institution')->nullable();

            // diagnosis
            $table->string(column: 'diagnosis')->nullable();
            $table->string(column: 'final_diagnosis')->nullable();
            $table->string(column: 'procedure')->nullable();

            // tooth number
            $table->integer(column: 'first_tooth')->nullable();
            $table->integer(column: 'second_tooth')->nullable();
            $table->integer(column: 'third_tooth')->nullable();
            $table->integer(column: 'fourth_tooth')->nullable();

            $table->string('first_tooth_service')->nullable();
            $table->string('second_tooth_service')->nullable();
            $table->string('third_tooth_service')->nullable();
            $table->string('fourth_tooth_service')->nullable();

            // payment
            $table->integer('institution_fees')->nullable();
            $table->integer('mandatory_discount')->nullable();
            $table->integer('philhealth_benefit')->nullable();




            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csf_dependents');
    }
};
