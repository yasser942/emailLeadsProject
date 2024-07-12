<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use App\Jobs\SendEmailToLead;
use App\Models\EmailTemplate;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::paginate(10);
        $emailTemplates = EmailTemplate::all();

        if ($emailTemplates->isEmpty()) {
            return redirect()->route('email-templates.create')->with('error', 'No email template found. Please create one.');
        }

        return view('leads.index', compact('leads', 'emailTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:leads',
        ]);

        // Check if there is an email template
        $emailTemplate = EmailTemplate::first();
        if (!$emailTemplate) {
            return redirect()->route('email-templates.create')->with('error', 'No email template found. Please create one.');
        }

        $lead = new Lead();
        $lead->name = $validatedData['name'];
        $lead->email = $validatedData['email'];
        $lead->save();

        

        // Dispatch the SendEmailToLead job
        SendEmailToLead::dispatch($lead, $emailTemplate);

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.edit', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:leads,email,' . $id,
    ]);

    $lead = Lead::findOrFail($id);
    $lead->name = $validatedData['name'];
    $lead->email = $validatedData['email'];
    $lead->save();

    return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $lead = Lead::findOrFail($id);
    $lead->delete();

    return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }

    public function sendEmails(Request $request)
    {
        $request->validate([
            'email_template' => 'required|exists:email_templates,id',
        ]);

        $emailTemplate = EmailTemplate::findOrFail($request->email_template);
        $leads = Lead::all();

        foreach ($leads as $lead) {
            SendEmailToLead::dispatch($lead, $emailTemplate);
        }

        return redirect()->route('leads.index')->with('success', 'Emails are being sent to all leads.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        // Import data but don't save to the database yet
        $import = new LeadsImport;
        $rows = Excel::toArray($import, $request->file('file'));

        foreach ($rows[0] as $row) {
            if (!isset($row['email'])) {
                return redirect()->back()->with('error', 'Invalid data format.');
            }

            $existingLead = Lead::where('email', $row['email'])->first();
            if ($existingLead) {
                return redirect()->back()->with('error', 'Email ' . $row['email'] . ' already exists.');
            }

            $validator = Validator::make([
                'name' => $row['name'],
                'email' => $row['email']
            ], [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:leads,email'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        // Import the data into the database
        Excel::import($import, $request->file('file'));

        return redirect()->back()->with('success', 'Leads Imported Successfully');
    }
}