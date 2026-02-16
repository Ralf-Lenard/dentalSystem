<?php

namespace App\Http\Controllers;

use App\Models\CSF;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon; // make sure you have this at the top
use Illuminate\Http\Response;

class CSFPrintController extends Controller
{
    /**
     * Stream PDF for member only (folio size, no margins)
     */
   

     public function memberPdf($id)
     {
         $csf = CSF::findOrFail($id);
     
         // Check if date_admitted exists
         if (!$csf->date_admitted) {
             // Option 1: Return an error response
             return redirect()->back()->withErrors([
                'date_admitted' => 'Date admitted is missing for this member.'
            ]);
            
     
             // Option 2: Or throw an exception instead
             // throw new \Exception('Date admitted is missing for this member.');
         }
     
         // Get today's date as Carbon object
         $today = Carbon::now();
     
         // Render the HTML
         $html = View::make('member_pdf', [
             'csf' => $csf,
             'today' => $today,
         ])->render();
     
         // Generate the PDF content directly (without saving to file)
         $pdfContent = Browsershot::html($html)
             ->ignoreHttpsErrors()
             ->format('LEGAL')        // Set Legal/Folio page size
             ->showBackground()
             ->pdf();                 // Returns PDF content as string
     
         // Return PDF inline in browser
         return response($pdfContent, 200)
             ->header('Content-Type', 'application/pdf')
             ->header('Content-Disposition', "inline; filename=CSF_MEMBER_{$csf->philhealth_number}.pdf");
     }
     

    /**
     * Stream PDF for member + dependents (folio size, no margins)
     */
    public function dependentsPdf($id, $dependent_id = null)
    {
        // ✅ Load CSF with dependents
        $csf = CSF::with('dependents')->findOrFail($id);
    
        $today = Carbon::now();
    
        // ✅ Pick a specific dependent safely
        $dependent = null;
        if ($dependent_id) {
            $dependent = $csf->dependents->firstWhere('id', $dependent_id);
        }
    
        // Default to the first dependent if none specified or not found
        if (!$dependent) {
            $dependent = $csf->dependents->first();
        }
    
        // ✅ Render HTML
        $html = View::make('dependents_pdf', [
            'csf' => $csf,
            'dependent' => $dependent, // pass single dependent
            'today' => $today,
        ])->render();
    
        // ✅ Generate PDF
        $pdfContent = Browsershot::html($html)
            ->ignoreHttpsErrors()
            ->format('LEGAL')        // Legal/Folio page size
            ->showBackground()
            ->pdf();   
    
        // ✅ Return PDF response
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=CSF_DEPENDENT_{$csf->philhealth_number}.pdf");
    }
    
    

}
