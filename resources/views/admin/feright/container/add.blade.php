@extends('admin.master')
@section('title')
    Add container
@endsection
@section('body')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Container & Seal Upload</h4>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('admin.container.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Booking Number -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Booking Number
                            </label>

                            <select class="form-select select2"
                                    name="booking_number"
                                    id="booking_number"
                                    required>

                                <option value="">Search Booking Number</option>

                                @foreach($shipments as $shipment)
                                    <option value="{{ $shipment->booking_number }}"
                                            data-container="{{ $shipment->container_qty }}">
                                        {{ $shipment->booking_number }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Container Area -->
                    <div id="containerArea"></div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">
                            Save Container Details
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('js')

    <script>
        $(document).on('change', '#booking_number', function() {

            let selectedOption = this.options[this.selectedIndex];

            let totalContainer = selectedOption.getAttribute('data-container');

            console.log(totalContainer);

            let html = '';

            for(let i = 1; i <= totalContainer; i++)
            {
                html += `
<div class="card border mb-4">
    <div class="card-header">
        <h5 class="mb-0">Container ${i}</h5>
    </div>

    <div class="card-body">

        <input type="hidden"
               name="container_serial[]"
               value="${i}">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Container Photo</label>

                <input type="file"
                       name="container_images[]"
                       class="form-control container-image"
                       data-index="${i}"
                       accept="image/*">
            </div>

            <div class="col-md-6 mb-3">
                <label>Seal Photo</label>

                <input type="file"
                       name="seal_images[]"
                       class="form-control seal-image"
                       data-index="${i}"
                       accept="image/*">
            </div>

            <div class="col-md-6 mb-3">
                <label>Container Number</label>

                <input type="text"
                       name="container_number[]"
                       id="container_number_${i}"
                       class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Seal Number</label>

                <input type="text"
                       name="seal_number[]"
                       id="seal_number_${i}"
                       class="form-control">
            </div>

        </div>

        <button type="button"
                class="btn btn-primary extract-ocr"
                data-index="${i}">
            Extract Container & Seal number
        </button>

    </div>
</div>
        `;
            }

            document.getElementById('containerArea').innerHTML = html;
        });

        // OCR AJAX

        $(document).on('click','.extract-ocr',function(){

            let index = $(this).data('index');

            let containerFile =
                $('.container-image[data-index="'+index+'"]')[0].files[0];

            let sealFile =
                $('.seal-image[data-index="'+index+'"]')[0].files[0];

            if(!containerFile || !sealFile)
            {
                alert('Please select both Container and Seal images.');
                return;
            }

            let formData = new FormData();

            formData.append('container_image', containerFile);
            formData.append('seal_image', sealFile);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({

                url: "{{ route('admin.extract.ocr') }}",

                type: "POST",

                data: formData,

                processData: false,
                contentType: false,

                beforeSend:function(){
                    console.log('OCR Processing...');
                },

                success:function(response){

                    console.log(response);

                    $('#container_number_'+index)
                        .val(response.container_number);

                    $('#seal_number_'+index)
                        .val(response.seal_number);

                },

                error:function(xhr){

                    console.log(xhr.responseText);

                    alert('OCR Failed. Check console.');
                }
            });

        });
    </script>
    <script>
        $(document).ready(function(){

            $('.select2').select2({
                placeholder: 'Search Booking Number',
                allowClear: true,
                width: '100%'
            });

        });
    </script>
@endpush