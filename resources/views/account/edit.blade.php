@extends('account.master')
@section('title')
    Edit Calculation
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h4>Edit Calculation</h4>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('account.calculation.update',$calculation->id) }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                Booking Number
                            </label>

                            <input type="hidden"
                                   name="shipment_id"
                                   value="{{ $calculation->shipment_id }}">

                            <input type="text"
                                   class="form-control"
                                   value="{{ $calculation->shipment->booking_number }}"
                                   readonly>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                TCMB
                            </label>

                            <input type="number"
                                   step="0.0001"
                                   class="form-control"
                                   name="tcmb"
                                   id="tcmb" value="{{ $calculation->tcmb }}">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Shipping Cost
                            </label>

                            <input type="number"
                                   step="0.01"
                                   class="form-control"
                                   name="shipping_cost"
                                   id="shipping_cost"
                                   value="{{ $calculation->shipping_cost }}"
                            >
                        </div>

                        <div class="col-md-2 mb-3">

                            <label class="form-label d-block">
                                Add Percentage
                            </label>

                            <input type="checkbox"
                                   id="add_percentage"
                                    {{ $calculation->percentage > 0 ? 'checked' : '' }}>
                        </div>

                        <div class="col-md-3 mb-3 {{ $calculation->percentage > 0 ? '' : 'd-none' }}"
                             id="percentage_div">

                            <label class="form-label">
                                Percentage (%)
                            </label>

                            <input type="number"
                                   step="0.01"
                                   class="form-control"
                                   name="percentage"
                                   id="percentage" value="{{ $calculation->percentage }}">
                        </div>

                    </div>

                    <hr>

                    <div id="productArea">

                        @include('account.partials.edit-product')

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
                setTimeout(function(){

                    recalculateAllRows();

                }, 300);
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