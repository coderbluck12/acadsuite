<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PlatformSettingController extends Controller
{
    public function index()
    {
        $fee_percentage = PlatformSetting::get('platform_fee_percentage', 0);
        return view('superadmin.settings.index', compact('fee_percentage'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'platform_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        PlatformSetting::set('platform_fee_percentage', $request->platform_fee_percentage);

        return back()->with('success', 'Platform settings updated successfully.');
    }
}
