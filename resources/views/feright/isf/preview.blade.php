@extends('feright.master')

@section('title')
    ISF Preview
@endsection

@section('body')

    <div class="row">

        <div class="col-md-12 mb-3">

            <a href="{{route('isf.edit',['id'=>$isf->id])}}"
               class="btn btn-warning">
                Edit
            </a>

            <a href="{{route('isf.export.excel',['id'=>$isf->id])}}}"
               class="btn btn-success">
                Export Excel
            </a>
            <a href="{{route('isf.pdf',['id'=>$isf->id])}}"
               class="btn btn-primary">
                Export PDF
            </a>

        </div>

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h4>ISF Preview</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="25%">Booking Number</th>
                            <td>{{ $isf->booking_number }}</td>
                        </tr>

                        <tr>
                            <th>HBL Number</th>
                            <td>{{ $isf->hbl }}</td>
                        </tr>

                        <tr>
                            <th>MBL Number</th>
                            <td>{{ $isf->mbl }}</td>
                        </tr>
                        <tr>
                            <th>From</th>
                            <td>{{ $isf->from_address }}</td>
                        </tr>

                        <tr>
                            <th>To</th>
                            <td>{{ $isf->to_address }}</td>
                        </tr>
                        <tr>
                            <th>Manufacturer</th>
                            <td>{{ $isf->manufacturer }}</td>
                        </tr>

                        <tr>
                            <th>ETD</th>
                            <td>{{ $isf->etd }}</td>
                        </tr>

                        <tr>
                            <th>Port Of Departure</th>
                            <td>{{ $isf->port_of_loading }}</td>
                        </tr>

                        <tr>
                            <th>Port Of Unloading</th>
                            <td>{{ $isf->port_of_discharge }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if($isf->status == 'Draft')
                                    <span class="badge bg-warning">
                                    Draft
                                </span>
                                @else
                                    <span class="badge bg-success">
                                    Submitted
                                </span>
                                @endif
                            </td>
                        </tr>

                    </table>

                    <hr>

                    <div class="row">

                        <div class="col-md-6">

                            <h5>Product Name</h5>

                            <textarea class="form-control"
                                      rows="8"
                                      readonly>{{ $isf->product_name }}</textarea>

                        </div>

                        <div class="col-md-6">

                            <h5>HS Code</h5>

                            <textarea class="form-control"
                                      rows="8"
                                      readonly>{{ $isf->hs_code }}</textarea>

                        </div>

                    </div>

                    <br>

                    <div class="row">

                        <div class="col-md-12">

                            <h5>Container Numbers</h5>

                            <textarea class="form-control"
                                      rows="6"
                                      readonly>{{ $isf->container_numbers }}</textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection