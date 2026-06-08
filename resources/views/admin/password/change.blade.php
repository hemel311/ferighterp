@extends('admin.master')

@section('title')
Change Password
@endsection

@section('body')

<div class="page-header">
    <h3 class="page-title">
        Change Password
    </h3>
</div>

<div class="card">
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.password.update') }}"
              method="POST">

            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Current Password
                </label>

                <input type="password"
                       name="current_password"
                       class="form-control">

                @error('current_password')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">
                    New Password
                </label>

                <input type="password"
                       name="password"
                       class="form-control">

                @error('password')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Confirm Password
                </label>

                <input type="password"
                       name="password_confirmation"
                       class="form-control">
            </div>

            <button type="submit"
                    class="btn btn-primary">
                Change Password
            </button>

        </form>

    </div>
</div>

@endsection