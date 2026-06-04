@extends('feright.master')

@section('title')
    Edit ISF
@endsection

@section('body')

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h4>Edit ISF</h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('isf.update',$isf->id) }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>MBL Prefix</label>

                                <select name="mbl_prefix_id"
                                        id="mbl_prefix_id"
                                        class="form-control select2">
                                    <option value="">Select Prefix</option>

                                    @foreach($prefixes as $prefix)
                                        <option value="{{ $prefix->id }}"
                                                data-prefix="{{ $prefix->prefix }}"
                                                {{ $isf->mbl_prefix_id == $prefix->id ? 'selected' : '' }}>
                                            {{ $prefix->shipping_company }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Booking Number</label>

                                <select name="shipment_id"
                                        id="shipment_id"
                                        class="form-control select2">
                                    <option value="">Select Booking</option>

                                    @foreach($shipments as $shipment)
                                        <option value="{{ $shipment->id }}"
                                                {{ $isf->shipment_id == $shipment->id ? 'selected' : '' }}>
                                            {{ $shipment->booking_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>From</label>

                                <textarea name="from_address"
                                          id="from_address"
                                          rows="4"
                                          class="form-control">{{ isset($isf) ? $isf->from_address : '' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>To</label>

                                <textarea name="to_address"
                                          id="to_address"
                                          rows="4"
                                          class="form-control">{{ isset($isf) ? $isf->to_address : '' }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Manufacturer</label>

                                <textarea name="manufacturer"
                                          id="manufacturer"
                                          rows="3"
                                          class="form-control">{{ isset($isf) ? $isf->manufacturer : '' }}</textarea>
                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Product Name</label>
                                <textarea name="product_name"
                                          id="product_name"
                                          rows="4"
                                          class="form-control">{{ $isf->product_name }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>HS Code</label>
                                <textarea name="hs_code"
                                          id="hs_code"
                                          rows="4"
                                          class="form-control">{{ $isf->hs_code }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>HBL Number</label>
                                <input type="text"
                                       name="hbl"
                                       id="hbl"
                                       class="form-control"
                                       value="{{ $isf->hbl }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>MBL Number</label>
                                <input type="text"
                                       name="mbl"
                                       id="mbl"
                                       class="form-control"
                                        value="{{$isf->mbl}}"
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Vessel Name</label>
                                <input type="text"
                                       name="vessel_name"
                                       id="vessel_name"
                                       class="form-control"
                                        value="{{$isf->vessel_name}}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Voyage No</label>
                                <input type="text"
                                       name="voyage"
                                       id="voyage"
                                       class="form-control"
                                        value="{{$isf->voyage}}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>ETD</label>
                                <input type="date"
                                       name="etd"
                                       id="etd"
                                       class="form-control"
                                        value="{{$isf->etd}}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Port Of Departure</label>
                                <input type="text"
                                       name="port_of_loading"
                                       id="port_of_loading"
                                       class="form-control"
                                        value="{{$isf->port_of_loading}}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Port Of Unloading</label>
                                <input type="text"
                                       name="port_of_discharge"
                                       id="port_of_discharge"
                                       class="form-control"
                                        value="{{$isf->port_of_discharge}}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Container Number(s)</label>
                                <textarea name="container_numbers"
                                          id="container_numbers"
                                          rows="3"
                                          class="form-control">{{ $isf->container_numbers }}</textarea>
                            </div>

                        </div>

                        <div class="mt-3">

                            <button type="submit"
                                    name="status"
                                    value="Draft"
                                    class="btn btn-warning">
                                Update Draft
                            </button>

                            <button type="submit"
                                    name="status"
                                    value="Submitted"
                                    class="btn btn-success">
                                Submit
                            </button>

                            <a href="{{ route('isf.index') }}"
                               class="btn btn-secondary">
                                Back
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {

            $('.select2').select2({
                width: '100%',
                placeholder: 'Select Option'
            });

            // Generate HBL & MBL
            function generateBlNumber()
            {
                let bookingNumber = $('#shipment_id option:selected').text().trim();

                let prefix = $('#mbl_prefix_id option:selected').data('prefix');

                if (prefix && bookingNumber)
                {
                    let blNumber = prefix + bookingNumber;

                    $('#hbl').val(blNumber);
                    $('#mbl').val(blNumber);
                }
            }

            // When Prefix Changes
            $('#mbl_prefix_id').on('change', function () {
                generateBlNumber();
            });

            // When Booking Changes
            $('#shipment_id').on('change', function () {

                generateBlNumber();

                let shipmentId = $(this).val();

                if (!shipmentId) {

                    $('#product_name').val('');
                    $('#hs_code').val('');
                    $('#etd').val('');
                    $('#port_of_loading').val('');
                    $('#port_of_discharge').val('');
                    $('#container_numbers').val('');

                    return;
                }

                $.ajax({
                    url: '/isf/get-shipment-data/' + shipmentId,
                    type: 'GET',
                    dataType: 'json',

                    success: function (response) {

                        let shipment = response.shipment;

                        // Shipment Information
                        $('#etd').val(shipment.etd);
                        $('#port_of_loading').val(shipment.port_of_loading);
                        $('#port_of_discharge').val(shipment.port_of_discharge);
                        $('#vessel_name').val(shipment.vessel_name);
                        $('#voyage').val(shipment.voyage);

                        // Product Names
                        let productNames = shipment.items.map(function(item) {
                            return item.item_name;
                        });

                        $('#product_name').val(
                            productNames.join('\n')
                        );

                        // HS Codes
                        let hsCodes = shipment.items.map(function(item) {
                            return item.hs_code;
                        });

                        $('#hs_code').val(
                            hsCodes.join('\n')
                        );

                        // Container Numbers
                        let containerNumbers = shipment.containers.map(function(container) {
                            return container.container_number;
                        });

                        $('#container_numbers').val(
                            containerNumbers.join('\n')
                        );
                    },

                    error: function(xhr) {
                        console.log(xhr);
                        alert('Unable to fetch shipment data');
                    }
                });

            });

        });
    </script>
@endpush
