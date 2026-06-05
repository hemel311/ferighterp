@extends('admin.master')

@section('title')
    Add VGM Information
@endsection

@section('body')

    <div class="row">
        <div class="col-md-8 mx-auto">

            <div class="card">
                <div class="card-header">
                    <h4>Add VGM Information</h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.vgm.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="container_id" value="{{ $container->id }}">
                        <div class="mb-3">
                            <label>Upload VGM PDF</label>
                            <input type="file"
                                   id="vgm_pdf"
                                   name="pdf_file"
                                   class="form-control"
                                   accept=".pdf">
                        </div>
                        <div class="mb-3">
                            <label>VGM Weight</label>
                            <input type="number"
                                   step="0.01"
                                   name="vgm_weight"
                                   id="vgm_weight"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Container Weight</label>
                            <input type="number"
                                   step="0.01"
                                   name="container_weight"
                                   id="container_weight"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Gross Weight</label>
                            <input type="number"
                                   step="0.01"
                                   name="gross_weight"
                                   id="gross_weight"
                                   class="form-control"
                                   readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save VGM
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function () {

            // Upload PDF and extract data
            $('#vgm_pdf').change(function () {

                let formData = new FormData();

                formData.append('pdf', this.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({

                    url: "{{ route('admin.vgm.extract') }}",

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function (res) {

                        $('#vgm_weight').val(res.vgm_weight);
                        $('#container_weight').val(res.container_weight);
                        $('#gross_weight').val(res.gross_weight);

                    },

                    error: function (xhr) {

                        console.log(xhr.responseText);

                        alert('PDF extraction failed.');

                    }

                });

            });

            // Manual calculation if values change
            $('#vgm_weight, #container_weight').on('input', function () {

                calculateGross();

            });

        });

        function calculateGross()
        {
            let vgm = parseFloat($('#vgm_weight').val()) || 0;

            let container = parseFloat($('#container_weight').val()) || 0;

            $('#gross_weight').val((vgm - container).toFixed(2));
        }

    </script>
@endpush