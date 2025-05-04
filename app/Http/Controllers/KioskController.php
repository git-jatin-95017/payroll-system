<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KioskController extends Controller
{
    public function login()
    {
        return view('kiosk.login');
    }

    public function processLogin(Request $request)
    {
        // Validate the company name
        $request->validate([
            'company_name' => 'required|string|max:255'
        ]);

        // Store the company name in session for next steps
        session(['kiosk_company_name' => $request->company_name]);
        
        // Redirect to the next step
        return redirect()->route('kiosk.step2');
    }
} 