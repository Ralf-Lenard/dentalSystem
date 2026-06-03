<?php

namespace App\Http\Controllers;

use App\Models\CSF;
use App\Models\CSFDependent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CSFController extends Controller
{
    /**
     * Display all CSF members with dependents
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $csf = CSF::with('dependents')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('philhealth_number', 'like', "%{$search}%")
                        // This allows searching "First Last" or "Last First"
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name)"), 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return Inertia::render('CSF', [
            'csf' => $csf,
            'filters' => $request->only(['search'])
        ]);
    }
    /**
     * Store new CSF member with optional dependents
     */

    //  public function store(Request $request)
    //  {
    //      $request->validate([
    //          'philhealth_number' => 'required|unique:csf,philhealth_number',
    //          'last_name' => 'required|string|max:255',
    //          'first_name' => 'required|string|max:255',
    //          'middle_name' => 'nullable|string|max:255',
    //          'name_extension' => 'nullable|string|max:255',
    //          'birthdate' => 'required|date',
    //          'date_admitted' => 'nullable|date',
    //          'date_discharged' => 'nullable|date|after_or_equal:date_admitted',

    //          'oral_examination' => 'nullable|array',
    //          'institution_fees' => 'required|numeric|min:0',
    //          'diagnosis' => 'nullable|string|max:255',

    //          // ✅ Dependents
    //          'dependents' => 'nullable|array',
    //          'dependents.*.philhealth_number' => 'required_with:dependents|string',
    //          'dependents.*.last_name' => 'required_with:dependents|string|max:255',
    //          'dependents.*.first_name' => 'required_with:dependents|string|max:255',
    //          'dependents.*.middle_name' => 'nullable|string|max:255',
    //          'dependents.*.name_extension' => 'nullable|string|max:255',
    //          'dependents.*.birthdate' => 'required_with:dependents|date',
    //          'dependents.*.date_admitted' => 'required_with:dependents|date',
    //          'dependents.*.date_discharged' => 'required_with:dependents|date|after_or_equal:dependents.*.date_admitted',
    //          'dependents.*.relationship' => 'required_with:dependents|in:child,spouse,parent',
    //          'dependents.*.diagnosis' => 'nullable|string|max:255',

    //          // ✅ Representative
    //          'dependents.*.representative_type' => 'nullable|string|max:255',
    //          'dependents.*.representative_other_type' => 'nullable|string|max:255',
    //          'dependents.*.representative' => 'nullable|string|max:255',

    //          // ✅ Member incapacitated logic
    //          'member_incapacitated_option' => 'nullable|in:yes,other',
    //          'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
    //      ]);

    //      // Determine what to store in one column
    //      if ($request->member_incapacitated_option === 'yes') {
    //          $memberIncapacitated = 'Yes';
    //      } else {
    //          $memberIncapacitated = $request->member_incapacitated_reason;
    //      }

    //      // ✅ Create CSF
    //      $csf = CSF::create([
    //          'philhealth_number' => $request->philhealth_number,
    //          'last_name' => $request->last_name,
    //          'first_name' => $request->first_name,
    //          'middle_name' => $request->middle_name,
    //          'name_extension' => $request->name_extension,
    //          'birthdate' => $request->birthdate,
    //          'date_admitted' => $request->date_admitted,
    //          'date_discharged' => $request->date_discharged,
    //          'oral_examination' => $request->oral_examination,
    //          'institution_fees' => $request->institution_fees,
    //          'diagnosis' => $request->diagnosis,
    //         //  'member_incapacitated' => $memberIncapacitated, // ✅ Save in one column
    //      ]);

    //      // ✅ Save Dependents
    //      if ($request->has('dependents') && is_array($request->dependents)) {

    //          foreach ($request->dependents as $dependent) {

    //              // 🔥 If "others" selected → replace with actual typed value
    //              $representativeType = $dependent['representative_type'] ?? null;

    //              if ($representativeType === 'others') {
    //                  $representativeType = $dependent['representative_other_type'] ?? null;
    //              }

    //              $csf->dependents()->create([
    //                  'philhealth_number' => $dependent['philhealth_number'] ?? null,
    //                  'last_name' => $dependent['last_name'] ?? null,
    //                  'first_name' => $dependent['first_name'] ?? null,
    //                  'middle_name' => $dependent['middle_name'] ?? null,
    //                  'name_extension' => $dependent['name_extension'] ?? null,
    //                  'birthdate' => $dependent['birthdate'] ?? null,
    //                  'date_admitted' => $dependent['date_admitted'] ?? null,
    //                  'date_discharged' => $dependent['date_discharged'] ?? null,
    //                  'relationship' => $dependent['relationship'] ?? null,
    //                  'diagnosis' => $dependent['diagnosis'] ?? null,

    //                  // ✅ Final cleaned value
    //                  'representative_type' => $representativeType,

    //                  'representative' => $dependent['representative'] ?? null,
    //              ]);
    //          }
    //      }

    //      return redirect()->back()->with('success', 'CSF Member created successfully');

    //  }

    public function store(Request $request)
    {
        // Normalize referral flag
        $request->merge([
            'is_referred' => (int) $request->is_referred,
        ]);
    
        // =========================
        // VALIDATION
        // =========================
        $validated = $request->validate([
    
            // Basic Info
            'philhealth_number' => 'required|string|unique:csf,philhealth_number',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
    
            'date_admitted' => 'nullable|date',
            'date_discharged' => 'nullable|date|after_or_equal:date_admitted',
    
            // Address
            'unit_no' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'brgy' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'landline_no' => 'nullable|string|max:50',
            'mobile_no' => 'nullable|string|max:50',
            'email_address' => 'nullable|email|max:255',
    
            // Referral
            'is_referred' => 'required|boolean',
            'hci_name' => 'nullable|string|max:255',
            'pan_referring_hci' => 'nullable|string|max:255',
            'date_of_referral' => 'nullable|date',
            'referral_transaction_code' => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'type_institution' => 'nullable|string|max:255',
    
            // Dental / Fees / Diagnosis
            'first_tooth' => 'nullable|numeric|min:0',
            'second_tooth' => 'nullable|numeric|min:0',
            'third_tooth' => 'nullable|numeric|min:0',
            'fourth_tooth' => 'nullable|numeric|min:0',
    
            'first_tooth_service' => 'nullable|string|max:255',
            'second_tooth_service' => 'nullable|string|max:255',
            'third_tooth_service' => 'nullable|string|max:255',
            'fourth_tooth_service' => 'nullable|string|max:255',
    
            'institution_fees' => 'nullable|numeric|min:0',
            'mandatory_discount' => 'nullable|numeric|min:0',
            'philhealth_benefit' => 'nullable|numeric|min:0',
    
            'diagnosis' => 'nullable|string|max:255',
            'final_diagnosis' => 'nullable|string|max:255',
            'procedure' => 'nullable|string|max:255',
        ]);
    
        // =========================
        // CONDITIONAL REFERRAL VALIDATION
        // =========================
        if ($request->is_referred) {
            $request->validate([
                'hci_name' => 'required|string|max:255',
                'pan_referring_hci' => 'required|string|max:255',
                'date_of_referral' => 'required|date',
                'referral_transaction_code' => 'required|string|max:255',
                'institution_name' => 'required|string|max:255',
                'type_institution' => 'required|string|max:255',
            ]);
        }
    
        // =========================
        // CREATE RECORD
        // =========================
        CSF::create([
            ...$validated,
    
            // Clean referral fields if not referred
            'hci_name' => $request->is_referred ? $request->hci_name : null,
            'pan_referring_hci' => $request->is_referred ? $request->pan_referring_hci : null,
            'date_of_referral' => $request->is_referred ? $request->date_of_referral : null,
            'referral_transaction_code' => $request->is_referred ? $request->referral_transaction_code : null,
            'institution_name' => $request->is_referred ? $request->institution_name : null,
            'type_institution' => $request->is_referred ? $request->type_institution : null,
        ]);
    
        return redirect()->back()->with('success', 'CSF Member created successfully');
    }


    /**
     * Update CSF member and optionally dependents
     */
    public function update(Request $request, $id)
    {
        $csf = CSF::findOrFail($id);
    
        // Normalize referral flag
        $request->merge([
            'is_referred' => (int) $request->is_referred,
        ]);
    
        // =========================
        // MAIN VALIDATION
        // =========================
        $validated = $request->validate([
    
            // Basic Info
            'philhealth_number' => 'required|string|unique:csf,philhealth_number,' . $csf->id,
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
    
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
    
            'date_admitted' => 'nullable|date',
            'date_discharged' => 'nullable|date|after_or_equal:date_admitted',
    
            'oral_examination' => 'nullable|array',
            'diagnosis' => 'nullable|string|max:255',
            'final_diagnosis' => 'nullable|string|max:255',
            'procedure' => 'nullable|string|max:255',
    
            // Address
            'unit_no' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'brgy' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'landline_no' => 'nullable|string|max:50',
            'mobile_no' => 'nullable|string|max:50',
            'email_address' => 'nullable|email|max:255',
    
            // Fees
            'institution_fees' => 'nullable|numeric|min:0',
            'mandatory_discount' => 'nullable|numeric|min:0',
            'philhealth_benefit' => 'nullable|numeric|min:0',
    
            // Referral
            'is_referred' => 'required|boolean',
            'hci_name' => 'nullable|string|max:255',
            'pan_referring_hci' => 'nullable|string|max:255',
            'date_of_referral' => 'nullable|date',
            'referral_transaction_code' => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'type_institution' => 'nullable|string|max:255',
        ]);
    
        // =========================
        // CONDITIONAL REFERRAL VALIDATION
        // =========================
        if ($request->is_referred) {
            $request->validate([
                'hci_name' => 'required|string|max:255',
                'pan_referring_hci' => 'required|string|max:255',
                'date_of_referral' => 'required|date',
                'referral_transaction_code' => 'required|string|max:255',
                'institution_name' => 'required|string|max:255',
                'type_institution' => 'required|string|max:255',
            ]);
        }
    
        // =========================
        // UPDATE RECORD
        // =========================
        $csf->update([
            ...$validated,
    
            // Clean referral fields if not referred
            'hci_name' => $request->is_referred ? $request->hci_name : null,
            'pan_referring_hci' => $request->is_referred ? $request->pan_referring_hci : null,
            'date_of_referral' => $request->is_referred ? $request->date_of_referral : null,
            'referral_transaction_code' => $request->is_referred ? $request->referral_transaction_code : null,
            'institution_name' => $request->is_referred ? $request->institution_name : null,
            'type_institution' => $request->is_referred ? $request->type_institution : null,
        ]);
    
        return redirect()->back()->with('success', 'CSF Member updated successfully');
    }
    
    /**
     * Delete CSF member (dependents auto delete via cascade)
     */
    public function destroy($id)
    {
        $csf = CSF::findOrFail($id);
        $csf->delete();

        return redirect()->back()->with('success', 'CSF Member deleted successfully');
    }

    /**
     * Store Dependent for specific CSF member
     */
    public function storeDependent(Request $request, $csf_id)
    {
        $csf = CSF::findOrFail($csf_id);
    
        // 🔥 Force integer before validation
        $request->merge([
            'is_referred' => (int) $request->is_referred,
        ]);
    
        $request->validate([
            'philhealth_number' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'date_admitted' => 'required|date',
            'date_discharged' => 'required|date|after_or_equal:date_admitted',
            'sex' => 'required|in:Male,Female',
    
            'oral_examination' => 'nullable|array',
    
            // fees
            'institution_fees' => 'nullable|numeric|min:0',
            'mandatory_discount' => 'nullable|numeric|min:0',
            'philhealth_benefit' => 'nullable|numeric|min:0',
    
            'relationship' => 'required|in:child,spouse,parent',
            'diagnosis' => 'nullable|string|max:255',
            'final_diagnosis' => 'nullable|string|max:255',
            'procedure' => 'nullable|string|max:255',
    
            // Representative
            'representative_type' => 'nullable|string|max:255',
            'representative_other_type' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:255',
    
            // Incapacitated
            'member_incapacitated_option' => 'required|in:yes,other',
            'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
    
            // Referral
            'is_referred' => 'required|in:0,1',
    
            'hci_name' => 'required_if:is_referred,1|nullable|string|max:255',
            'pan_referring_hci' => 'required_if:is_referred,1|nullable|string|max:255',
            'date_of_referral' => 'required_if:is_referred,1|nullable|date',
            'referral_transaction_code' => 'required_if:is_referred,1|nullable|string|max:255',
            'institution_name' => 'required_if:is_referred,1|nullable|string|max:255',
            'type_institution' => 'required_if:is_referred,1|nullable|string|max:255',
    
            'first_tooth' => 'nullable|numeric|min:0',
            'second_tooth' => 'nullable|numeric|min:0',
            'third_tooth' => 'nullable|numeric|min:0',
            'fourth_tooth' => 'nullable|numeric|min:0',

            'first_tooth_service' => 'nullable|string|max:255',
            'second_tooth_service' => 'nullable|string|max:255',
            'third_tooth_service' => 'nullable|string|max:255',
            'fourth_tooth_service' => 'nullable|string|max:255',
        ]);
    
        // 🔥 Representative Fix
        $representativeType = $request->representative_type;
        if ($representativeType === 'others') {
            $representativeType = $request->representative_other_type;
        }
    
        // 🔥 Incapacitated Logic
        $memberIncapacitated = $request->member_incapacitated_option === 'yes'
            ? 'Yes'
            : $request->member_incapacitated_reason;
    
        // 🔥 Referral Logic (SAFE)
        $isReferred = (int) $request->is_referred;
    
        $dependent = $csf->dependents()->create([
            'philhealth_number' => $request->philhealth_number,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'name_extension' => $request->name_extension,
            'birthdate' => $request->birthdate,
            'date_admitted' => $request->date_admitted,
            'date_discharged' => $request->date_discharged,
            'oral_examination' => $request->oral_examination,
            'institution_fees' => $request->institution_fees,
            'mandatory_discount' => $request->mandatory_discount,
            'philhealth_benefit' => $request->philhealth_benefit,
            'diagnosis' => $request->diagnosis,
            'final_diagnosis' => $request->final_diagnosis,
            'procedure' => $request->procedure,
            'relationship' => $request->relationship,
            'sex' => $request->sex,
    
            // Representative
            'representative_type' => $representativeType,
            'representative' => $request->representative,
    
            // Incapacitated
            'member_incapacitated' => $memberIncapacitated,
    
            // Referral
            'is_referred' => $isReferred,
            'hci_name' => $isReferred ? $request->hci_name : null,
            'pan_referring_hci' => $isReferred ? $request->pan_referring_hci : null,
            'date_of_referral' => $isReferred ? $request->date_of_referral : null,
            'referral_transaction_code' => $isReferred ? $request->referral_transaction_code : null,
            'institution_name' => $isReferred ? $request->institution_name : null,
            'type_institution' => $isReferred ? $request->type_institution : null,
    
            'first_tooth' => $request->first_tooth,
            'second_tooth' => $request->second_tooth,
            'third_tooth' => $request->third_tooth,
            'fourth_tooth' => $request->fourth_tooth,

            'first_tooth_service' => $request->first_tooth_service,
            'second_tooth_service' => $request->second_tooth_service,
            'third_tooth_service' => $request->third_tooth_service,
            'fourth_tooth_service' => $request->fourth_tooth_service,


        ]);
    
        return redirect()->back()->with('success', 'Dependent added successfully');
    }
    /**
     * Update Dependent
     */
    public function updateDependent(Request $request, $id)
    {
        $dependent = CSFDependent::findOrFail($id);
    
        // 🔥 Force integer before validation (MATCH STORE)
        $request->merge([
            'is_referred' => (int) $request->is_referred,
        ]);
    
        $request->validate([
            'philhealth_number' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'date_admitted' => 'required|date',
            'date_discharged' => 'required|date|after_or_equal:date_admitted',
            'sex' => 'required|in:Male,Female',
    
            'oral_examination' => 'nullable|array',
    
            // Fees
            'institution_fees' => 'nullable|numeric|min:0',
            'mandatory_discount' => 'nullable|numeric|min:0',
            'philhealth_benefit' => 'nullable|numeric|min:0',
    
            'relationship' => 'required|in:child,spouse,parent',
            'diagnosis' => 'nullable|string|max:255',
            'final_diagnosis' => 'nullable|string|max:255',
            'procedure' => 'nullable|string|max:255',
    
            // Representative
            'representative_type' => 'nullable|string|max:255',
            'representative_other_type' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:255',
    
            // Incapacitated
            'member_incapacitated_option' => 'required|in:yes,other',
            'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
    
            // Referral (MATCH STORE)
            'is_referred' => 'required|in:0,1',
    
            'hci_name' => 'required_if:is_referred,1|nullable|string|max:255',
            'pan_referring_hci' => 'required_if:is_referred,1|nullable|string|max:255',
            'date_of_referral' => 'required_if:is_referred,1|nullable|date',
            'referral_transaction_code' => 'required_if:is_referred,1|nullable|string|max:255',
            'institution_name' => 'required_if:is_referred,1|nullable|string|max:255',
            'type_institution' => 'required_if:is_referred,1|nullable|string|max:255',
    
            // Teeth
            'first_tooth' => 'nullable|numeric|min:0',
            'second_tooth' => 'nullable|numeric|min:0',
            'third_tooth' => 'nullable|numeric|min:0',
            'fourth_tooth' => 'nullable|numeric|min:0',
    
            // Tooth Services
            'first_tooth_service' => 'nullable|string|max:255',
            'second_tooth_service' => 'nullable|string|max:255',
            'third_tooth_service' => 'nullable|string|max:255',
            'fourth_tooth_service' => 'nullable|string|max:255',
        ]);
    
        // 🔥 Representative Fix (MATCH STORE)
        $representativeType = $request->representative_type;
        if ($representativeType === 'others') {
            $representativeType = $request->representative_other_type;
        }
    
        // 🔥 Incapacitated Logic (MATCH STORE)
        $memberIncapacitated = $request->member_incapacitated_option === 'yes'
            ? 'Yes'
            : $request->member_incapacitated_reason;
    
        // 🔥 Referral Logic (SAFE - MATCH STORE)
        $isReferred = (int) $request->is_referred;
    
        $dependent->update([
            'philhealth_number' => $request->philhealth_number,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'name_extension' => $request->name_extension,
            'birthdate' => $request->birthdate,
            'date_admitted' => $request->date_admitted,
            'date_discharged' => $request->date_discharged,
            'oral_examination' => $request->oral_examination,
            'institution_fees' => $request->institution_fees,
            'mandatory_discount' => $request->mandatory_discount,
            'philhealth_benefit' => $request->philhealth_benefit,
            'diagnosis' => $request->diagnosis,
            'final_diagnosis' => $request->final_diagnosis,
            'procedure' => $request->procedure,
            'relationship' => $request->relationship,
            'sex' => $request->sex,
    
            // Representative
            'representative_type' => $representativeType,
            'representative' => $request->representative,
    
            // Incapacitated
            'member_incapacitated' => $memberIncapacitated,
    
            // Referral
            'is_referred' => $isReferred,
            'hci_name' => $isReferred ? $request->hci_name : null,
            'pan_referring_hci' => $isReferred ? $request->pan_referring_hci : null,
            'date_of_referral' => $isReferred ? $request->date_of_referral : null,
            'referral_transaction_code' => $isReferred ? $request->referral_transaction_code : null,
            'institution_name' => $isReferred ? $request->institution_name : null,
            'type_institution' => $isReferred ? $request->type_institution : null,
    
            // Teeth
            'first_tooth' => $request->first_tooth,
            'second_tooth' => $request->second_tooth,
            'third_tooth' => $request->third_tooth,
            'fourth_tooth' => $request->fourth_tooth,
    
            // Tooth Services
            'first_tooth_service' => $request->first_tooth_service,
            'second_tooth_service' => $request->second_tooth_service,
            'third_tooth_service' => $request->third_tooth_service,
            'fourth_tooth_service' => $request->fourth_tooth_service,
        ]);
    
        return redirect()->back()->with('success', 'Dependent updated successfully');
    }
    /**
     * Delete Dependent
     */
    public function destroyDependent($id)
    {
        $dependent = CSFDependent::findOrFail($id);
        $dependent->delete();

        return redirect()->back()->with('success', 'Dependent deleted successfully');
    }
}
