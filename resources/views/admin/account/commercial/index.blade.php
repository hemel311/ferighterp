@extends('admin.master')

@section('title')
    Commercial Invoice List
@endsection

@section('body')

    <div class="page-header">

        <h3 class="page-title">
            Commercial Invoice List
        </h3>

        <a href="{{ route('account.commercial.admin.create') }}"
           class="btn btn-primary">

            Create Commercial Invoice

        </a>

    </div>

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Commercial Invoices
            </h5>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                    <tr>

                        <th width="70">
                            #
                        </th>

                        <th>
                            Booking Number
                        </th>

                        <th>
                            Export Number
                        </th>

                        <th>
                            Shipping Cost
                        </th>

                        <th>
                            Created Date
                        </th>

                        <th width="250">
                            Action
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($invoices as $key => $invoice)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $invoice->shipment->booking_number ?? '' }}
                            </td>

                            <td>
                                {{ $invoice->export_number }}
                            </td>

                            <td>
                                {{ number_format(
                                    $invoice->shipping_cost,
                                    2
                                ) }}
                            </td>

                            <td>
                                {{ $invoice->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <a href="{{ route('account.commercial.admin.show',$invoice->id) }}"
                                   class="btn btn-info btn-sm">

                                    View

                                </a>

                                <a href="{{ route('account.commercial.admin.edit',$invoice->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>
                                <a href="{{ route('account.commercial.admin.exportExcel',$invoice->id) }}"
                                   class="btn btn-success btn-sm">

                                    Excel

                                </a>
                                <a href="{{ route('account.commercial.admin.exportPdf',$invoice->id) }}"
                                   class="btn btn-danger btn-sm">

                                    PDF

                                </a>

                                <a href="{{ route('account.commercial.admin.delete',$invoice->id) }}"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure?')">

                                    Delete

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center">

                                No Commercial Invoice Found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $invoices->links() }}

            </div>

        </div>

    </div>

@endsection