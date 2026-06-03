<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CSF extends Model
{
    use HasFactory;

    protected $table = 'csf';

    protected $fillable = [
        'philhealth_number',
        'last_name',
        'first_name',
        'middle_name',
        'birthdate',
        'name_extension',
        'date_admitted',
        'institution_fees',
        'date_discharged',
        'oral_examination',
        'diagnosis',

        'sex',
        'unit_no',
        'building_name',
        'house_no',
        'street',
        'village',
        'brgy',
        'municipality',
        'province',
        'country',
        'zip_code',
        'landline_no',
        'mobile_no',
        'email_address',
        'is_referred',
        'hci_name',
        'pan_referring_hci',
        'date_of_referral',
        'referral_transaction_code',
        'institution_name',
        'type_institution',

        
        'first_tooth',
        'second_tooth',
        'third_tooth',
        'fourth_tooth',

        'first_tooth_service',
        'second_tooth_service',
        'third_tooth_service',
        'fourth_tooth_service',

        'mandatory_discount',
        'philhealth_benefit'

    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_admitted'    => 'datetime',
        'date_discharged'  => 'datetime',
    ];

    // ✅ Relationship: A CSF member has many dependents
    public function dependents()
    {
        // The second argument tells Laravel to use 'csf_id' 
        // instead of the guessed 'c_s_f_id'
        return $this->hasMany(CSFDependent::class, 'csf_id');
    }
}
