<?php

namespace App\Http\Controllers\freight;

use App\Http\Controllers\Controller;
use App\Models\ContainerUpload;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\GoogleVisionService;

class ContainerController extends Controller
{
    public function index()
    {
        $shipments = Shipment::all();

        return view(
            'feright.container.addcontainer',
            compact('shipments')
        );
    }
    public function extractOcr(Request $request, GoogleVisionService $googleVision
    )
    {
        $request->validate([
            'container_image' => 'required|image',
            'seal_image'      => 'required|image',
        ]);

        try {

            $containerNumber = $googleVision->extractContainerNumber(
                $request->file('container_image')->getRealPath()
            );

            $sealNumber = $googleVision->extractSealNumber(
                $request->file('seal_image')->getRealPath()
            );

            return response()->json([
                'success' => true,
                'container_number' => $containerNumber,
                'seal_number' => $sealNumber
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Final Save
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_number'      => 'required',
            'container_images.*'  => 'required|image',
            'seal_images.*'       => 'required|image',
        ]);

        foreach ($request->container_images as $key => $containerImage)
        {
            /*
            |--------------------------------------------------------------------------
            | Upload Container Image
            |--------------------------------------------------------------------------
            */

            $containerName =
                time().'_container_'.$key.'.'.$containerImage->extension();

            $containerImage->move(
                public_path('uploads/container'),
                $containerName
            );

            /*
            |--------------------------------------------------------------------------
            | Upload Seal Image
            |--------------------------------------------------------------------------
            */

            $sealImage = $request->seal_images[$key];

            $sealName =
                time().'_seal_'.$key.'.'.$sealImage->extension();

            $sealImage->move(
                public_path('uploads/seal'),
                $sealName
            );

            /*
            |--------------------------------------------------------------------------
            | Save Database
            |--------------------------------------------------------------------------
            */

            ContainerUpload::create([

                'booking_number'   => $request->booking_number,

                'container_serial' =>
                    $request->container_serial[$key],

                'container_number' =>
                    $request->container_number[$key] ?? null,

                'seal_number' =>
                    $request->seal_number[$key] ?? null,

                'container_image' =>
                    'uploads/container/'.$containerName,

                'seal_image' =>
                    'uploads/seal/'.$sealName,
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Container Information Saved Successfully'
            );
    }
}
