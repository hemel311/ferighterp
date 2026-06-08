@extends('feright.master')

@section('title')
    Edit Container
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header bg-primary text-white">
                <h4>Edit Container</h4>
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

                <form action="{{ route('container.update',$container->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Container Number
                            </label>

                            <input type="text"
                                   name="container_number"
                                   id="container_number"
                                   class="form-control"
                                   value="{{ old('container_number',$container->container_number) }}">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Seal Number
                            </label>

                            <input type="text"
                                   name="seal_number"
                                   id="seal_number"
                                   class="form-control"
                                   value="{{ old('seal_number',$container->seal_number) }}">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Current Container Image
                            </label>

                            <div class="mb-2">

                                @if($container->container_image)

                                    <img src="{{ asset($container->container_image) }}"
                                         class="img-thumbnail"
                                         style="max-height:250px">

                                @else

                                    <div class="alert alert-warning mb-0">
                                        No Container Image Found
                                    </div>

                                @endif

                            </div>

                            <input type="file"
                                   name="container_image"
                                   id="container_image"
                                   class="form-control"
                                   accept="image/*">

                            <small class="text-muted">
                                Upload new image to replace current image and run OCR.
                            </small>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Current Seal Image
                            </label>

                            <div class="mb-2">

                                @if($container->seal_image)

                                    <img src="{{ asset($container->seal_image) }}"
                                         class="img-thumbnail"
                                         style="max-height:250px">

                                @else

                                    <div class="alert alert-warning mb-0">
                                        No Seal Image Found
                                    </div>

                                @endif

                            </div>

                            <input type="file"
                                   name="seal_image"
                                   id="seal_image"
                                   class="form-control"
                                   accept="image/*">

                            <small class="text-muted">
                                Upload new image to replace current image and run OCR.
                            </small>

                        </div>

                    </div>

                    <div id="ocrLoading"
                         class="alert alert-info d-none">

                        <i class="fa fa-spinner fa-spin"></i>
                        Reading image using OCR. Please wait...

                    </div>

                    <div class="mt-3">

                        <button type="submit"
                                name="action"
                                value="draft"
                                class="btn btn-warning">

                            Save Draft

                        </button>

                        <button type="submit"
                                name="action"
                                value="submit"
                                class="btn btn-success">

                            Final Submit

                        </button>

                        <a href="{{ route('container.manage') }}"
                           class="btn btn-secondary">

                            Back

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('js')

    <script>

        $(document).ready(function(){

            function runOCR()
            {
                let containerImage =
                    $('#container_image')[0].files[0];

                let sealImage =
                    $('#seal_image')[0].files[0];

                if(!containerImage && !sealImage)
                {
                    return;
                }

                let formData = new FormData();

                if(containerImage)
                {
                    formData.append(
                        'container_image',
                        containerImage
                    );
                }

                if(sealImage)
                {
                    formData.append(
                        'seal_image',
                        sealImage
                    );
                }

                formData.append(
                    '_token',
                    '{{ csrf_token() }}'
                );

                $('#ocrLoading')
                    .removeClass('d-none');

                $.ajax({

                    url: "{{ route('extract.ocr') }}",

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(response)
                    {
                        $('#ocrLoading')
                            .addClass('d-none');

                        if(response.success)
                        {
                            if(response.container_number)
                            {
                                $('#container_number')
                                    .val(response.container_number);
                            }

                            if(response.seal_number)
                            {
                                $('#seal_number')
                                    .val(response.seal_number);
                            }
                        }
                    },

                    error: function()
                    {
                        $('#ocrLoading')
                            .addClass('d-none');

                        alert(
                            'OCR failed. Please enter values manually.'
                        );
                    }

                });
            }

            $('#container_image').change(function(){
                runOCR();
            });

            $('#seal_image').change(function(){
                runOCR();
            });

        });

    </script>

@endpush