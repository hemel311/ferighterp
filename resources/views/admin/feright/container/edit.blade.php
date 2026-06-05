@extends('admin.master')

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

                <form action="{{ route('admin.container.update',$container->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Container Number</label>

                            <input type="text"
                                   name="container_number"
                                   class="form-control"
                                   value="{{ $container->container_number }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Seal Number</label>

                            <input type="text"
                                   name="seal_number"
                                   class="form-control"
                                   value="{{ $container->seal_number }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Replace Container Image</label>

                            <input type="file"
                                   name="container_image"
                                   class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Replace Seal Image</label>

                            <input type="file"
                                   name="seal_image"
                                   class="form-control">
                        </div>

                    </div>

                    <button type="submit"
                            class="btn btn-success">
                        Update Container
                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection