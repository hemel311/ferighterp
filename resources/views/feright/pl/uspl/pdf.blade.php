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

    <table>

        <tr>
            <td colspan="9"
                style="font-size:18px;font-weight:bold;">
                CONTAINER NUMBER
                {{ $packingList->container->container_number }}
            </td>
        </tr>

    </table>

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

    <table>

        <tr>

            <td width="55%"></td>

            <td class="summary-label">
                Net weight
            </td>

            <td>
                {{ number_format($totalNetWeight,2) }}
                kg
            </td>

        </tr>

        <tr>

            <td></td>

            <td class="summary-label">
                Gross weight
            </td>

            <td>
                {{ number_format($totalGrossWeight,2) }}
                kg
            </td>

        </tr>

        <tr>

            <td></td>

            <td class="summary-label">
                Total palets
            </td>

            <td>
                {{ $totalPallets }}
                palets
            </td>

        </tr>

        <tr>

            <td></td>

            <td class="summary-label">
                Total Volume
            </td>

            <td>
                60 m3
            </td>

        </tr>

        <tr>

            <td></td>

            <td class="summary-label">
                Total Package
            </td>

            <td>
                {{ $totalPackages }}
                Packages
            </td>

        </tr>

        <tr>

            <td></td>

            <td class="summary-label">
                Total pieces
            </td>

            <td>
                {{ $totalPieces }}
                pieces
            </td>

        </tr>

    </table>

</div>

</body>
</html>