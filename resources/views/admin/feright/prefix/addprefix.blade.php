@extends("admin.master")
@section('title')
    Add MBL Prefix
@endsection
@section('body')
    <div class="card">
        <div class="card-header">
            <h4>Add MBL Prefix</h4>
        </div>

        <div class="card-body">
            <form action="{{route('create.prefix')}}" method="POST" >
                @csrf

                <div class="row">

                    <!-- Shipping Company -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Shipping Company <span class="text-danger">*</span></label>
                        <input type="text" name="shipping_company" class="form-control" placeholder="Enter shipping Company Name" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prefix <span class="text-danger">*</span></label>
                        <input type="text" name="prefix" class="form-control" placeholder="Enter prefix" required>
                    </div>

                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save MBl Prefix
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection