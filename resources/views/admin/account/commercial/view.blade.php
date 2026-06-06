@extends('admin.master')

@section('title')
    View Commercial Invoice
@endsection

@section('body')

    <div class="page-header">

        <h3 class="page-title">
            Commercial Invoice Details
        </h3>

        <a href="{{ route('account.commercial.admin.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-md-4">

                    <strong>
                        Booking Number
                    </strong>

                    <br>

                    {{ $invoice->shipment->booking_number }}

                </div>

                <div class="col-md-4">

                    <strong>
                        Export Number
                    </strong>

                    <br>

                    {{ $invoice->export_number }}

                </div>

                <div class="col-md-4">

                    <strong>
                        Shipping Cost
                    </strong>

                    <br>

                    {{ number_format(
                        $invoice->shipping_cost,
                        2
                    ) }}

                </div>

            </div>

        </div>

        <div class="card-body">

            @php

                $grandTotal = 0;

            @endphp

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                    <tr>

                        <th>
                            Product Name
                        </th>

                        <th>
                            Invoice Qty
                        </th>

                        <th>
                            Price PI A
                        </th>

                        <th>
                            Total Value
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach(
                        $invoice->calculation->items
                        as $item
                    )

                        @php

                            $pricePiA =
                                $item->direct_usd
                                ? $item->item_price
                                : $item->tl_usd;

                            $totalValue =
                                $pricePiA *
                                $item->invoice_qty;

                            $grandTotal +=
                                $totalValue;

                        @endphp

                        <tr>

                            <td>
                                {{ $item->english_name }}
                            </td>

                            <td>
                                {{ number_format(
                                    $item->invoice_qty
                                ) }}
                            </td>

                            <td>
                                {{ number_format(
                                    $pricePiA,
                                    4
                                ) }}
                            </td>

                            <td>
                                {{ number_format(
                                    $totalValue,
                                    2
                                ) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                    <tfoot>

                    <tr>

                        <th colspan="3"
                            class="text-end">

                            Grand Total

                        </th>

                        <th>

                            {{ number_format(
                                $grandTotal,
                                2
                            ) }}

                        </th>

                    </tr>

                    <tr>

                        <th colspan="3"
                            class="text-end">

                            Shipping Cost

                        </th>

                        <th>

                            {{ number_format(
                                $invoice->shipping_cost,
                                2
                            ) }}

                        </th>

                    </tr>

                    <tr>

                        <th colspan="3"
                            class="text-end">

                            Final Total

                        </th>

                        <th>

                            {{ number_format(
                                $grandTotal +
                                $invoice->shipping_cost,
                                2
                            ) }}

                        </th>

                    </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

@endsection