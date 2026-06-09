


<button type="button"
        id="addRow"
        class="btn btn-success mb-3">
    Add Product
</button>
<div class="table-responsive">


    <table class="table table-bordered">

        <thead>

        <tr>

            <th>Turkish Name</th>

            <th>English Name</th>

            @for($i=1; $i <= $containers; $i++)

                <th>CONT {{ $i }}</th>

            @endfor

            <th>Invoice Qty</th>

            <th>Original Price</th>

            <th>Item Price</th>
            <th>Direct USD</th>
            <th>TL/USD</th>

            <th>Shipping Additional</th>

            <th>CIF Price</th>

            <th>TL Total</th>
            <th>Action</th>

        </tr>

        </thead>

        <tbody id="productTableBody">

        </tbody>

    </table>

</div>

<script>

    $(document).ready(function(){

        let rowIndex = 0;

        function getTotalInvoiceQty()
        {
            let totalQty = 0;

            $('.product-row').each(function(){

                totalQty += parseFloat(
                    $(this).find('.invoice-qty').val()
                ) || 0;

            });

            return totalQty;
        }

        function calculateRow(row)
        {
            let invoiceQty = 0;

            row.find('.cont-qty').each(function(){

                invoiceQty += parseFloat($(this).val()) || 0;

            });

            row.find('.invoice-qty').val(invoiceQty);
            row.find('.invoice-qty-hidden').val(invoiceQty);

            let originalPrice =
                parseFloat(
                    row.find('.original-price').val()
                ) || 0;

            let percentage =
                parseFloat(
                    $('#percentage').val()
                ) || 0;

            let itemPrice = originalPrice;

            if($('#add_percentage').is(':checked'))
            {
                itemPrice =
                    originalPrice +
                    (
                        originalPrice *
                        percentage / 100
                    );
            }

            row.find('.item-price')
                .val(itemPrice);
            row.find('.item-price-hidden')
                .val(itemPrice);

            let tcmb =
                parseFloat($('#tcmb').val()) || 0;

            let tlUsd = 0;

            let isDirectUsd =
                row.find('.direct-usd').is(':checked');

            if(isDirectUsd)
            {
                tlUsd = itemPrice;
            }
            else
            {
                if(tcmb > 0)
                {
                    tlUsd = itemPrice / tcmb;
                }
            }

            row.find('.tl-usd')
                .val(tlUsd);

            row.find('.tl-usd-hidden')
                .val(tlUsd);

            let shippingCost =
                parseFloat(
                    $('#shipping_cost').val()
                ) || 0;

            let totalInvoiceQty =
                getTotalInvoiceQty();

            let shippingAdditional = 0;

            if(totalInvoiceQty > 0)
            {
                shippingAdditional =
                    shippingCost /
                    totalInvoiceQty;
            }

            row.find('.shipping-additional')
                .val(
                    shippingAdditional
                );

            row.find('.shipping-additional-hidden')
                .val(
                    shippingAdditional
                );

            let cifPrice =
                tlUsd +
                shippingAdditional;

            row.find('.cif-price')
                .val(
                    cifPrice
                );

            row.find('.cif-price-hidden')
                .val(
                    cifPrice
                );

            let tlTotal =
                itemPrice *
                invoiceQty;

            row.find('.tl-total')
                .val(
                    tlTotal
                );

            row.find('.tl-total-hidden')
                .val(
                    tlTotal
                );
        }

        function recalculateAllRows()
        {
            $('.product-row').each(function(){

                calculateRow($(this));

            });
        }

        /*
        =====================================
        ADD PRODUCT ROW
        =====================================
        */

        $('#addRow').click(function(){

            let html = `
            <tr class="product-row">

                <td>
                    <input type="text"
                           name="turkish_name[]"
                           class="form-control">
                </td>

                <td>
                    <input type="text"
                           name="english_name[]"
                           class="form-control">
                </td>

                @for($i=1; $i <= $containers; $i++)

                <td>
                    <input type="number"
                           min="0"
                           value="0"
                           class="form-control cont-qty"
                           name="containers[${rowIndex}][CONT {{ $i }}]">
                </td>

                @endfor

                <td>
                    <input type="number"
                           readonly
                           class="form-control invoice-qty">

                    <input type="hidden"
                           name="invoice_qty[]"
                           class="invoice-qty-hidden">
                </td>

                <td>
                    <input type="number"
                           step="any"
                           name="original_price[]"
                           class="form-control original-price">
                </td>

                <td>
                    <input type="number"
       readonly
       class="form-control item-price">

<input type="hidden"
       name="item_price[]"
       class="item-price-hidden">
                </td>

                <td class="text-center">

    <input type="checkbox"
           class="direct-usd">

    <input type="hidden"
           name="direct_usd[]"
           class="direct-usd-hidden"
           value="0">

</td>

<td>
    <input type="number"
           readonly
           class="form-control tl-usd">

    <input type="hidden"
           name="tl_usd[]"
           class="tl-usd-hidden">
</td>

                <td>
                    <input type="number"
                           readonly
                           class="form-control shipping-additional">

                    <input type="hidden"
                           name="shipping_additional[]"
                           class="shipping-additional-hidden">
                </td>

                <td>
                    <input type="number"
                           readonly
                           class="form-control cif-price">

                    <input type="hidden"
                           name="cif_price[]"
                           class="cif-price-hidden">
                </td>

                <td>
                    <input type="number"
                           readonly
                           class="form-control tl-total">

                    <input type="hidden"
                           name="tl_total[]"
                           class="tl-total-hidden">
                </td>

                <td>
                    <button type="button"
                            class="btn btn-danger removeRow">
                        X
                    </button>
                </td>

            </tr>
        `;

            $('#productTableBody')
                .append(html);

            rowIndex++;
            recalculateAllRows();

        });

        /*
        =====================================
        REMOVE ROW
        =====================================
        */

        $(document).on(
            'click',
            '.removeRow',
            function(){

                $(this)
                    .closest('tr')
                    .remove();

                recalculateAllRows();

            }
        );

        /*
        =====================================
        RECALCULATE
        =====================================
        */

        $(document).on(
            'keyup change',
            '.cont-qty,.original-price,#tcmb,#shipping_cost,#percentage,#add_percentage',
            function(){

                recalculateAllRows();

            }
        );
        $(document).on(
            'change',
            '.direct-usd',
            function(){

                let row =
                    $(this).closest('tr');

                row.find('.direct-usd-hidden')
                    .val(
                        $(this).is(':checked')
                            ? 1
                            : 0
                    );

                recalculateAllRows();

            }
        );
    });

</script>