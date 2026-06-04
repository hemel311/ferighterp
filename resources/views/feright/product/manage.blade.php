@extends('feright.master')

@section('title')
    Manage Product
@endsection

@section('body')

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
            <span class="page-icon">
                <i class="bi bi-box-seam"></i>
            </span>
                <div>
                    <h1 class="h3 mb-1">Manage Products</h1>
                </div>
            </div>
        </div>

        <section class="panel">

            <div class="panel-header">

                <input class="form-control form-control-sm table-search"
                       type="search"
                       placeholder="Search Product"
                       data-table-search="productsTable">

            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0"
                       id="productsTable"
                       data-searchable-table>

                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product Name</th>
                        <th>HS Code</th>
                        <th>Description</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($products as $product)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $product->product_name }}</td>

                            <td>{{ $product->hs_code }}</td>

                            <td>{{ $product->description }}</td>

                            <td class="text-end">

                                <a href="{{ route('edit.product',$product->id) }}"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="{{ route('delete.product',$product->id) }}"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure?')">
                                    Delete
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