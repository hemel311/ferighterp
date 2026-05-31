@extends('admin.master')
@section('title')
    Manage Prefix
@endsection

@section('body')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
                <div>
                    <h1 class="h3 mb-1">Manage prefix</h1>
                </div>
            </div>

        </div>

        <section class="panel">
            <div class="panel-header">

                <input class="form-control form-control-sm table-search" type="search" placeholder="Search prefix" data-table-search="ordersTable" aria-label="Search orders">
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="ordersTable" data-searchable-table>
                    <thead>
                    <tr>
                        <th>SL no</th>
                        <th>Shipping Company</th>
                        <th>Prefix</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prefixes as $prefix)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>{{$prefix->shipping_company}}</td>
                            <td>{{$prefix->prefix}}</td>
                            <td class="text-end">
                                <a href="{{route('edit.prefix',['id'=>$prefix->id])}}"><button class="btn btn-light btn-sm" type="button">Edit</button></a>
                                <a href="{{route('delete.prefix',['id'=>$prefix->id])}}"><button class="btn btn-danger btn-sm" type="button"
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