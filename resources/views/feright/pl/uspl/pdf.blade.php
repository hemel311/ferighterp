<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: DejaVu Sans;
            font-size:12px;
        }

        .outer{
            border:2px solid #000;
            padding:8px;
        }

        .title{
            text-align:center;
            font-size:26px;
            font-weight:bold;
            background:#d8dee7;
            border:1px solid #000;
            padding:5px;
        }

        .container-title{
            text-align:center;
            font-size:22px;
            font-weight:bold;
            border-left:1px solid #000;
            border-right:1px solid #000;
            border-bottom:1px solid #000;
            padding:4px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:4px;
        }

        .header{
            background:#d8dee7;
            text-align:center;
            font-weight:bold;
        }

        .summary-label{
            font-weight:bold;
        }

        .text-right{
            text-align:right;
        }

        .text-center{
            text-align:center;
        }

    </style>
</head>
<body>

<div class="outer">

    <div class="title">
        Packing List
    </div>

    <div class="container-title">
        CONTAINER NUMBER
        {{ $packingList->container->container_number }}
    </div>

    <table>

        <thead>

        <tr class="header">

            <th>Total Pallets</th>
            <th>Packages</th>
            <th>Quantity item per Pallet/Pack</th>
            <th>Product Name</th>
            <th>TOTAL item Quantity</th>
            <th>PALET / Pack KG</th>
            <th>TOTAL KG</th>
            <th>GROSS WEIGHT</th>
            <th>Warehouse code</th>

        </tr>

        </thead>

        <tbody>

        @foreach($packingList->products as $item)

            <tr>

                <td class="text-center">
                    {{ $item->total_pallets }}
                </td>

                <td class="text-center">
                    {{ $item->packages }}
                </td>

                <td class="text-center">
                    {{ $item->qty_per_pallet }}
                </td>

                <td>
                    {{ $item->product_name }}
                </td>

                <td class="text-center">
                    {{ $item->total_item_qty }}
                </td>

                <td class="text-right">
                    {{ number_format($item->pallet_pack_kg,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->total_kg,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->gross_weight,2) }}
                </td>

                <td>
                    {{ $item->warehouse_code }}
                </td>

            </tr>

        @endforeach

        {{-- Empty Rows --}}
        @for($i = $packingList->products->count(); $i < 10; $i++)

            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        @endfor

        </tbody>

    </table>

    <br>
    @php

        $totalNetWeight =
            $packingList->products->sum('total_kg');

        $totalGrossWeight =
            $packingList->products->sum('gross_weight');

        $totalPallets =
            $packingList->products->sum('total_pallets');

        $totalPackages =
            $packingList->products->sum('packages');

        $totalPieces =
            $packingList->products->sum('total_item_qty');

    @endphp

    <table style="width:100%; border-collapse:collapse; margin-top:10px;">

        <tr>

            <td colspan="4"
                style="border:1px solid #000;
                   font-size:18px;
                   font-weight:bold;
                   text-align:center;">

                CONTAINER NUMBER
                {{ $packingList->container->container_number }}

            </td>

            <td style="border:1px solid #000;">
                Net weight
            </td>

            <td style="border:1px solid #000;">
                {{ number_format($totalNetWeight,1) }}
                kg
            </td>

        </tr>

        <tr>

            <td style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                gross weight kg
            </td>

            <td style="border:1px solid #000;">
                net weight kg
            </td>

            <td style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                Gross weight
            </td>

            <td style="border:1px solid #000;">
                {{ number_format($totalGrossWeight,1) }}
                kg
            </td>

        </tr>

        <tr>

            <td style="border:1px solid #000;"></td>

            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($totalGrossWeight,0) }}
            </td>

            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($totalNetWeight,0) }}
            </td>

            <td style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                Total palets
            </td>

            <td style="border:1px solid #000;">
                {{ $totalPallets }}
                palets
            </td>

        </tr>

        <tr>

            <td colspan="4"
                style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                Total Volume
            </td>

            <td style="border:1px solid #000;">
                60 m3
            </td>

        </tr>

        <tr>

            <td colspan="4"
                style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                Total Package
            </td>

            <td style="border:1px solid #000;">
                {{ $totalPackages }}
                Packages
            </td>

        </tr>

        <tr>

            <td colspan="4"
                style="border:1px solid #000;"></td>

            <td style="border:1px solid #000;">
                Total pieces
            </td>

            <td style="border:1px solid #000;">
                {{ $totalPieces }}
                pieces
            </td>

        </tr>

    </table>



</div>

</body>
</html>