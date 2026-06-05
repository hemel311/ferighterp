@extends('account.master')
@section('title')
    Create Calculation
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h4>Create Calculation</h4>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('account.calculation.store') }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                Booking Number
                            </label>

                            <select name="shipment_id"
                                    id="shipment_id"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select Booking
                                </option>

                                @foreach($shipments as $shipment)

                                    <option value="{{ $shipment->id }}">
                                        {{ $shipment->booking_number }}
                                    </option>

                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                TCMB
                            </label>

                            <input type="number"
                                   step="0.0001"
                                   class="form-control"
                                   name="tcmb"
                                   id="tcmb">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Shipping Cost
                            </label>

                            <input type="number"
                                   step="0.01"
                                   class="form-control"
                                   name="shipping_cost"
                                   id="shipping_cost">
                        </div>

                        <div class="col-md-2 mb-3">

                            <label class="form-label d-block">
                                Add Percentage
                            </label>

                            <input type="checkbox"
                                   id="add_percentage">
                        </div>

                        <div class="col-md-3 mb-3 d-none"
                             id="percentage_div">

                            <label class="form-label">
                                Percentage (%)
                            </label>

                            <input type="number"
                                   step="0.01"
                                   class="form-control"
                                   name="percentage"
                                   id="percentage">
                        </div>

                    </div>

                    <hr>

                    <div id="productArea">

                        <div class="text-center text-muted">

                            Select a booking number to load products.

                        </div>

                    </div>

                    <hr>

                    <button type="submit"
                            class="btn btn-primary">

                        Save Calculation

                    </button>

                </form>

            </div>
        </div>

    </div>

@endsection

@push('js')

    <script>

        $(document).ready(function(){

            $('#add_percentage').change(function(){

                $('#percentage_div').toggleClass(
                    'd-none'
                );

            });

            $('#shipment_id').change(function(){

                let shipmentId = $(this).val();

                if(shipmentId == '')
                {
                    $('#productArea').html('');
                    return;
                }

                $.ajax({

                    url:
                        "{{ route('account.calculation.loadProducts', ':id') }}"
                            .replace(':id', shipmentId),

                    type: 'GET',

                    success:function(response){

                        console.log(response);

                        $('#productArea').html(response);



                    }

                });

            });

        });

    </script>

@endpush