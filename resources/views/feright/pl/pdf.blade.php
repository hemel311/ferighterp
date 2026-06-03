<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        @page{
            margin:0;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            margin:0;
            padding:0;
        }

        table{
            border-collapse:collapse;
        }

        .master-table{
            width:100%;
        }

        .master-table td,
        .master-table th{
            border:1px solid #000;
            padding:2px;
            vertical-align:middle;
        }

        .title{
            font-size:22px;
            font-weight:bold;
            text-align:center;
        }

        .container-title{
            font-size:18px;
            font-weight:bold;
            text-align:center;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .bold{
            font-weight:bold;
        }

        .small{
            font-size:10px;
        }

        .empty-row{
            height:18px;
        }

    </style>
</head>
<body>

<table class="master-table">

    <tr>
        <td rowspan="2" width="20%"></td>

        <td colspan="8" class="title">
            Packing List
        </td>
    </tr>

    <tr>
        <td colspan="8" class="container-title">
            CONTAINER NUMBER {{ $packingList->container->container_number }}
        </td>
    </tr>
    <tr>

        <td rowspan="15" valign="top">

            <strong>To:</strong><br>

           {{$packingList->to_location}}

        </td>

        <th width="5%">
            Total<br>Palets
        </th>

        <th width="5%">
            Packages
        </th>

        <th width="10%">
            Quantity Item Per<br>
            Palet/Pack & KG
        </th>

        <th width="32%">
            TYPE
        </th>

        <th width="10%">
            TOTAL Item<br>
            Quantity/KG
        </th>

        <th width="9%">
            PALET /<br>
            Pack KG
        </th>

        <th width="9%">
            TOTAL KG
        </th>

        <th width="10%">
            GROSS<br>
            WEIGHT
        </th>

    </tr>
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

            <td class="text-right">
                {{ $item->item_quantity }}
            </td>

            <td class="text-right">

                @php

                    $palletPackKg = 0;

                    if($item->total_pallets > 0)
                    {
                        $palletPackKg =
                            $item->net_weight /
                            $item->total_pallets;
                    }
                    elseif($item->total_packages > 0)
                    {
                        $palletPackKg =
                            $item->net_weight /
                            $item->total_packages;
                    }

                @endphp

                {{ number_format($palletPackKg,2) }}

            </td>

            <td class="text-right">
                {{ number_format($item->net_weight,2) }}
            </td>

            <td class="text-right">
                {{ number_format($item->gross_weight,2) }}
            </td>

        </tr>

@endforeach
    @for($i=0;$i<12;$i++)

        <tr class="empty-row">

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
    <tr>

        <td valign="top" style="height:120px">

            <strong>From:</strong><br>

            {{$packingList->from_location}}

        </td>

        <td colspan="8"></td>

    </tr>

    <tr>

        <td>
            INVOICE DATE:
            {{ \Carbon\Carbon::parse($packingList->pl_date)->format('d.m.Y') }}
        </td>

        <td colspan="8"></td>

    </tr>

    <tr>

        <td>
            C.I.F. ISTANBUL
        </td>

        <td colspan="8"></td>

    </tr>

    <tr>

        <td>
            CASH IN ADVANCE
        </td>

        <td colspan="8"></td>

    </tr>

    <tr>

        <td>
            INVOICE NUMBER:
        </td>

        <td colspan="8"></td>

    </tr>

    <tr>

        <td style="height:70px" valign="top">
            Notes:
        </td>

        <td colspan="8"></td>

    </tr>
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
    <tr>

        <td colspan="5"
            class="container-title">

            CONTAINER NUMBER
            {{ $packingList->container->container_number }}

        </td>

        <td colspan="2">
            Net weight
        </td>

        <td colspan="2">
            {{ number_format($totalNetWeight,2) }} kg
        </td>

    </tr>

    <tr>

        <td colspan="3">
            gross weight kg
        </td>

        <td colspan="2">
            net weight kg
        </td>

        <td colspan="2">
            Gross weight
        </td>

        <td colspan="2">
            {{ number_format($totalGrossWeight,2) }} kg
        </td>

    </tr>

    <tr>

        <td colspan="3"
            class="text-right">

            {{ number_format($totalGrossWeight,2) }}

        </td>

        <td colspan="2"
            class="text-right">

            {{ number_format($totalNetWeight,2) }}

        </td>

        <td colspan="2">
            Total pallets
        </td>

        <td colspan="2">
            {{ $totalPallets }} pallets
        </td>

    </tr>

    <tr>

        <td colspan="5"></td>

        <td colspan="2">
            Total Volume
        </td>

        <td colspan="2">
            60 m3
        </td>

    </tr>

    <tr>

        <td colspan="5"></td>

        <td colspan="2">
            Total Package
        </td>

        <td colspan="2">
            {{ $totalPackages }} Packages
        </td>

    </tr>

    <tr>

        <td colspan="5"></td>

        <td colspan="2">
            Total pieces
        </td>

        <td colspan="2">
            {{ $totalPieces }} pieces
        </td>

    </tr>
</table>
</body>
</html>