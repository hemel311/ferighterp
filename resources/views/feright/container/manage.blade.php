@extends('feright.master')
@section('title')
    Manage Container
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

                url: "{{ route('container.search') }}",

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

                        <a href="{{ url('container/edit') }}/${row.id}"
                           class="btn btn-sm btn-warning">
                            Edit
                        </a>

                        <a href="{{ url('container/delete') }}/${row.id}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this container?')">
                            Delete
                        </a>

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