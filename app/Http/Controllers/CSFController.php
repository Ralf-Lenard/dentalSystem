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

     public function store(Request $request)
     {
         $request->validate([
             'philhealth_number' => 'required|unique:csf,philhealth_number',
             'last_name' => 'required|string|max:255',
             'first_name' => 'required|string|max:255',
             'middle_name' => 'nullable|string|max:255',
             'name_extension' => 'nullable|string|max:255',
             'birthdate' => 'required|date',
             'date_admitted' => 'nullable|date',
             'date_discharged' => 'nullable|date|after_or_equal:date_admitted',
     
             'oral_examination' => 'nullable|array',
             'institution_fees' => 'required|numeric|min:0',
             'diagnosis' => 'nullable|string|max:255',
     
             // ✅ Dependents
             'dependents' => 'nullable|array',
             'dependents.*.philhealth_number' => 'required_with:dependents|string',
             'dependents.*.last_name' => 'required_with:dependents|string|max:255',
             'dependents.*.first_name' => 'required_with:dependents|string|max:255',
             'dependents.*.middle_name' => 'nullable|string|max:255',
             'dependents.*.name_extension' => 'nullable|string|max:255',
             'dependents.*.birthdate' => 'required_with:dependents|date',
             'dependents.*.date_admitted' => 'required_with:dependents|date',
             'dependents.*.date_discharged' => 'required_with:dependents|date|after_or_equal:dependents.*.date_admitted',
             'dependents.*.relationship' => 'required_with:dependents|in:child,spouse,parent',
             'dependents.*.diagnosis' => 'nullable|string|max:255',
     
             // ✅ Representative
             'dependents.*.representative_type' => 'nullable|string|max:255',
             'dependents.*.representative_other_type' => 'nullable|string|max:255',
             'dependents.*.representative' => 'nullable|string|max:255',
     
             // ✅ Member incapacitated logic
             'member_incapacitated_option' => 'nullable|in:yes,other',
             'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
         ]);
     
         // Determine what to store in one column
         if ($request->member_incapacitated_option === 'yes') {
             $memberIncapacitated = 'Yes';
         } else {
             $memberIncapacitated = $request->member_incapacitated_reason;
         }
     
         // ✅ Create CSF
         $csf = CSF::create([
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
             'diagnosis' => $request->diagnosis,
            //  'member_incapacitated' => $memberIncapacitated, // ✅ Save in one column
         ]);
     
         // ✅ Save Dependents
         if ($request->has('dependents') && is_array($request->dependents)) {
     
             foreach ($request->dependents as $dependent) {
     
                 // 🔥 If "others" selected → replace with actual typed value
                 $representativeType = $dependent['representative_type'] ?? null;
     
                 if ($representativeType === 'others') {
                     $representativeType = $dependent['representative_other_type'] ?? null;
                 }
     
                 $csf->dependents()->create([
                     'philhealth_number' => $dependent['philhealth_number'] ?? null,
                     'last_name' => $dependent['last_name'] ?? null,
                     'first_name' => $dependent['first_name'] ?? null,
                     'middle_name' => $dependent['middle_name'] ?? null,
                     'name_extension' => $dependent['name_extension'] ?? null,
                     'birthdate' => $dependent['birthdate'] ?? null,
                     'date_admitted' => $dependent['date_admitted'] ?? null,
                     'date_discharged' => $dependent['date_discharged'] ?? null,
                     'relationship' => $dependent['relationship'] ?? null,
                     'diagnosis' => $dependent['diagnosis'] ?? null,
     
                     // ✅ Final cleaned value
                     'representative_type' => $representativeType,
     
                     'representative' => $dependent['representative'] ?? null,
                 ]);
             }
         }
     
         return redirect()->back()->with('success', 'CSF Member created successfully');

     }
     
    
    /**
     * Update CSF member and optionally dependents
     */
    public function update(Request $request, $id)
    {
        $csf = CSF::findOrFail($id);
    
        $request->validate([
            'philhealth_number' => 'required|unique:csf,philhealth_number,' . $csf->id,
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'date_admitted' => 'nullable|date',
    
            // ✅ NEW FIELDS
            'date_discharged' =>'nullable|date',
            'oral_examination' => 'nullable|array',
            'institution_fees' => 'required|integer|min:0',
            'diagnosis' => 'nullable|string|max:255',
        ]);
    
        $csf->update([
            'philhealth_number' => $request->philhealth_number,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'name_extension' => $request->name_extension,
            'birthdate' => $request->birthdate,
            'date_admitted' => $request->date_admitted,
    
            // ✅ NEW
            'date_discharged' => $request->date_discharged,
            'oral_examination' => $request->oral_examination,
            'institution_fees' => $request->institution_fees,
            'diagnosis' => $request->diagnosis,
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
    
        $request->validate([
            'philhealth_number' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'date_admitted' => 'required|date',
            'date_discharged' => 'required|date|after_or_equal:date_admitted',
    
            'oral_examination' => 'nullable|array',
            'institution_fees' => 'required|numeric|min:0',
    
            'relationship' => 'required|in:child,spouse,parent',
            'diagnosis' => 'nullable|string|max:255',
    
            // ✅ Representative
            'representative_type' => 'nullable|string|max:255',
            'representative_other_type' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:255',
    
            // ✅ Dependent incapacitated logic
            'member_incapacitated_option' => 'required|in:yes,other',
            'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
        ]);
    
        // 🔥 Replace "others" with actual typed value
        $representativeType = $request->representative_type;
        if ($representativeType === 'others') {
            $representativeType = $request->representative_other_type;
        }
    
        // ✅ Determine what to store in one column for incapacitation
        $memberIncapacitated = $request->member_incapacitated_option === 'yes'
            ? 'Yes'
            : $request->member_incapacitated_reason;
    
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
            'diagnosis' => $request->diagnosis,
            'relationship' => $request->relationship,
    
            // ✅ Final cleaned value (no "others")
            'representative_type' => $representativeType,
            'representative' => $request->representative,
    
            // ✅ Save incapacitation info in one column
            'member_incapacitated' => $memberIncapacitated,
        ]);
    
        return redirect()->back()->with('success', 'Dependent added successfully');
    }
    
    
    /**
     * Update Dependent
     */
    public function updateDependent(Request $request, $id)
    {
        $dependent = CSFDependent::findOrFail($id);
    
        $request->validate([
            'philhealth_number' => 'required|string',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'date_admitted' => 'required|date',
            'date_discharged' => 'required|date|after_or_equal:date_admitted',
    
            'oral_examination' => 'nullable|array',
            'institution_fees' => 'required|numeric|min:0',
    
            'relationship' => 'required|in:child,spouse,parent',
            'diagnosis' => 'nullable|string|max:255',
    
            // ✅ Representative
            'representative_type' => 'nullable|string|max:255',
            'representative_other_type' => 'nullable|string|max:255',
            'representative' => 'nullable|string|max:255',
    
            // ✅ Dependent incapacitated logic
            'member_incapacitated_option' => 'required|in:yes,other',
            'member_incapacitated_reason' => 'nullable|string|required_if:member_incapacitated_option,other',
        ]);
    
        // 🔥 Replace "others" with actual typed value
        $representativeType = $request->representative_type;
        if ($representativeType === 'others') {
            $representativeType = $request->representative_other_type;
        }
    
        // ✅ Determine what to store in one column for incapacitation
        $memberIncapacitated = $request->member_incapacitated_option === 'yes'
            ? 'Yes'
            : $request->member_incapacitated_reason;
    
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
            'relationship' => $request->relationship,
            'diagnosis' => $request->diagnosis,
    
            // ✅ Final cleaned value
            'representative_type' => $representativeType,
            'representative' => $request->representative,
    
            // ✅ Save incapacitation info in one column
            'member_incapacitated' => $memberIncapacitated,
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
