<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function events()
    {
        $shipments = Shipment::all();

        $events = [];

        foreach ($shipments as $shipment) {

            // Accountant only sees CY Cut Off
            if(auth()->guard('accountant'))
            {
                if($shipment->si_cut_off)
                {
                    $events[] = [
                        'title' => 'SI - '.$shipment->booking_number,
                        'start' => \Carbon\Carbon::parse($shipment->si_cut_off)->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => '#12e338',
                        'borderColor' => '#12e338',
                        'textColor' => '#000000',
                    ];
                }
            }

            if($shipment->cy_cut_off)
            {
                $events[] = [
                    'title' => 'CY - '.$shipment->booking_number,
                    'start' => \Carbon\Carbon::parse($shipment->cy_cut_off)->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => '#e31220',
                    'borderColor' => '#e31220',
                    'textColor' => '#fffff',
                ];
            }

            if($shipment->etd)
            {
                $events[] = [
                    'title' => 'ETD - '.$shipment->booking_number,
                    'start' => $shipment->etd,
                    'color' => '#0d6efd',
                ];
            }

            if($shipment->eta)
            {
                $events[] = [
                    'title' => 'ETA - '.$shipment->booking_number,
                    'start' => $shipment->eta,
                    'color' => '#198754',
                ];
            }

        }

        return response()->json($events);
    }
}
