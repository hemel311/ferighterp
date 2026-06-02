@extends('feright.master')

@section('title')
    Manage ISF
@endsection

@section('body')
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </span>
                <div>
                    <h1 class="h3 mb-1">Manage ISF</h1>
                </div>
            </div>

            <div>
                <a href="" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add ISF
                </a>
            </div>
        </div>

        <section class="panel">

            <div class="panel-header">
                <input
                        class="form-control form-control-sm table-search"
                        type="search"
                        placeholder="Search ISF"
                        data-table-search="isfTable">
            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0"
                       id="isfTable"
                       data-searchable-table>

                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Booking Number</th>
                        <th>Container Number(s)</th>
                        <th>ETD</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($isfs as $isf)

                        <tr>

                            <td class="fw-semibold">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $isf->booking_number }}
                            </td>

                            <td>
                                {!! nl2br(e($isf->container_numbers)) !!}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($isf->etd)->format('d-M-Y') }}
                            </td>
                            <td>
                                @if($isf->status == 'Draft')
                                    <span class="badge bg-warning text-dark">
                                        Draft
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Submitted
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">

                                @if($isf->status == 'Draft')

                                    <a href="{{route('isf.edit',['id'=>$isf->id])}}">
                                        <button class="btn btn-light btn-sm"
                                                type="button">
                                            Edit
                                        </button>
                                    </a>

                                @else

                                    <a href="{{route('isf.preview',['id'=>$isf->id])}}">
                                        <button class="btn btn-success btn-sm"
                                                type="button">
                                            View ISF
                                        </button>
                                    </a>

                                @endif
                                    <a href="{{route('isf.delete',['id'=>$isf->id])}}">
                                        <button class="btn btn-danger btn-sm"
                                                type="submit"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </a>




                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No ISF Found
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </div>
@endsection