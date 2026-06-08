<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    public function index()
    {
        $totalShipments = Shipment::count();

        $draftShipments = Shipment::where(
            'status',
            'Draft'
        )->count();

        $submittedShipments = Shipment::where(
            'status',
            'Submitted'
        )->count();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $todayShipments = Shipment::whereDate('si_cut_off', $today)
            ->orWhereDate('cy_cut_off', $today)
            ->orWhereDate('etd', $today)
            ->orWhereDate('eta', $today)
            ->get();

        $tomorrowShipments = Shipment::whereDate('si_cut_off', $tomorrow)
            ->orWhereDate('cy_cut_off', $tomorrow)
            ->orWhereDate('etd', $tomorrow)
            ->orWhereDate('eta', $tomorrow)
            ->get();
        $todayActivities = $todayShipments->count();

        return view(
            'admin.dashboard',
            compact(
                'todayShipments',
                'tomorrowShipments',
                'totalShipments',
                'draftShipments',
                'submittedShipments',
                'todayActivities'

            )
        );
    }
    public function changePassword()
    {
        return view('admin.password.change');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403);
        }

        if (!Hash::check(
            $request->current_password,
            $admin->password
        )) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with(
            'success',
            'Password changed successfully.'
        );
    }

}
