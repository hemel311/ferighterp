@extends('account.master')

@section('title')
    View Calculation
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            Calculation Details
        </h3>

        <a href="{{ route('account.calculation.index') }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">

        <div class="card-header">

            <div class="row">

                <div class="col-md-3">
                    <strong>Booking Number:</strong><br>
                    {{ $calculation->shipment->booking_number }}
                </div>

                <div class="col-md-3">
                    <strong>TCMB:</strong><br>
                    {{ number_format($calculation->tcmb,4) }}
                </div>

                <div class="col-md-3">
                    <strong>Shipping Cost:</strong><br>
                    {{ number_format($calculation->shipping_cost,2) }}
                </div>

                <div class="col-md-3">
                    <strong>Percentage:</strong><br>
                    {{ $calculation->percentage ?? 0 }} %
                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                    <tr>

                        <th>Turkish Name</th>
                        <th>English Name</th>
                        <th>Invoice Qty</th>
                        <th>Original Price</th>
                        <th>Item Price</th>
                        <th>Price PI A</th>
                        <th>Shipping Additional</th>
                        <th>CIF Price</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($calculation->items as $item)

                        <tr>

                            <td>
                                {{ $item->turkish_name }}
                            </td>

                            <td>
                                {{ $item->english_name }}
                            </td>

                            <td>
                                {{ $item->invoice_qty }}
                            </td>

                            <td>
                                {{ number_format($item->original_price,2) }}
                            </td>

                            <td>
                                {{ number_format($item->item_price,2) }}
                            </td>

                            <td>
                                {{ number_format($item->tl_usd,4) }}
                            </td>

                            <td>
                                {{ number_format($item->shipping_additional,4) }}
                            </td>

                            <td>
                                {{ number_format($item->cif_price,4) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                    <tfoot>

                    <tr>
                    </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

@endsection