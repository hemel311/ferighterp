<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FreightController extends Controller
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
            'feright.dashboard',
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
}
