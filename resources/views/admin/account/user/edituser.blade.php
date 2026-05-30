@extends("admin.master")
@section('title')
    Edit Accountant
@endsection
@section('body')
    <div class="card">
        <div class="card-header">
            <h4>Edit Accountant</h4>
        </div>

        <div class="card-body">
            <form action="{{route('update.accountant',['id'=>$accountant->id])}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Full Name" value="{{$accountant->name}}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email Address" required value="{{$accountant->email}}">
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password </label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password">
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password </label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" >
                    </div>

                    <!-- Profile Image -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Image</label><br>
                        <img src="{{asset($accountant->image)}}" alt="{{$accountant->name}}" class="product-thumb">
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                </div>

                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Update Accountant
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection