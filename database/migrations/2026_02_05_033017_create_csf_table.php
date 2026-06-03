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

            $table->string('sex');
            $table->string('unit_no')->nullable();
            $table->string('building_name')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('village')->nullable();
            $table->string('brgy')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('landline_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();

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
        Schema::dropIfExists('csf');
    }
};

