@extends('feright.master')
@section('title')
    Edit Shipment
@endsection

@section('body')

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Edit Shipment</h4>
                    </div>

                    <div class="card-body">

                        <form action="{{route('update.shipment',['id'=>$shipment->id])}}" method="POST">

                            @csrf

                            <input type="hidden"
                                   name="status"
                                   id="status"
                                   value="{{ $shipment->status }}">

                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Booking Number <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="booking_number"
                                           value="{{ old('booking_number',$shipment->booking_number) }}"
                                           class="form-control">

                                    @error('booking_number')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Shipment Type
                                    </label>

                                    <select name="shipment_type" class="form-select">

                                        <option value="FCL"
                                                {{ $shipment->shipment_type == 'FCL' ? 'selected' : '' }}>
                                            FCL
                                        </option>

                                        <option value="LCL"
                                                {{ $shipment->shipment_type == 'LCL' ? 'selected' : '' }}>
                                            LCL
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Carrier
                                    </label>

                                    <input type="text"
                                           name="carrier"
                                           class="form-control"
                                           value="{{ old('carrier',$shipment->carrier) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Container Qty
                                    </label>

                                    <input type="number"
                                           name="container_qty"
                                           value="{{ old('container_qty',$shipment->container_qty) }}"
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
                                           class="form-control"
                                           value="{{ old('vessel_name',$shipment->vessel_name) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Voyage
                                    </label>

                                    <input type="text"
                                           name="voyage"
                                           class="form-control"
                                           value="{{ old('voyage',$shipment->voyage) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Port Of Loading
                                    </label>

                                    <input type="text"
                                           name="port_of_loading"
                                           class="form-control"
                                           value="{{ old('port_of_loading',$shipment->port_of_loading) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Port Of Discharge
                                    </label>

                                    <input type="text"
                                           name="port_of_discharge"
                                           class="form-control"
                                           value="{{ old('port_of_discharge',$shipment->port_of_discharge) }}"
                                    >
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        ETD
                                    </label>

                                    <input type="date"
                                           name="etd"
                                           class="form-control"
                                           value="{{ old('etd',$shipment->etd) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        ETA
                                    </label>

                                    <input type="date"
                                           name="eta"
                                           class="form-control"
                                           value="{{ old('eta',$shipment->eta) }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        SI Cut Off
                                    </label>

                                    <input type="datetime-local"
                                           name="si_cut_off"
                                           class="form-control"
                                           value="{{ old('si_cut_off',$shipment->si_cut_off ? \Carbon\Carbon::parse($shipment->si_cut_off)->format('Y-m-d\TH:i') : '') }}"
                                    >
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        CY Cut Off
                                    </label>

                                    <input type="datetime-local"
                                           name="cy_cut_off"
                                           class="form-control"
                                           value="{{ old('cy_cut_off',$shipment->cy_cut_off ? \Carbon\Carbon::parse($shipment->cy_cut_off)->format('Y-m-d\TH:i') : '') }}"
                                    >
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

                                @forelse($shipment->items as $item)

                                    <div class="row item-row mb-3">

                                        <div class="col-md-4">
                                            <input type="text"
                                                   name="hs_code[]"
                                                   value="{{ $item->hs_code }}"
                                                   class="form-control"
                                                   placeholder="HS Code">
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text"
                                                   name="item_name[]"
                                                   value="{{ $item->item_name }}"
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

                                @empty

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

                                @endforelse

                            </div>

                            <hr>

                            <div class="mb-3">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea name="remarks"
                                          rows="4"
                                          class="form-control">{{ old('remarks',$shipment->remarks) }}</textarea>

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
                                    Update Shipment
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



