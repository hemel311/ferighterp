@extends('feright.master')

@section('title')
    TR Packing List Preview
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            TR Packing List Preview
        </h3>
    </div>

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Packing List Details
                </h5>

                <div>

                    <a href="{{ route('trpl.edit',$packingList->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="{{route('trpl.export.pdf',$packingList->id)}}"
                       class="btn btn-danger btn-sm">
                        PDF
                    </a>

                    <a href="{{ route('trpl.export.excel',$packingList->id) }}"
                       class="btn btn-success btn-sm">
                        Excel
                    </a>
                    <a href="{{ route('trpl.delete',$packingList->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Packing List?')"> Delete </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Booking Number</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->booking_number }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Container Number</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->container_number }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>VGM Gross Weight</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->container->vgmInfo->gross_weight }}"
                           readonly>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Date</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->pl_date }}"
                           readonly>
                </div>

                <div class="col-md-6 mb-3">

                    <label>From</label>

                    <textarea class="form-control"
                              rows="4"
                              readonly>{{ $packingList->from_location }}</textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label>To</label>

                    <textarea class="form-control"
                              rows="4"
                              readonly>{{ $packingList->to_location }}</textarea>

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
                        <th>Pallets</th>
                        <th>Packages</th>
                        <th>Quantity  item per Palet/pack & kg</th>
                        <th>Total KG</th>
                        <th>Gross Weight</th>
                        <th>Pallet/Pack KG</th>
                        <th>Total Quantity</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($packingList->items as $item)

                        <tr>

                            <td>{{ $item->product_name }}</td>

                            <td>{{ $item->total_pallets }}</td>

                            <td>{{ $item->total_packages }}</td>
                            <td>{{ $item->quantity_per_unit }}</td>

                            <td>{{ $item->net_weight }}</td>

                            <td>{{ $item->gross_weight }}</td>

                            <td>

                                {{ $item->pallet_pack_kg}}

                            </td>

                            <td>{{ $item->item_quantity }}</td>

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
                           value="{{ $packingList->total_net_weight }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Gross Weight</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->total_gross_weight }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Pallets</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->total_pallets }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Packages</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->total_packages }}"
                           readonly>
                </div>

                <div class="col-md-2">
                    <label>Total Pieces</label>

                    <input type="text"
                           class="form-control"
                           value="{{ $packingList->total_item_quantity }}"
                           readonly>
                </div>

            </div>

        </div>

    </div>

@endsection