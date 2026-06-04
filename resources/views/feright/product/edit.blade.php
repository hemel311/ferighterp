@extends('feright.master')

@section('title')
    Edit Product
@endsection

@section('body')

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h4>Edit Product</h4>
            </div>

            <div class="card-body">

                <form action="{{ route('update.product',$product->id) }}"
                      method="POST">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Product Name
                            </label>

                            <input type="text"
                                   name="product_name"
                                   class="form-control"
                                   value="{{ old('product_name',$product->product_name) }}">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                HS Code
                            </label>

                            <input type="text"
                                   name="hs_code"
                                   class="form-control"
                                   value="{{ old('hs_code',$product->hs_code) }}">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old('description',$product->description) }}</textarea>

                    </div>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Product
                    </button>

                    <a href="{{ route('manage.product') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection