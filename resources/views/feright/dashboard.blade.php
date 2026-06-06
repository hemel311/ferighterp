@extends('feright.master')
@section('title')
    Feright Forwarder
@endsection
@section('body')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Dashboard</h1>
                </div>
            </div>

        </div>

        <section class="row g-3 mt-1" aria-label="Dashboard metrics">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-primary">
                    <div class="metric-top">
    <span class="metric-label">
        Total Shipments
    </span>

                        <span class="metric-icon">
        <i class="bi bi-truck"></i>
    </span>
                    </div>

                    <div class="metric-value">
                        {{ $totalShipments }}
                    </div>

                    <div class="metric-meta">
                        <span>All shipments</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-success">
                    <div class="metric-top">
    <span class="metric-label">
        Draft Shipments
    </span>

                        <span class="metric-icon">
        <i class="bi bi-file-earmark"></i>
    </span>
                    </div>

                    <div class="metric-value">
                        {{ $draftShipments }}
                    </div>

                    <div class="metric-meta">
                        <span>Pending preparation</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-warning">
                    <div class="metric-top">
    <span class="metric-label">
        Submitted Shipments
    </span>

                        <span class="metric-icon">
        <i class="bi bi-send-check"></i>
    </span>
                    </div>

                    <div class="metric-value">
                        {{ $submittedShipments }}
                    </div>

                    <div class="metric-meta">
                        <span>Currently processing</span>
                    </div>
                </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-danger">
                    <div class="metric-top">
    <span class="metric-label">
        Today's Activities
    </span>

                        <span class="metric-icon">
        <i class="bi bi-calendar-event"></i>
    </span>
                    </div>

                    <div class="metric-value">
                        {{ $todayActivities }}
                    </div>

                    <div class="metric-meta">
                        <span>SI / CY / ETD / ETA</span>
                    </div>
                </article>
            </div>
        </section>
        <div class="row">

            {{-- Calendar --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipment Calendar</h5>
                    </div>

                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            Today's Activities
                        </h6>
                    </div>

                    <div class="card-body">

                        @forelse($todayShipments as $shipment)

                            <div class="border rounded p-2 mb-2">

                                <div class="fw-bold mb-2">
                                    {{ $shipment->booking_number }}
                                </div>

                                @if($shipment->si_cut_off &&
                                    \Carbon\Carbon::parse($shipment->si_cut_off)->isToday())

                                    <span class="badge bg-success">
            SI Cut Off
        </span>

                                @endif

                                @if($shipment->cy_cut_off &&
                                    \Carbon\Carbon::parse($shipment->cy_cut_off)->isToday())

                                    <span class="badge bg-danger">
            CY Cut Off
        </span>

                                @endif

                                @if($shipment->etd &&
                                    \Carbon\Carbon::parse($shipment->etd)->isToday())

                                    <span class="badge bg-primary">
            ETD
        </span>

                                @endif

                                @if($shipment->eta &&
                                    \Carbon\Carbon::parse($shipment->eta)->isToday())

                                    <span class="badge bg-info">
            ETA
        </span>

                                @endif

                            </div>

                        @empty

                            <div class="alert alert-success mb-0">
                                No shipment activities today.
                            </div>

                        @endforelse

                    </div>

                </div>

                <div class="card mt-3">

                    <div class="card-header bg-warning">
                        <h6 class="mb-0">
                            Tomorrow Activities
                        </h6>
                    </div>

                    <div class="card-body">

                        @forelse($tomorrowShipments as $shipment)

                            <div class="border rounded p-2 mb-2">

                                <div class="fw-bold mb-2">
                                    {{ $shipment->booking_number }}
                                </div>

                                @if($shipment->si_cut_off &&
                                    \Carbon\Carbon::parse($shipment->si_cut_off)->isTomorrow())

                                    <span class="badge bg-success">
                        SI Cut Off
                    </span>

                                @endif

                                @if($shipment->cy_cut_off &&
                                    \Carbon\Carbon::parse($shipment->cy_cut_off)->isTomorrow())

                                    <span class="badge bg-danger">
                        CY Cut Off
                    </span>

                                @endif

                                @if($shipment->etd &&
                                    \Carbon\Carbon::parse($shipment->etd)->isTomorrow())

                                    <span class="badge bg-primary">
                        ETD
                    </span>

                                @endif

                                @if($shipment->eta &&
                                    \Carbon\Carbon::parse($shipment->eta)->isTomorrow())

                                    <span class="badge bg-info">
                        ETA
                    </span>

                                @endif

                            </div>

                        @empty

                            <div class="alert alert-success mb-0">
                                No shipment activities tomorrow.
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>
        </div>
@endsection