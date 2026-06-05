@extends('admin.master')

@section('title')
    US Packing List Preview
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            US Packing List Preview
        </h3>
    </div>

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Packing List Details
                </h5>

                <div>

                    <a href="{{route('admin.uspl.edit',['id'=>$packingList->id])}}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="{{route('admin.uspl.export.pdf',['id'=>$packingList->id])}}"
                       class="btn btn-danger btn-sm">
                        PDF
                    </a>

                    <a href="{{route('admin.uspl.export.excel',['id'=>$packingList->id])}}"
                       class="btn btn-success btn-sm">
                        Excel
                    </a>
                    <a href="{{route('admin.us.delete',['id'=>$packingList->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Packing List?')"> Delete </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Booking Number</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->booking_number }}"
                           readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Container Number</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->container_number }}"
                           readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label>VGM Gross Weight</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->vgmInfo->gross_weight ?? 0 }}"
                           readonly>
                </div>

            </div>

        </div>

    </div>
    <div class="card mt-3">

        <div class="card-header">
            <h5 class="mb-0">
                Products
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead class="bg-dark text-white">

                    <tr>

                        <th>Product Name</th>
                        <th>Warehouse Code</th>
                        <th>Pallets</th>
                        <th>Packages</th>
                        <th style="">
                            Quantity Item per Palet/Pack & kg
                        </th>
                        <th>Total KG</th>
                        <th>Gross Weight</th>
                        <th>Pallet/Pack KG</th>
                        <th>Total Quantity</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($packingList->products as $item)

                        <tr>

                            <td>{{ $item->product_name }}</td>

                            <td>{{ $item->warehouse_code }}</td>

                            <td>{{ $item->total_pallets }}</td>

                            <td>{{ $item->packages }}</td>
                            <td>{{ $item->qty_per_pallet }}</td>

                            <td>{{ $item->total_kg }}</td>

                            <td>{{ $item->gross_weight }}</td>

                            <td>{{ $item->pallet_pack_kg }}</td>

                            <td>{{ $item->total_item_qty }}</td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    <div class="card mt-3">

        <div class="card-header">
            <h5 class="mb-0">
                Summary
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-2">
                    <label>Total Net Weight</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->products->sum('total_kg') }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Gross Weight</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->products->sum('gross_weight') }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Pallets</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->products->sum('total_pallets') }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Packages</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->products->sum('packages') }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Pieces</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->products->sum('total_item_qty') }}"
                           readonly>
                </div>

            </div>

        </div>

    </div>

@endsection