<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function create(Request $request) {
        $settings = Setting::find(1);
        return view('admin.settings.create', compact('settings'));
    }

    public function updateSettings(Request $request) {
        $settings = Setting::find(1);
        $settings->update($request->all());

        return back()->with('message', 'Settings updated successfully.');
    }
}
