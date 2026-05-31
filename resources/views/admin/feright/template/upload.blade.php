@extends('admin.master')
@section('title')
Upload Template
@endsection

@section('body')
    <form action="{{route('upload.templates')}}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Template Type <span class="text text-danger-emphasis">*</span></label>
            <input type="text" name="name" id="" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Excel File</label>
            <input type="file" name="file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">
            Upload
        </button>

    </form>
@endsection