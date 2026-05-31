@extends('feright.master')
@section('title')
    Add Shipment
@endsection

@section('body')

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Create Shipment</h4>
                    </div>

                    <div class="card-body">

                        <form action="{{route('create.shipment')}}" method="POST">

                            @csrf

                            <input type="hidden"
                                   name="status"
                                   id="status"
                                   value="Draft">

                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Booking Number <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="booking_number"
                                           value="{{ old('booking_number') }}"
                                           class="form-control">

                                    @error('booking_number')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Shipment Type
                                    </label>

                                    <select name="shipment_type"
                                            class="form-select">

                                        <option value="FCL">FCL</option>
                                        <option value="LCL">LCL</option>

                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Carrier
                                    </label>

                                    <input type="text"
                                           name="carrier"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Container Qty
                                    </label>

                                    <input type="number"
                                           name="container_qty"
                                           value="1"
                                           min="1"
                                           class="form-control">
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Vessel Name
                                    </label>

                                    <input type="text"
                                           name="vessel_name"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Voyage
                                    </label>

                                    <input type="text"
                                           name="voyage"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Port Of Loading
                                    </label>

                                    <input type="text"
                                           name="port_of_loading"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Port Of Discharge
                                    </label>

                                    <input type="text"
                                           name="port_of_discharge"
                                           class="form-control">
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        ETD
                                    </label>

                                    <input type="date"
                                           name="etd"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        ETA
                                    </label>

                                    <input type="date"
                                           name="eta"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        SI Cut Off
                                    </label>

                                    <input type="datetime-local"
                                           name="si_cut_off"
                                           class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        CY Cut Off
                                    </label>

                                    <input type="datetime-local"
                                           name="cy_cut_off"
                                           class="form-control">
                                </div>

                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Shipment Items</h5>

                                <button type="button"
                                        id="add-item"
                                        class="btn btn-success btn-sm">
                                    Add Item
                                </button>
                            </div>

                            <div id="item-container">

                                <div class="row item-row mb-3">

                                    <div class="col-md-4">
                                        <input type="text"
                                               name="hs_code[]"
                                               class="form-control"
                                               placeholder="HS Code">
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text"
                                               name="item_name[]"
                                               class="form-control"
                                               placeholder="Item Name">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button"
                                                class="btn btn-danger remove-item">
                                            Remove
                                        </button>
                                    </div>

                                </div>

                            </div>

                            <hr>

                            <div class="mb-3">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea name="remarks"
                                          rows="4"
                                          class="form-control"></textarea>

                            </div>

                            <div class="mt-4">

                                <button type="submit"
                                        class="btn btn-warning"
                                        onclick="document.getElementById('status').value='Draft'">
                                    Save Draft
                                </button>

                                <button type="submit"
                                        class="btn btn-primary"
                                        onclick="document.getElementById('status').value='Submitted'">
                                    Submit Shipment
                                </button>

                                <a href=""
                                   class="btn btn-secondary">
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
@push('js')
    <script>

        $(document).ready(function(){

            $('#add-item').on('click', function(){

                $('#item-container').append(`
            <div class="row item-row mb-3">

                <div class="col-md-4">
                    <input type="text"
                           name="hs_code[]"
                           class="form-control"
                           placeholder="HS Code">
                </div>

                <div class="col-md-6">
                    <input type="text"
                           name="item_name[]"
                           class="form-control"
                           placeholder="Item Name">
                </div>

                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-danger remove-item">
                        Remove
                    </button>
                </div>

            </div>
        `);

            });

            $(document).on('click', '.remove-item', function(){

                if($('.item-row').length > 1){
                    $(this).closest('.item-row').remove();
                }

            });

        });

    </script>
@endpush



