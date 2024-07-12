<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\EmailTemplate;

class DashboardController extends Controller
{
    public function index()
    {
        $leadsCount = Lead::count();
        $emailTemplatesCount = EmailTemplate::count();

        return view('dashboard', compact('leadsCount', 'emailTemplatesCount'));
    }
}