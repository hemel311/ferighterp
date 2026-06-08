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
        $shipments = Shipment::where('status', 'Submitted')->get();

        return view(
            'feright.container.addcontainer',
            compact('shipments')
        );
    }
    public function extractOcr(
        Request $request,
        GoogleVisionService $googleVision
    )
    {
        $request->validate([
            'container_image' => 'nullable|image',
            'seal_image'      => 'nullable|image',
        ]);

        try {

            $containerNumber = null;
            $sealNumber = null;

            if($request->hasFile('container_image'))
            {
                $containerNumber =
                    $googleVision->extractContainerNumber(
                        $request->file('container_image')
                            ->getRealPath()
                    );
            }

            if($request->hasFile('seal_image'))
            {
                $sealNumber =
                    $googleVision->extractSealNumber(
                        $request->file('seal_image')
                            ->getRealPath()
                    );
            }

            return response()->json([
                'success'          => true,
                'container_number' => $containerNumber,
                'seal_number'      => $sealNumber
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
            'container_images.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'seal_images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $status = $request->action === 'submit'
            ? 'submitted'
            : 'draft';

        foreach ($request->container_serial as $key => $serial)
        {
            $containerPath = null;
            $sealPath = null;

            /*
            |--------------------------------------------------------------------------
            | Container Image Upload
            |--------------------------------------------------------------------------
            */

            if (
                isset($request->container_images[$key]) &&
                $request->container_images[$key] != null
            )
            {
                $containerImage =
                    $request->container_images[$key];

                $containerName =
                    time().'_container_'.$key.'_'.uniqid().'.'.
                    $containerImage->extension();

                $containerImage->move(
                    public_path('uploads/container'),
                    $containerName
                );

                $containerPath =
                    'uploads/container/'.$containerName;
            }

            /*
            |--------------------------------------------------------------------------
            | Seal Image Upload
            |--------------------------------------------------------------------------
            */

            if (
                isset($request->seal_images[$key]) &&
                $request->seal_images[$key] != null
            )
            {
                $sealImage =
                    $request->seal_images[$key];

                $sealName =
                    time().'_seal_'.$key.'_'.uniqid().'.'.
                    $sealImage->extension();

                $sealImage->move(
                    public_path('uploads/seal'),
                    $sealName
                );

                $sealPath =
                    'uploads/seal/'.$sealName;
            }

            /*
            |--------------------------------------------------------------------------
            | Save / Update Record
            |--------------------------------------------------------------------------
            */

            ContainerUpload::updateOrCreate(

                [
                    'booking_number'   => $request->booking_number,
                    'container_serial' => $serial,
                ],

                [
                    'container_number' =>
                        $request->container_number[$key] ?? null,

                    'seal_number' =>
                        $request->seal_number[$key] ?? null,

                    'container_image' =>
                        $containerPath,

                    'seal_image' =>
                        $sealPath,

                    'status' =>
                        $status,
                ]
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                $status == 'draft'
                    ? 'Draft saved successfully.'
                    : 'Container information submitted successfully.'
            );
    }
    public function manage()
    {
        $shipments = Shipment::select('booking_number')
            ->distinct()
            ->get();

        return view(
            'feright.container.manage',
            compact('shipments')
        );
    }
    public function search(Request $request)
    {
        $containers = ContainerUpload::where(
            'booking_number',
            $request->booking_number
        )->get();

        return response()->json($containers);
    }
    public function edit($id)
    {
        $container = ContainerUpload::findOrFail($id);

        return view(
            'feright.container.edit',
            compact('container')
        );
    }
    public function update(
        Request $request,
        $id,
        GoogleVisionService $googleVision
    )
    {
        $container = ContainerUpload::findOrFail($id);

        $request->validate([
            'container_number' => 'nullable|string|max:255',
            'seal_number'      => 'nullable|string|max:255',
            'container_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'seal_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $status = $request->action === 'submit'
            ? 'submitted'
            : 'draft';

        /*
        |--------------------------------------------------------------------------
        | Replace Container Image + OCR
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('container_image'))
        {
            if (
                !empty($container->container_image) &&
                file_exists(public_path($container->container_image))
            )
            {
                unlink(public_path($container->container_image));
            }

            $containerImage = $request->file('container_image');

            $containerName =
                time().'_container_'.uniqid().'.'.
                $containerImage->extension();

            $containerImage->move(
                public_path('uploads/container'),
                $containerName
            );

            $containerPath =
                'uploads/container/'.$containerName;

            $container->container_image = $containerPath;

            try {

                $ocrContainerNumber =
                    $googleVision->extractContainerNumber(
                        public_path($containerPath)
                    );

                if(!empty($ocrContainerNumber))
                {
                    $container->container_number =
                        $ocrContainerNumber;
                }

            } catch (\Exception $e) {

                \Log::error(
                    'Container OCR Error: '.$e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Replace Seal Image + OCR
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('seal_image'))
        {
            if (
                !empty($container->seal_image) &&
                file_exists(public_path($container->seal_image))
            )
            {
                unlink(public_path($container->seal_image));
            }

            $sealImage = $request->file('seal_image');

            $sealName =
                time().'_seal_'.uniqid().'.'.
                $sealImage->extension();

            $sealImage->move(
                public_path('uploads/seal'),
                $sealName
            );

            $sealPath =
                'uploads/seal/'.$sealName;

            $container->seal_image = $sealPath;

            try {

                $ocrSealNumber =
                    $googleVision->extractSealNumber(
                        public_path($sealPath)
                    );

                if(!empty($ocrSealNumber))
                {
                    $container->seal_number =
                        $ocrSealNumber;
                }

            } catch (\Exception $e) {

                \Log::error(
                    'Seal OCR Error: '.$e->getMessage()
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Manual Override
        |--------------------------------------------------------------------------
        */

        if($request->filled('container_number'))
        {
            $container->container_number =
                $request->container_number;
        }

        if($request->filled('seal_number'))
        {
            $container->seal_number =
                $request->seal_number;
        }

        $container->status = $status;

        $container->save();

        return redirect()
            ->route('container.manage')
            ->with(
                'success',
                $status == 'draft'
                    ? 'Draft updated successfully.'
                    : 'Container submitted successfully.'
            );
    }
    public function delete($id)
    {
        $container = ContainerUpload::findOrFail($id);

        // Delete Container Image
        if(
            !empty($container->container_image) &&
            file_exists(public_path($container->container_image))
        )
        {
            unlink(public_path($container->container_image));
        }

        // Delete Seal Image
        if(
            !empty($container->seal_image) &&
            file_exists(public_path($container->seal_image))
        )
        {
            unlink(public_path($container->seal_image));
        }

        $container->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Container Deleted Successfully'
            );
    }

}
