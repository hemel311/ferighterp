@extends('admin.master')

@section('title')
    Create Commercial Invoice
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4>Create Commercial Invoice</h4>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('account.commercial.admin.store') }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label>
                                Booking Number
                            </label>

                            <select name="shipment_id"
                                    id="shipment_id"
                                    class="form-control select2"
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

                        <div class="col-md-4 mb-3">

                            <label>
                                Export Number
                            </label>

                            <input type="text"
                                   name="export_number"
                                   class="form-control"
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
                                   value="0">

                        </div>

                    </div>

                    <hr>

                    <div id="productArea">

                        <div class="text-center text-muted">

                            Select Booking Number

                        </div>

                    </div>

                    <hr>

                    <button type="submit"
                            class="btn btn-primary">

                        Save Commercial Invoice

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('js')

    <script>

        $(document).ready(function(){

            $('.select2').select2();

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

            $('#shipment_id').change(function(){

                let shipmentId = $(this).val();

                if(shipmentId == '')
                {
                    $('#productArea').html(
                        '<div class="text-center text-muted">Select Booking Number</div>'
                    );

                    return;
                }

                $.ajax({

                    url: "{{ route('account.commercial.admin.load', ':id') }}"
                        .replace(':id', shipmentId),

                    type: 'GET',

                    beforeSend:function()
                    {
                        $('#productArea').html(
                            '<div class="text-center">Loading...</div>'
                        );
                    },

                    success:function(response)
                    {
                        $('#productArea').html(
                            response
                        );

                        updateFinalTotal();
                    },

                    error:function(xhr)
                    {
                        $('#productArea').html(
                            '<div class="alert alert-danger">Calculation not found.</div>'
                        );
                    }

                });

            });

            $(document).on(
                'keyup change',
                '#shipping_cost',
                function(){
                    updateFinalTotal();
                }
            );

        });

    </script>

@endpush