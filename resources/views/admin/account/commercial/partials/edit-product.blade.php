@extends('admin.master')

@section('title')
    Edit Commercial Invoice
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4>Edit Commercial Invoice</h4>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('account.commercial.admin.update',$invoice->id) }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Booking Number
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $invoice->shipment->booking_number }}"
                                   readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Export Number
                            </label>

                            <input type="text"
                                   name="export_number"
                                   class="form-control"
                                   value="{{ $invoice->export_number }}"
                                   required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Shipping Cost
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="shipping_cost"
                                   id="shipping_cost"
                                   class="form-control"
                                   value="{{ $invoice->shipping_cost }}">

                        </div>

                    </div>

                    <hr>

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

                            @foreach($invoice->calculation->items as $item)

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
                                        {{ number_format($item->invoice_qty) }}
                                    </td>

                                    <td>
                                        {{ number_format($pricePiA,4) }}
                                    </td>

                                    <td>
                                        {{ number_format($totalValue,2) }}
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

                                <th id="grandTotal">

                                    {{ number_format($grandTotal,2) }}

                                </th>

                            </tr>

                            <tr>

                                <th colspan="3"
                                    class="text-end">

                                    Shipping Cost

                                </th>

                                <th id="shippingCostDisplay">

                                    {{ number_format($invoice->shipping_cost,2) }}

                                </th>

                            </tr>

                            <tr>

                                <th colspan="3"
                                    class="text-end">

                                    Final Total

                                </th>

                                <th id="finalTotal">

                                    {{ number_format(
                                        $grandTotal + $invoice->shipping_cost,
                                        2
                                    ) }}

                                </th>

                            </tr>

                            </tfoot>

                        </table>

                    </div>

                    <hr>

                    <button type="submit"
                            class="btn btn-primary">

                        Update Commercial Invoice

                    </button>

                    <a href="{{ route('account.commercial.admin.index') }}"
                       class="btn btn-secondary">

                        Back

                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('js')

    <script>

        $(document).ready(function(){

            function updateFinalTotal()
            {
                let grandTotal = parseFloat(
                    $('#grandTotal')
                        .text()
                        .replace(/,/g,'')
                ) || 0;

                let shippingCost = parseFloat(
                    $('#shipping_cost').val()
                ) || 0;

                $('#shippingCostDisplay').text(
                    shippingCost.toFixed(2)
                );

                $('#finalTotal').text(
                    (grandTotal + shippingCost)
                        .toFixed(2)
                );
            }

            $('#shipping_cost').on(
                'keyup change',
                function(){

                    updateFinalTotal();

                }
            );

        });

    </script>

@endpush