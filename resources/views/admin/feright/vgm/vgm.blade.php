@extends('admin.master')
@section('title')
    VGM
@endsection
@section('body')
    <div class="row mb-3">

        <div class="col-md-6">

            <label>Booking Number</label>

            <select class="form-select select2"
                    id="search_booking">

                <option value="">
                    Search Booking Number
                </option>

                @foreach($shipments as $shipment)

                    <option value="{{ $shipment->booking_number }}">
                        {{ $shipment->booking_number }}
                    </option>

                @endforeach

            </select>

        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

            <tr>
                <th>Container Serial</th>
                <th>Container Number</th>
                <th>Seal Number</th>
                <th>Status</th>
                <th width="150">Action</th>

            </tr>

            </thead>

            <tbody id="containerTable">

            </tbody>

        </table>

    </div>
@endsection

@push('js')

    <script>

        $(document).ready(function(){

            $('.select2').select2({
                placeholder: 'Search Booking Number',
                allowClear: true,
                width: '100%'
            });

        });

        $('#search_booking').change(function(){

            let booking_number = $(this).val();

            $.ajax({

                url: "{{ route('admin.vgm.search') }}",

                type: "GET",

                data: {
                    booking_number: booking_number
                },

                success:function(response){

                    let html = '';

                    $.each(response,function(index,row){

                        html += `
                <tr>

                    <td>${row.container_serial}</td>

                    <td>${row.container_number ?? ''}</td>

                    <td>${row.seal_number ?? ''}</td>
                    <td>
        ${row.vgm_info
                            ? '<span class="badge bg-success">VGM Submitted</span>'
                            : '<span class="badge bg-danger">VGM Not Submitted</span>'
                            }
    </td>


                    <td>
${
                            row.vgm_info !== null
                                ? `
        <a href="/admin/vgm/download/${row.vgm_info.id}"
           class="btn btn-info btn-sm">
            Download
        </a>

        <a href="/admin/vgm/delete/${row.vgm_info.id}"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure you want to delete this VGM?')">
            Delete
        </a>
      `
                                : `
        <a href="{{ url('/admin/vgm/create') }}/${row.id}"
           class="btn btn-primary btn-sm">
            Add VGM
        </a>
      `
                            }
</td>

                </tr>
                `;
                    });

                    $('#containerTable').html(html);

                }

            });

        });

    </script>

@endpush