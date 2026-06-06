@extends('account.master')

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
                      action="{{ route('account.commercial.update',$invoice->id) }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>
                                Booking Number
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="{{ $invoice->shipment->booking_number }}"
                                   readonly>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>
                                Export Number
                            </label>

                            <input type="text"
                                   name="export_number"
                                   class="form-control"
                                   value="{{ $invoice->export_number }}"
                                   required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label>
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

                    @include(
                        'account.commercial.partials.edit-product',
                        [
                            'calculation' =>
                            $invoice->calculation
                        ]
                    )

                    <hr>

                    <button type="submit"
                            class="btn btn-primary">

                        Update Commercial Invoice

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection