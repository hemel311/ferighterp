@extends('feright.master')

@section('title')
    Create US Packing List
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            Create US Packing List
        </h3>
    </div>
        <form method="POST"
              action="{{ route('uspl.update',$packingList->id) }}"
              id="packingListForm">

            @csrf

            <input type="hidden"
                   name="container_upload_id"
                   value="{{ $packingList->container_upload_id }}">

            <input type="hidden"
                   name="force_submit"
                   id="force_submit"
                   value="0">

        {{-- Header Information --}}
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Booking Number</label>

                        <input type="text"
                               class="form-control"
                               value="{{ $packingList->container->booking_number }}"
                               readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Container Number</label>

                        <input type="text"
                               class="form-control"
                               value="{{ $packingList->container->container_number }}"
                               readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>VGM Gross Weight</label>

                        <input type="text"
                               id="vgm_gross_weight"
                               class="form-control"
                               value="{{ $packingList->container->vgmInfo->gross_weight ?? 0 }}"
                               readonly>
                    </div>

                </div>

            </div>

        </div>

        {{-- Product Section --}}
        <div class="card mt-3">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Products
                    </h5>

                    <button type="button"
                            class="btn btn-primary btn-sm"
                            id="addRow">
                        Add Product
                    </button>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped"
                           id="productTable">

                        <thead class="bg-dark text-white">

                        <tr>

                            <th style="min-width:180px">
                                Product Name
                            </th>
                            <th style="min-width:180px">
                                Warehouse Code
                            </th>

                            <th style="width:100px">
                                Pallets
                            </th>

                            <th style="width:100px">
                                Packages
                            </th>
                            <th style="width:100px">
                                Quantity Item per Palet/Pack & kg
                            </th>

                            <th style="width:120px">
                                Total KG
                            </th>

                            <th style="width:120px">
                                Gross Weight
                            </th>

                            <th style="width:120px">
                                Pallet/Pack KG
                            </th>

                            <th style="width:120px">
                                Total Quantity
                            </th>
                            <th style="width:100px">
                                Manual Qty
                            </th>

                            <th style="width:80px">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody id="productBody">

                        @foreach($packingList->products as $key => $product)

                            <tr>

                                <td>
                                    <input type="text"
                                           name="items[{{ $key }}][product_name]"
                                           value="{{ $product->product_name }}"
                                           class="form-control"
                                           required>
                                </td>

                                <td>
                                    <input type="text"
                                           name="items[{{ $key }}][warehouse_code]"
                                           value="{{ $product->warehouse_code }}"
                                           class="form-control">
                                </td>

                                <td>
                                    <input type="number"
                                           name="items[{{ $key }}][total_pallets]"
                                           value="{{ $product->total_pallets }}"
                                           class="form-control pallets calc">
                                </td>

                                <td>
                                    <input type="number"
                                           name="items[{{ $key }}][total_packages]"
                                           value="{{ $product->packages }}"
                                           class="form-control packages calc">
                                </td>
                                <td>
                                    <input type="text"
                                           name="items[{{ $key }}][qty_per_pallet]"
                                           value="{{ $product->qty_per_pallet }}"
                                           class="form-control calc">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="items[{{ $key }}][net_weight]"
                                           value="{{ $product->total_kg }}"
                                           class="form-control net-weight calc">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="items[{{ $key }}][gross_weight]"
                                           value="{{ $product->gross_weight }}"
                                           class="form-control gross-weight calc">
                                </td>

                                <td>
                                    <input type="text"
                                           value="{{ $product->pallet_pack_kg }}"
                                           class="form-control pallet-pack-kg calc"
                                           readonly>
                                </td>

                                <td>
                                    <input type="number"
                                           name="items[{{ $key }}][item_quantity]"
                                           value="{{ $product->total_item_qty }}"
                                           class="form-control quantity"
                                           readonly>
                                </td>
                                <td class="text-center">

                                    <input type="checkbox"
                                           class="manual-quantity">

                                <td>
                                    <button type="button"
                                            class="btn btn-danger btn-sm removeRow">
                                        ×
                                    </button>
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        {{-- Summary --}}
        <div class="card mt-3">

            <div class="card-header">
                <h5 class="mb-0">
                    Summary
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md mb-3">

                        <label class="form-label">
                            Total Net Weight
                        </label>

                        <input type="text"
                               id="total_net_weight"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md mb-3">

                        <label class="form-label">
                            Total Gross Weight
                        </label>

                        <input type="text"
                               id="total_gross_weight"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md mb-3">

                        <label class="form-label">
                            Total Pallets
                        </label>

                        <input type="text"
                               id="total_pallets"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md mb-3">

                        <label class="form-label">
                            Total Packages
                        </label>

                        <input type="text"
                               id="total_packages"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md mb-3">

                        <label class="form-label">
                            Total Pieces
                        </label>

                        <input type="text"
                               id="total_pieces"
                               class="form-control"
                               readonly>

                    </div>

                </div>

            </div>

        </div>

        {{-- Buttons --}}
        <div class="text-end mt-3">

            <button type="reset"
                    class="btn btn-danger">
                Reset
            </button>

            <button type="submit"
                    name="action"
                    value="draft"
                    class="btn btn-warning">
                Save Draft
            </button>

            <button type="submit"
                    name="action"
                    value="submit"
                    class="btn btn-success"
                    id="submitBtn">
                Submit
            </button>


        </div>

    </form>

@endsection
@push('js')
    <script>

        let rowIndex = {{ count($packingList->products) }};

        $('#addRow').click(function(){

            let row = `
    <tr>

        <td>
            <input type="text"
                   name="items[${rowIndex}][product_name]"
                   class="form-control" required>
        </td>
        <td>
    <input type="text"
           name="items[${rowIndex}][warehouse_code]"
           class="form-control">
    </td>
        <td>
            <input type="number"
       name="items[${rowIndex}][total_pallets]"
       class="form-control pallets calc"
       min="0">
        </td>

        <td>
            <input type="number"
                   name="items[${rowIndex}][total_packages]"
                   class="form-control packages calc"
                   min="0">
        </td>
        <td>
            <input type="text"
       name="items[${rowIndex}][qty_per_pallet]"
       class="form-control">
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${rowIndex}][net_weight]"
                   class="form-control net-weight calc">
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="items[${rowIndex}][gross_weight]"
                   class="form-control gross-weight calc">
        </td>

        <td>
            <input type="text"
                   class="form-control pallet-pack-kg"
                   readonly>
        </td>

       <td>
    <input type="number"
           name="items[${rowIndex}][item_quantity]"
           class="form-control quantity"
           readonly>
</td>
        <td class="text-center">

    <input type="checkbox"
           class="manual-quantity">

</td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm removeRow">
                ×
            </button>
        </td>

    </tr>
    `;

            $('#productBody').append(row);

            rowIndex++;

        });

        $(document).on('click','.removeRow',function(){

            if($('#productBody tr').length > 1)
            {
                $(this).closest('tr').remove();

                calculateTotals();
            }
            else
            {
                alert('At least one product is required');
            }

        });

        function calculateTotals()
        {
            let totalNetWeight = 0;
            let totalGrossWeight = 0;
            let totalPallets = 0;
            let totalPackages = 0;
            let totalPieces = 0;

            $('#productBody tr').each(function(){

                let row = $(this);

                let pallets =
                    parseFloat(row.find('.pallets').val()) || 0;

                let packages =
                    parseFloat(row.find('.packages').val()) || 0;

                let netWeight =
                    parseFloat(row.find('.net-weight').val()) || 0;

                let grossWeight =
                    parseFloat(row.find('.gross-weight').val()) || 0;

                let quantityPerUnit =
                    parseFloat(
                        row.find('input[name*="[qty_per_pallet]"]').val()
                    ) || 0;

                /*
                ------------------------------------
                AUTO TOTAL QUANTITY
                ------------------------------------
                */

                let quantityField =
                    row.find('.quantity');

                let manualQuantity =
                    row.find('.manual-quantity').is(':checked');

                let quantity =
                    parseFloat(quantityField.val()) || 0;

                if(!manualQuantity)
                {
                    quantityField.prop('readonly', true);

                    if (pallets > 0)
                    {
                        quantity = pallets * quantityPerUnit;
                    }
                    else if (packages > 0)
                    {
                        quantity = packages * quantityPerUnit;
                    }

                    quantityField.val(quantity);
                }
                else
                {
                    quantityField.prop('readonly', false);
                }
                /*
                ------------------------------------
                TOTALS
                ------------------------------------
                */

                totalNetWeight += netWeight;
                totalGrossWeight += grossWeight;
                totalPallets += pallets;
                totalPackages += packages;
                totalPieces += quantity;

            });

            $('#total_net_weight').val(
                totalNetWeight.toFixed(2)
            );

            $('#total_gross_weight').val(
                totalGrossWeight.toFixed(2)
            );

            $('#total_pallets').val(
                totalPallets
            );

            $('#total_packages').val(
                totalPackages
            );

            $('#total_pieces').val(
                totalPieces
            );
        }


        $(document).on('keyup change','.calc',function(){

            calculateTotals();

        });

        $('#submitBtn').click(function(e){

            e.preventDefault();

            let vgmGrossWeight =
                parseFloat($('#vgm_gross_weight').val()) || 0;

            let plGrossWeight =
                parseFloat($('#total_gross_weight').val()) || 0;
            let invalidRow = 0;

            $('#productBody tr').each(function(index){

                let netWeight =
                    parseFloat($(this).find('.net-weight').val()) || 0;

                let grossWeight =
                    parseFloat($(this).find('.gross-weight').val()) || 0;

                if(netWeight > grossWeight)
                {
                    invalidRow = index + 1;
                    return false;
                }

            });
            if(invalidRow > 0)
            {
                Swal.fire({
                    icon:'error',
                    title:'Validation Error',
                    text:'Row ' + invalidRow + ' Net Weight cannot be greater than Gross Weight'
                });

                return false;
            }



            // PL > VGM
            if(plGrossWeight > vgmGrossWeight)
            {
                Swal.fire({
                    icon:'error',
                    title:'Validation Error',
                    text:'PL Gross Weight is greater than VGM Gross Weight'
                });

                return false;
            }

            // PL < VGM
            if(plGrossWeight < vgmGrossWeight)
            {
                Swal.fire({
                    title:'Confirmation',
                    text:'PL Gross Weight is less than VGM Gross Weight. Do you want to submit?',
                    icon:'warning',
                    showCancelButton:true,
                    confirmButtonText:'Yes Submit',
                    cancelButtonText:'No'
                }).then((result)=>{

                    if(result.isConfirmed)
                    {
                        $('#force_submit').val(1);

                        $('#packingListForm').submit();
                    }

                });

                return false;
            }

            // PL == VGM
            $('#packingListForm').submit();

        });

        $('textarea').on('focus', function() {
            this.setSelectionRange(0, 0);
        });

        $(document).on('keyup change','.calc',function(){

            calculateTotals();

        });

        $(document).on(
            'change',
            '.manual-quantity',
            function(){

                calculateTotals();

            }
        );

        $(document).on(
            'keyup change',
            '.quantity',
            function(){

                let row = $(this).closest('tr');

                if(row.find('.manual-quantity').is(':checked'))
                {
                    calculateTotals();
                }

            }
        );

        $(document).ready(function () {

            calculateTotals();

        });

    </script>
@endpush