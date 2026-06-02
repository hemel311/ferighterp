
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        @page{
            margin:10px;
        }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td{
            border:1px solid #000;
            padding:3px;
            vertical-align:middle;
        }

        .title{
            font-size:28px;
            font-weight:bold;
            text-align:center;
        }

        .container-title{
            font-size:20px;
            font-weight:bold;
            text-align:center;
        }

        .company{
            width:170px;
            vertical-align:top;
            height:170px;
        }

        .atlantic{
            width:170px;
            vertical-align:top;
            height:170px;
        }

        .header{
            font-size:10px;
            font-weight:bold;
            text-align:center;
            height:55px;
        }

        .right{
            text-align:right;
        }

        .center{
            text-align:center;
        }

        .product-row{
            height:28px;
        }

        .notes{
            height:130px;
            vertical-align:top;
        }
        td:nth-child(5){
            width:260px;
        }

    </style>

</head>

<body>

<table>

    <tr>

        <td rowspan="8" class="company">

            {{ $packingList->to_location }}

        </td>

        <td colspan="8" class="title">

            Packing List

        </td>

    </tr>

    <tr>

        <td colspan="8" class="container-title">

            CONTAINER NUMBER
            {{ $packingList->container->container_number }}

        </td>

    </tr>

    <tr>

        <td class="header">
            Total Pallets
        </td>

        <td class="header">
            Packages
        </td>

        <td class="header">
            Quantity Item /
            Pallet
        </td>

        <td class="header">
            TYPE
        </td>

        <td class="header">
            TOTAL Item
            Quantity
        </td>

        <td class="header">
            PALET /
            PACK KG
        </td>

        <td class="header">
            TOTAL KG
        </td>

        <td class="header">
            GROSS WEIGHT
        </td>

    </tr>

    @php
        $maxRows = 8;
    @endphp

    @foreach($packingList->items as $item)

        @php

            $qtyPerPallet = '';
            $packKg = '';

            if($item->total_pallets > 0)
            {
                $qtyPerPallet = round(
                    $item->item_quantity / $item->total_pallets,
                    2
                );

                $packKg = round(
                    $item->net_weight / $item->total_pallets,
                    2
                );
            }

        @endphp

        <tr class="product-row">

            <td class="right">{{ $item->total_pallets }}</td>

            <td class="right">{{ $item->total_packages }}</td>

            <td class="right">{{ $qtyPerPallet }}</td>

            <td>{{ $item->product_name }}</td>

            <td class="right">{{ $item->item_quantity }}</td>

            <td class="right">{{ $packKg }}</td>

            <td class="right">{{ $item->net_weight }}</td>

            <td class="right">{{ $item->gross_weight }}</td>

        </tr>

    @endforeach

    @php
        $blankRows = 8 - $packingList->items->count();
    @endphp

    @for($i = 0; $i < $blankRows; $i++)

        <tr class="product-row">

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

    <tr>

        <td class="atlantic">

            {{ $packingList->from_location }}

        </td>

        <td colspan="8"
            style="height:170px;">
        </td>

    </tr>

</table>

<table>

    <tr>

        <td style="width:25%;">
            INVOICE DATE:
            {{ \Carbon\Carbon::parse($packingList->pl_date)->format('d.m.Y') }}
        </td>

        <td colspan="2"
            class="container-title">

            CONTAINER NUMBER
            {{ $packingList->container->container_number }}

        </td>

        <td>
            Net weight
        </td>

        <td>
            {{ number_format($packingList->total_net_weight,2) }} kg
        </td>

    </tr>

    <tr>

        <td>
            C.I.F. ISTANBUL
        </td>

        <td>
            gross weight kg
        </td>

        <td>
            net weight kg
        </td>

        <td>
            Gross weight
        </td>

        <td>
            {{ number_format($packingList->total_gross_weight,2) }} kg
        </td>

    </tr>

    <tr>

        <td>
            CASH IN ADVANCE
        </td>

        <td class="right">
            {{ number_format($packingList->total_gross_weight,2) }}
        </td>

        <td class="right">
            {{ number_format($packingList->total_net_weight,2) }}
        </td>

        <td>
            Total pallets
        </td>

        <td>
            {{ $packingList->total_pallets }}
        </td>

    </tr>

    <tr>

        <td rowspan="4" class="notes">

            INVOICE NUMBER:

            <br><br>

            Notes:

        </td>

        <td rowspan="4" colspan="2"></td>

        <td>
            Total Volume
        </td>

        <td>
            60 m3
        </td>

    </tr>

    <tr>

        <td>
            Total Package
        </td>

        <td>
            {{ $packingList->total_packages }}
        </td>

    </tr>

    <tr>

        <td>
            Total pieces
        </td>

        <td>
            {{ $packingList->total_item_quantity }}
        </td>

    </tr>

    <tr>

        <td></td>
        <td></td>

    </tr>

</table>

</body>
</html>

