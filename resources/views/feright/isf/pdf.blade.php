<!DOCTYPE html>
@php
    $hsCodes = preg_split('/\r\n|\r|\n/', trim($isf->hs_code));
    $products = preg_split('/\r\n|\r|\n/', trim($isf->product_name));

    $combined = [];

    foreach ($hsCodes as $index => $hsCode) {
        $combined[] = trim($hsCode) . ' ' . trim($products[$index] ?? '');
    }
@endphp
<html>
<head>
    <meta charset="utf-8">

    <style>
        @font-face {
            font-family: 'CalistoMT';
            src: url('{{ public_path('fonts/CALIST.TTF') }}')
            format('truetype');
        }

        body{
            font-family: CalistoMT;
            font-size:11px;
            line-height:1.3;
        }

        .title{
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:5px;
        }

        .subtitle{
            text-align:center;
            font-size:10px;
            margin-bottom:10px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td,th{
            border:1px solid #000;
            padding:5px;
            vertical-align:top;
        }

        .section-title{
            font-weight:bold;
            margin-top:8px;
            margin-bottom:5px;
        }

        .box-title{
            font-weight:bold;
            font-size:10px;
            margin-bottom:5px;
        }

        .cell-height{
            height:80px;
        }

        .small{
            font-size:10px;
        }
        .isf-table{
            width:100%;
            border-collapse:collapse;
            border:2px solid #000;
        }

        .isf-table td{
            border:1px solid #000;
            padding:3px;
            vertical-align:top;
        }

    </style>
</head>
<body>

<div class="title">
    IMPORTER SECURITY FILING (ISF) INFORMATION SHEET
</div>

<div class="subtitle">
    ALL FILINGS MUST BE COMPLETED AT LEAST 24 HOURS PRIOR TO THE VESSEL SAILING TO THE USA
</div>

<div class="section-title">
    ITEMS 1-7 to be completed by shipper and verified by ISF Filer:
</div>

<table class="isf-table">

    <tr>

        <td style="
            width:50%;
            border:1px solid #000;
            text-align:center;
            font-weight:bold;
            padding:4px;
        ">
            (1) SELLER NAME AND ADDRESS
        </td>

        <td style="
            width:50%;
            border:1px solid #000;
            text-align:center;
            font-weight:bold;
            padding:4px;
        ">
            (2) BUYER NAME AND ADDRESS
        </td>

    </tr>

    <tr>

        <td style="
            height:85px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            {!! nl2br(e($isf->from_address)) !!}
        </td>

        <td style="
            height:85px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            {!! nl2br(e($isf->to_address)) !!}
        </td>

    </tr>

    <tr>

        <td style="
            border:1px solid #000;
            text-align:center;
            font-weight:bold;
            padding:4px;
        ">
            (3) CONSOLIDATOR (STUFFER) NAME AND ADDRESS
        </td>

        <td style="
            border:1px solid #000;
            text-align:center;
            font-weight:bold;
            padding:4px;
        ">
            (4) CONTAINER STUFFING LOCATION NAME AND ADDRESS
        </td>

    </tr>

    <tr>

        <td style="
            height:85px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            {!! nl2br(e($isf->from_address)) !!}
        </td>

        <td style="
            height:85px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            {!! nl2br(e($isf->from_address)) !!}
        </td>

    </tr>

</table>

<br>

<div class="small">
    <strong>
        PER ISF RULE, HTSUS, Country of Origin, and Manufacturer must be linked to one another at the line item level.
    </strong>
    <br>
    Add additional sheets for additional HTSUS numbers
</div>

<br>

<table class="isf-table">

    <tr>

        <td style="
            width:65%;
            border:1px solid #000;
            font-weight:bold;
            padding:4px;
            vertical-align:top;
        ">
            (5) COMMODITY HTSUS NUMBER(S) AND DESCRIPTION
        </td>

        <td style="
            width:15%;
            border:1px solid #000;
            font-weight:bold;
            padding:4px;
            vertical-align:top;
        ">
            (6) COUNTRY OF ORIGIN
        </td>

        <td style="
            width:20%;
            border:1px solid #000;
            font-weight:bold;
            padding:4px;
            vertical-align:top;
        ">
            (7) MANUFACTURER
        </td>

    </tr>

    <tr>

        <td style="
            height:65px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">

            @php
                $hsCodes = preg_split('/\r\n|\r|\n/', trim($isf->hs_code));
                $products = preg_split('/\r\n|\r|\n/', trim($isf->product_name));

                $combined = [];

                foreach ($hsCodes as $index => $hsCode) {
                    $combined[] = trim($hsCode).' '.trim($products[$index] ?? '');
                }
            @endphp

            {{ implode(', ', $combined) }}

        </td>

        <td style="
            height:65px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            TURKEY-TR
        </td>

        <td style="
            height:65px;
            border:1px solid #000;
            vertical-align:top;
            padding:6px;
        ">
            {{ $isf->manufacturer }}
        </td>

    </tr>

</table>

<div style="font-size:11px;">
    To be completed by Shipper and the Origin freight forwarder:
</div>

<table class="isf-table">

    <tr style="height:22px;">

        <td style="width:20%; border:1px solid #000; padding:3px;">
            <strong>House B/L #</strong><br>
            {{ $isf->hbl }}
        </td>

        <td style="width:48%; border:1px solid #000; padding:3px;">
            <strong>Master B/L #</strong><br>
            {{ $isf->mbl }}
        </td>

        <td style="width:16%; border:1px solid #000; padding:3px;">
            <strong>Vessel Name</strong><br>
            {{ $isf->vessel_name }}
        </td>

        <td style="width:16%; border:1px solid #000; padding:3px;">
            <strong>Voyage No.</strong><br>
            {{ $isf->voyage }}
        </td>

    </tr>

    <tr>

        <td style="border:1px solid #000; padding:3px;">
            <strong>Date of Departure</strong><br>
            {{ \Carbon\Carbon::parse($isf->etd)->format('d-M-y') }}
        </td>

        <td style="border:1px solid #000; padding:3px;">
            <strong>Port of Departure</strong><br>
            {{ $isf->port_of_loading }}
        </td>

        <td style="border:1px solid #000; padding:3px;">
            <strong>Co Number(s)</strong><br>

            {{ collect(preg_split('/\r\n|\r|\n/', trim($isf->container_numbers)))
                ->filter()
                ->implode(', ') }}
        </td>

        <td style="border:1px solid #000; padding:3px;">
            <strong>Port&nbsp;of&nbsp;Unloading&nbsp;&amp;&nbsp;ETA</strong><br>

            {{ $isf->port_of_discharge }}

            @if(!empty($isf->eta))
                <br>
                ETA: {{ \Carbon\Carbon::parse($isf->eta)->format('d-M-y') }}
            @endif
        </td>

    </tr>

</table>


<div class="section-title">
    Items 8-10 to be completed at destination by ISF Filer / Importer:
</div>

<table>

    <tr>
        <td width="30%">
            <strong>(8) Importer of Record No</strong>
        </td>

        <td width="30%">
            <strong>(9) Consignee No</strong>
        </td>

        <td width="40%">
            <strong>(10) Ship to Name and Address</strong>
        </td>
    </tr>

    <tr>

        <td style="height:60px;">
            32-056758900
        </td>

        <td>
            &nbsp;
        </td>

        <td>
            {!! nl2br(e($isf->to_address)) !!}
        </td>

    </tr>

</table>

<br>

<div class="small">
    This form, or something similar AND a copy of the commercial Invoice(s) MUST be completed in English and e mailed to the USA office no later than 72 hours prior to sailing.
</div>
<table style="border:0; width:100%;">
    <tr>

        <td style="border:0; width:35%;">
            <strong>ISF APPROVAL NUMBER:</strong>
        </td>

        <td style="border:1px solid #000; width:15%; height:20px;">
            &nbsp;
        </td>

        <td style="border:0;"></td>

    </tr>
</table>
</body>
</html>