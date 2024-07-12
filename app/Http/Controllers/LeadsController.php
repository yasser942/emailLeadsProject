<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator; // Add this line

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
        return view('leads.index', compact('leads'));
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

    $lead = new Lead();
    $lead->name = $validatedData['name'];
    $lead->email = $validatedData['email'];
    $lead->save();

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

   

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        // Import data but don’t save to the database yet
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