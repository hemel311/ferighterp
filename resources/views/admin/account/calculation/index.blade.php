@extends('admin.master')

@section('title')
    Calculation List
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            Calculation List
        </h3>

        <a href="{{ route('account.calculation.admin.create') }}"
           class="btn btn-primary">
            Create Calculation
        </a>
    </div>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                All Calculations
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

                        <th>#</th>

                        <th>Booking Number</th>

                        <th>TCMB</th>

                        <th>Shipping Cost</th>

                        <th>Percentage</th>

                        <th>Products</th>

                        <th>Created</th>

                        <th width="250">
                            Action
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($calculations as $key => $calculation)

                        <tr>

                            <td>
                                {{ $key + 1 }}
                            </td>

                            <td>
                                {{ $calculation->shipment->booking_number ?? '' }}
                            </td>

                            <td>
                                {{ number_format($calculation->tcmb,4) }}
                            </td>

                            <td>
                                {{ number_format($calculation->shipping_cost,2) }}
                            </td>

                            <td>
                                {{ $calculation->percentage ?? 0 }} %
                            </td>

                            <td>
                                {{ $calculation->items->count() }}
                            </td>

                            <td>
                                {{ $calculation->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <a href="{{ route('account.calculation.admin.show',$calculation->id) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('account.calculation.admin.edit',$calculation->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="{{ route('account.calculation.admin.exportExcel',$calculation->id) }}"
                                   class="btn btn-success btn-sm">
                                    Excel
                                </a>

                                <form action="{{ route('account.calculation.admin.delete',$calculation->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete Calculation?')">
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="text-center">

                                No Calculation Found

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $calculations->links() }}

            </div>

        </div>

    </div>

@endsection