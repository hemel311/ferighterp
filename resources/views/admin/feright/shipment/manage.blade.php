@extends('admin.master')
@section('title')
    Manage Shipment
@endsection

@section('body')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Manage Shipment</h1>
                </div>
            </div>

        </div>

        <section class="panel">
            <div class="panel-header">

                <input class="form-control form-control-sm table-search" type="search" placeholder="Search shipment" data-table-search="ordersTable" aria-label="Search orders">
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
                    <thead>
                    <tr>
                        <th>SL no</th>
                        <th>Booking number</th>
                        <th>Container Qty</th>
                        <th>SI CUT OFF</th>
                        <th>CY CUT OFF</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>{{$shipment->booking_number}}</td>
                            <td>{{$shipment->container_qty}}</td>
                            <td>{{$shipment->si_cut_off}}</td>
                            <td>{{$shipment->cy_cut_off}}</td>
                            <td>

                                @if($shipment->status == 'Draft')
                                    <span class="badge bg-warning">Draft</span>

                                @elseif($shipment->status == 'Submitted')
                                    <span class="badge bg-primary">Submitted</span>

                                @elseif($shipment->status == 'Completed')
                                    <span class="badge bg-success">Completed</span>

                                @elseif($shipment->status == 'Cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif

                            </td>

                            <td class="text-end">
                                <a href="{{route('see.shipment',['id'=>$shipment->id])}}">
                                    <button class="btn btn-info btn-sm" type="button">
                                        See Details
                                    </button>
                                </a>
                                @if($shipment->status == 'Draft')

                                    <a href="{{route('edit.shipment',['id'=>$shipment->id])}}">
                                        <button class="btn btn-warning btn-sm" type="button">
                                            Continue
                                        </button>
                                    </a>

                                @else

                                    <a href="{{route('edit.shipment',['id'=>$shipment->id])}}">
                                        <button class="btn btn-light btn-sm" type="button">
                                            Edit
                                        </button>
                                    </a>

                                @endif

                                <a href="{{route('delete.shipment',['id'=>$shipment->id])}}">
                                    <button class="btn btn-danger btn-sm"
                                            type="button"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection