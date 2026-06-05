@extends('admin.master')

@section('title')
    Create TR Packing List
@endsection

@section('body')

    <div class="page-header">
        <h3 class="page-title">
            Create TR Packing List
        </h3>
    </div>

    <form method="POST"
          action="{{ route('trpl.admin.store') }}"
          id="packingListForm">

        @csrf

        <input type="hidden"
               name="container_upload_id"
               value="{{ $container->id }}">

        <input type="hidden"
               name="vgm_info_id"
               value="{{ $container->vgmInfo->id ?? '' }}">
        <input type="hidden"
               name="force_submit"
               id="force_submit"
               value="0">

        {{-- Header Information --}}
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Booking Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $container->booking_number }}"
                               readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Container Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $container->container_number }}"
                               readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            VGM Gross Weight
                        </label>

                        <input type="text"
                               id="vgm_gross_weight"
                               class="form-control"
                               value="{{ $container->vgmInfo->gross_weight ?? 0 }}"
                               readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Date
                        </label>

                        <input type="date"
                               name="pl_date"
                               class="form-control"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            From
                        </label>

                        <textarea name="from_location"
                                  rows="5"
                                  class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            To
                        </label>

                        <textarea name="to_location"
                                  rows="5"
                                  class="form-control"></textarea>
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
                            <th style="width:120px">
                                Manual Entry
                            </th>
                            <th style="width:120px">
                                Special Product
                            </th>

                            <th style="width:80px">
                                Action
                            </th>

                        </tr>

                        </thead>

                        <tbody id="productBody">

                        <tr>

                            <td>
                                <input type="text"
                                       name="items[0][product_name]"
                                       class="form-control" required>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[0][total_pallets]"
                                       class="form-control pallets calc"
                                       min="0">
                            </td>

                            <td>
                                <input type="number"
                                       name="items[0][total_packages]"
                                       class="form-control packages calc"
                                       min="0">
                            </td>
                            <td>
                                <input type="number"
                                       name="items[0][quantity_per_unit]"
                                       class="form-control quantity-per-unit calc"
                                       min="0">
                            </td>


                            <td>
                                <input type="number"
                                       step="0.01"
                                       name="items[0][net_weight]"
                                       class="form-control net-weight calc">
                            </td>

                            <td>
                                <input type="number"
                                       step="0.01"
                                       name="items[0][gross_weight]"
                                       class="form-control gross-weight calc">
                            </td>

                            <td>
                                <input type="text"
                                       name="items[0][pallet_pack_kg]"
                                       class="form-control pallet-pack-kg"
                                       readonly>
                            </td>

                            <td>
                                <input type="number"
                                       name="items[0][item_quantity]"
                                       class="form-control quantity  calc" readonly>
                            </td>
                            <td>
                                <input type="checkbox"
                                       class="manual-quantity">
                            </td>

                            <td>

                                <input type="checkbox"
                                       class="special-product"
                                       name="items[0][is_special_product]"
                                       value="1">

                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-danger btn-sm removeRow">
                                    ×
                                </button>
                            </td>

                        </tr>

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

        let rowIndex = 1;

        $('#addRow').click(function(){

            let row = `
    <tr>

        <td>
            <input type="text"
                   name="items[${rowIndex}][product_name]"
                   class="form-control" required>
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
        <input type="number"
                                   name="items[${rowIndex}][quantity_per_unit]"
                                   class="form-control quantity-per-unit calc"
                                   min="0">
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
       name="items[${rowIndex}][pallet_pack_kg]"
       class="form-control pallet-pack-kg"
       readonly>
        </td>

        <td>
            <input type="number"
                   name="items[${rowIndex}][item_quantity]"
                   class="form-control quantity calc" readonly>
        </td>
         <td>
                                <input type="checkbox"
                                       class="manual-quantity">
                            </td>
                            <td>

    <input type="checkbox"
           class="special-product"
           name="items[${rowIndex}][is_special_product]"
           value="1">

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
        $(document).on('change','.special-product',function()
        {
            let row = $(this).closest('tr');
            let quantityField = row.find('.quantity');
            let palletPackField = row.find('.pallet-pack-kg');
            let quantityPerUnitField = row.find('.quantity-per-unit');

            if($(this).is(':checked'))
            {
                quantityField.prop('readonly',false); palletPackField.prop('readonly',false);
                quantityPerUnitField .attr('type','text') .removeClass('calc') .val('');
            }
            else {
                quantityField.prop('readonly',true);
                palletPackField.prop('readonly',true);
                quantityPerUnitField .attr('type','number') .addClass('calc') .val(''); calculateTotals();
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
                        row.find('input[name*="[quantity_per_unit]"]').val()
                    ) || 0;
                let quantityField =
                    row.find('.quantity');
                let manualQuantity =
                    row.find('.manual-quantity').is(':checked');
                let specialProduct =
                    row.find('.special-product').is(':checked');
                let quantity =
                    parseFloat(quantityField.val()) || 0;
// Normal Product Auto Calculation
                if(!manualQuantity && !specialProduct)
                {
                    if(pallets > 0)
                    {
                        quantity = pallets * quantityPerUnit;
                    }
                    else if(packages > 0)
                    {

                        quantity = packages * quantityPerUnit;
                    }
                    quantityField.val(quantity);
                }
                totalNetWeight += netWeight;
                totalGrossWeight += grossWeight;
                totalPallets += pallets;
                totalPackages += packages;
                totalPieces += quantity;
// Auto Pallet/Pack KG
                if(!specialProduct)
                {
                    let palletPackKg = 0;
                    if(pallets > 0)
                    {
                        palletPackKg = netWeight / pallets;
                    }
                    else if(packages > 0)
                    {
                        palletPackKg = netWeight / packages;
                    }
                    row.find('.pallet-pack-kg')
                        .val(
                            palletPackKg > 0
                                ? palletPackKg.toFixed(2)
                                : ''
                        );
                }
            });
            $('#total_net_weight').val(totalNetWeight.toFixed(2));
            $('#total_gross_weight').val(totalGrossWeight.toFixed(2));
            $('#total_pallets').val(totalPallets);
            $('#total_packages').val(totalPackages);
            $('#total_pieces').val(totalPieces);

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

        $(document).on('change','.manual-quantity',function(){

            let row = $(this).closest('tr');

            let quantityField = row.find('.quantity');

            if($(this).is(':checked'))
            {
                quantityField.prop('readonly',false);
            }
            else
            {
                quantityField.prop('readonly',true);

                let pallets =
                    parseFloat(row.find('.pallets').val()) || 0;

                let packages =
                    parseFloat(row.find('.packages').val()) || 0;

                let quantityPerUnit =
                    parseFloat(
                        row.find('input[name*="[quantity_per_unit]"]').val()
                    ) || 0;

                let quantity = 0;

                let isSpecial =
                    $(this)
                        .find('.special-product')
                        .is(':checked');

                if(!isSpecial)
                {
                    if(pallets > 0)
                    {
                        quantity = pallets * quantityPerUnit;
                    }
                    else if(packages > 0)
                    {
                        quantity = packages * quantityPerUnit;
                    }

                    quantityField.val(quantity);
                }

                calculateTotals();
            }

        });

    </script>
@endpush