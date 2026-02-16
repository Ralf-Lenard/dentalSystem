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
        'diagnosis'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_admitted'    => 'datetime',
        'date_discharged'  => 'datetime',
        'oral_examination' => 'array',
    ];

    // ✅ Relationship: A CSF member has many dependents
    public function dependents()
    {
        // The second argument tells Laravel to use 'csf_id' 
        // instead of the guessed 'c_s_f_id'
        return $this->hasMany(CSFDependent::class, 'csf_id');
    }
}
