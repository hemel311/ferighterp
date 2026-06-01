@extends('feright.master')

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

                    <form action="{{ route('vgm.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="container_id" value="{{ $container->id }}">

                        <div class="mb-3">
                            <label>Container Number</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $container->container_number }}"
                                   readonly>
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
        document.getElementById('vgm_weight').addEventListener('input', calculateGross);
        document.getElementById('container_weight').addEventListener('input', calculateGross);

        function calculateGross()
        {
            let vgm = parseFloat(document.getElementById('vgm_weight').value) || 0;
            let container = parseFloat(document.getElementById('container_weight').value) || 0;

            document.getElementById('gross_weight').value = (vgm - container).toFixed(2);
        }
    </script>
@endpush