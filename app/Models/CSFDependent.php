<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CSFDependent extends Model
{
    use HasFactory;

    protected $table = 'csf_dependents';

    protected $fillable = [
        'csf_id',
        'philhealth_number',
        'last_name',
        'first_name',
        'middle_name',
        'birthdate',
        'name_extension',
        'relationship',
        'date_admitted',
        'institution_fees',
         'oral_examination',
         'date_discharged',
         'diagnosis',
         'representative',
         'representative_type',
         'member_incapacitated',
         'sex',
         'is_referred',
         'hci_name',
         'pan_referring_hci',
         'date_of_referral',
         'referral_transaction_code',
         'institution_name',
         'type_institution',
         'final_diagnosis',
         'procedure',

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
        'oral_examination' => 'array',
    ];

    

    // ✅ Relationship: Dependent belongs to a CSF member
    public function csf()
{
    return $this->belongsTo(CSF::class, 'csf_id');
}
}
