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
        .invoice{
            background-color: #d8dee7;
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
        @php
            $emptyRows = max(0, 12 - $packingList->items->count());
            $rowspan = 1 + $packingList->items->count() + $emptyRows;
        @endphp
        <tr class="header">

            <td rowspan="{{ $rowspan }}"
                width="20%"
                style="padding:0; vertical-align:top;">
                <div style="padding:5px;">

                    <strong>To:</strong><br>

                    {!! nl2br(e($packingList->to_location)) !!}

                    <hr style="margin:10px 0;">

                    <strong>From:</strong><br>

                    {!! nl2br(e($packingList->from_location)) !!}
                </div>

            </td>

            <th width="7%">Total Pallets</th>

            <th width="7%">Packages</th>

            <th width="12%">
                Quantity Item Per
                Pallet/Pack
            </th>

            <th width="35%">
                Product Name
            </th>

            <th width="10%">
                Total Item Quantity
            </th>

            <th width="10%">
                Pallet / Pack KG
            </th>

            <th width="10%">
                Total KG
            </th>

            <th width="9%">
                Gross Weight
            </th>

        </tr>

        </thead>

        <tbody>

        @foreach($packingList->items as $item)

            <tr>

                <td class="text-center">
                    {{ $item->total_pallets }}
                </td>

                <td class="text-center">
                    {{ $item->total_packages }}
                </td>

                <td class="text-center">
                    {{ $item->quantity_per_unit }}
                </td>

                <td>
                    {{ $item->product_name }}
                </td>

                <td class="text-center">
                    {{ $item->item_quantity }}
                </td>

                <td class="text-right">
                    {{ number_format($item->pallet_pack_kg,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->net_weight,2) }}
                </td>

                <td class="text-right">
                    {{ number_format($item->gross_weight,2) }}
                </td>

            </tr>

        @endforeach

        {{-- Empty Rows --}}
        @for($i = $packingList->items->count(); $i < 12; $i++)

            <tr>
                <td>&nbsp;</td>
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
            $packingList->items->sum('net_weight');

        $totalGrossWeight =
            $packingList->items->sum('gross_weight');

        $totalPallets =
            $packingList->items->sum('total_pallets');

        $totalPackages =
            $packingList->items->sum('total_packages');

        $totalPieces =
            $packingList->items->sum('item_quantity');

    @endphp

    <table style="margin-top:5px;">

        <tr>

            <td width="20%" class="invoice">
                INVOICE DATE: {{ \Carbon\Carbon::parse($packingList->pl_date)->format('d.m.Y') }}
            </td>

            <td width="55%" style="padding:0;">

                <table style="width:100%; border-collapse:collapse;">

                    <tr>
                        <td colspan="2"
                            style="
                        text-align:center;
                        font-size:18px;
                        font-weight:bold;
                    ">
                            CONTAINER NUMBER
                            {{ $packingList->container->container_number }}
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">
                            net weight
                        </td>

                        <td style="text-align:center;">
                            gross weight
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">
                            {{ number_format($totalNetWeight,2) }}
                        </td>

                        <td style="text-align:center;">
                            {{ number_format($totalGrossWeight,2) }}
                        </td>
                    </tr>

                </table>

            </td>

            <td width="15%">
                Net weight
            </td>

            <td width="10%">
                {{ number_format($totalNetWeight,2) }} kg
            </td>

        </tr>

            <td class="invoice">
                C.I.F. ISTANBUL
            </td>

            <td></td>

            <td>
                Gross weight
            </td>

            <td>
                {{ number_format($totalGrossWeight,2) }} kg
            </td>

        </tr>

        <tr>

            <td class="invoice">
                CASH IN ADVANCE
            </td>



            <td></td>

            <td>
                Total Pallets
            </td>

            <td>
                {{ $totalPallets }} pallets
            </td>

        </tr>

        <tr>

            <td class="invoice">
                INVOICE NUMBER:
            </td>

            <td></td>

            <td>
                Total Volume
            </td>

            <td>
                60 m3
            </td>

        </tr>

        <tr>

            <td valign="top" class="invoice">
                Notes:
            </td>

            <td></td>

            <td>
                Total Package
            </td>

            <td>
                {{ $totalPackages }} Packages
            </td>

        </tr>

        <tr>

            <td style="height:20px;"></td>

            <td></td>

            <td>
                Total Pieces
            </td>

            <td>
                {{ $totalPieces }} Pieces
            </td>

        </tr>

    </table>





</div>

</body>
</html>