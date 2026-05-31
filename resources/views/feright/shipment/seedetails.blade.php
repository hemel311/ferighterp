@extends('feright.master')

@section('title')
    Shipment Details
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Shipment Details</h4>

                <a href="{{ route('manage.shipment') }}"
                   class="btn btn-secondary btn-sm">
                    Back
                </a>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Booking Number</label>
                        <p>{{ $shipment->booking_number }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Shipment Type</label>
                        <p>{{ $shipment->shipment_type }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Carrier</label>
                        <p>{{ $shipment->carrier }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Container Qty</label>
                        <p>{{ $shipment->container_qty }}</p>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Vessel Name</label>
                        <p>{{ $shipment->vessel_name }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Voyage</label>
                        <p>{{ $shipment->voyage }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Port Of Loading</label>
                        <p>{{ $shipment->port_of_loading }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Port Of Discharge</label>
                        <p>{{ $shipment->port_of_discharge }}</p>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">ETD</label>
                        <p>{{ $shipment->etd }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">ETA</label>
                        <p>{{ $shipment->eta }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">SI Cut Off</label>
                        <p>{{ $shipment->si_cut_off }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">CY Cut Off</label>
                        <p>{{ $shipment->cy_cut_off }}</p>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="fw-bold">Status</label>

                        @if($shipment->status == 'Draft')
                            <p><span class="badge bg-warning">Draft</span></p>
                        @elseif($shipment->status == 'Submitted')
                            <p><span class="badge bg-primary">Submitted</span></p>
                        @elseif($shipment->status == 'Completed')
                            <p><span class="badge bg-success">Completed</span></p>
                        @else
                            <p><span class="badge bg-danger">Cancelled</span></p>
                        @endif

                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Shipment Items</h5>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th width="20%">HS Code</th>
                            <th>Item Name</th>
                        </tr>
                        </thead>

                        <tbody>

                        @forelse($shipment->items as $item)

                            <tr>
                                <td>{{ $item->hs_code }}</td>
                                <td>{{ $item->item_name }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="2" class="text-center">
                                    No Items Found
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    <label class="fw-bold">Remarks</label>
                    <p>{{ $shipment->remarks }}</p>
                </div>

            </div>

        </div>

    </div>

@endsection