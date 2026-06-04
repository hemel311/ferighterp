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

        body{
            font-family: DejaVu Sans;
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

<table>

    <tr>

        <td width="50%">
            <div class="box-title">
                (1) SELLER NAME AND ADDRESS
            </div>

            {{ $isf->from_address }}
        </td>

        <td width="50%">
            <div class="box-title">
                (2) BUYER NAME AND ADDRESS
            </div>

            {{ $isf->to_address }}
        </td>

    </tr>

    <tr>

        <td class="cell-height">
            <div class="box-title">
                (3) CONSOLIDATOR (STUFFER) NAME AND ADDRESS
            </div>

            {{ $isf->from_address }}
        </td>

        <td class="cell-height">
            <div class="box-title">
                (4) CONTAINER STUFFING LOCATION NAME AND ADDRESS
            </div>
            ATLANTIC GROUP TEKSTIL ELEKTRONIK A.S DERVISLER
            SOKAK NO1/3 HOCAPASA MAH FATIH ISTANBUL TURKEY
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

<table>

    <tr>

        <td width="50%">
            <div class="box-title">
                (5) COMMODITY HTSUS NUMBER(S) AND DESCRIPTION
            </div>

            {{ implode(', ', $combined) }}
        </td>

        <td width="15%">
            <div class="box-title">
                (6) COUNTRY OF ORIGIN
            </div>

            TURKEY-TR
        </td>

        <td width="35%">
            <div class="box-title">
                (7) MANUFACTURER
            </div>

            {{ $isf->manufacturer }}
        </td>

    </tr>

</table>

<br>

<br>

<div style="font-size:11px;">
    This form, or something similar AND a copy of the commercial Invoice(s)
    MUST be completed in English and e mailed to the USA office no later
    than 72 hours prior to sailing.
</div>

<br><br>



<table>

    <tr>

        <td>
            <strong>House B/L #</strong>
            <br>
            {{ $isf->hbl }}
        </td>

        <td>
            <strong>Master B/L #</strong>
            <br>
            {{ $isf->mbl }}
        </td>

        <td>
            <strong>Vessel Name</strong>
            <br>
            {{ $isf->vessel_name }}
        </td>

        <td>
            <strong>Voyage No.</strong>
            <br>
            {{ $isf->voyage }}
        </td>

    </tr>

    <tr>

        <td>
            <strong>Date of Departure</strong>
            <br>
            {{ \Carbon\Carbon::parse($isf->etd)->format('d-M-y') }}
        </td>

        <td>
            <strong>Port of Departure</strong>
            <br>
            {{ $isf->port_of_loading }}
        </td>

        <td colspan="2">
            <strong>Container Number(s)</strong>
            <br>
            {{ str_replace("\n", ",", $isf->container_numbers) }}
        </td>

    </tr>

</table>

<br>

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