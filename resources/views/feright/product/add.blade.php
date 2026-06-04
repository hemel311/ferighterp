@extends('feright.master')

@section('title')
    Add Product
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4>Add Product</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('create.product') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Product Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="product_name"
                                   class="form-control"
                                   value="{{ old('product_name') }}">

                            @error('product_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                HS Code <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="hs_code"
                                   class="form-control"
                                   value="{{ old('hs_code') }}">

                            @error('hs_code')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Product
                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection