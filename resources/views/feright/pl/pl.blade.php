@extends('feright.master')

@section('title')
    TR Packing List
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            TR Packing List
        </h3>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Container List</h5>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Booking Number</label>

                        <select class="form-control select2" id="booking_number">
                            <option value="">Select Booking Number</option>

                            @foreach($shipments as $shipment)
                                <option value="{{ $shipment->booking_number }}">
                                    {{ $shipment->booking_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                    <tr>
                        <th>Container Number</th>
                        <th>Container Serial</th>
                        <th>VGM Gross Weight</th>
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tbody id="containerTableBody">

                    <tr>
                        <td colspan="8" class="text-center">
                            Select a Booking Number
                        </td>
                    </tr>

                    </tbody>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

@endsection
@push('js')

    <script>

        $('.select2').select2({
            width:'100%'
        });

    </script>
    <script>
        $('#booking_number').change(function(){

            let bookingNumber = $(this).val();

            if(bookingNumber == '')
            {
                $('#containerTableBody').html('');
                return;
            }

            $.ajax({
                url: '/pl/containers/' + bookingNumber,
                type: 'GET',

                success:function(response){

                    let rows = '';

                    $.each(response,function(index,item){
                        let status = 'Not Created';
                        let action = '';

                        if(item.tr_packing_lists.length > 0)
                        {
                            let pl = item.tr_packing_lists[0];

                            if(pl.status === 'draft')
                            {
                                status = '<span class="badge bg-warning">Draft</span>';

                                action = `
            <a href="/edit/${pl.id}""
               class="btn btn-warning btn-sm">
                Edit Draft
            </a>
        `;
                            }
                            else
                            {
                                status = '<span class="badge bg-success">Submitted</span>';

                                action = `
            <a href="/tr-packing-list/${pl.id}/preview"
               class="btn btn-success btn-sm">
                View
            </a>
        `;
                            }
                        }
                        else
                        {
                            status = '<span class="badge bg-secondary">Not Created</span>';

                            action = `
        <a href="/tr-packing-list/create/${item.id}"
           class="btn btn-primary btn-sm">
            Create PL
        </a>
    `;
                        }

                        rows += `
<tr>

    <td>${item.container_number}</td>

    <td>${item.container_serial}</td>

    <td>${item.vgm_info ? item.vgm_info.gross_weight : 'N/A'}</td>

    <td>${status}</td>

    <td>${action}</td>

</tr>`;

                    });

                    $('#containerTableBody').html(rows);
                }
            });

        });

    </script>

@endpush
