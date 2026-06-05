



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

            <th>TL/USD</th>

            <th>Shipping Additional</th>

            <th>CIF Price</th>

            <th>TL Total</th>

        </tr>

        </thead>

        <tbody>

        @foreach($products as $key => $item)

            <tr class="product-row">

                <input type="hidden"
                       name="tl_usd[]"
                       class="tl-usd-hidden">
                <input type="hidden"
                       name="shipping_additional[]"
                       class="shipping-additional-hidden">
                <input type="hidden"
                       name="cif_price[]"
                       class="cif-price-hidden">
                <input type="hidden"
                       name="tl_total[]"
                       class="tl-total-hidden">
                <input type="hidden"
                       name="price_pi_a[]"
                       class="price-pia-hidden">
                <input type="hidden"
                       name="invoice_qty[]"
                       class="invoice-qty-hidden">
                <td>

                    {{ $item->product->turkish_name ?? '' }}

                    <input type="hidden"
                           name="product_id[]"
                           value="{{ $item->product_id }}">

                </td>

                <td>
                    {{ $item->item_name }}
                </td>

                @for($i=1; $i <= $containers; $i++)

                    <td>

                        <input type="number"
                               min="0"
                               value="0"
                               class="form-control cont-qty"
                               name="containers[{{ $key }}][CONT {{ $i }}]">

                    </td>

                @endfor

                <td>

                    <input type="number"
                           readonly
                           class="form-control invoice-qty">

                </td>

                <td>

                    <input type="number"
                           step="0.01"
                           class="form-control original-price"
                           name="original_price[]">

                </td>

                <td>

                    <input type="number"
                           readonly
                           class="form-control item-price">

                </td>

                <td>

                    <input type="number"
                           readonly
                           class="form-control tl-usd">

                </td>

                <td>

                    <input type="number"
                           readonly
                           class="form-control shipping-additional">

                </td>

                <td>

                    <input type="number"
                           readonly
                           class="form-control cif-price">

                </td>

                <td>

                    <input type="number"
                           readonly
                           class="form-control tl-total">

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

<script>

    $(document).ready(function(){

        function calculateRow(row)
        {
            let invoiceQty = 0;

            row.find('.cont-qty').each(function(){

                invoiceQty += parseFloat($(this).val()) || 0;

            });

            row.find('.invoice-qty')
                .val(invoiceQty);

            row.find('.invoice-qty-hidden')
                .val(invoiceQty);

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
                .val(itemPrice.toFixed(2));

            let tcmb =
                parseFloat(
                    $('#tcmb').val()
                ) || 0;

            let tlUsd = 0;

            if(tcmb > 0)
            {
                tlUsd =
                    itemPrice / tcmb;
            }

            row.find('.tl-usd')
                .val(tlUsd.toFixed(4));

            row.find('.tl-usd-hidden')
                .val(tlUsd.toFixed(4));

            let shippingCost =
                parseFloat(
                    $('#shipping_cost').val()
                ) || 0;

            let shippingAdditional = 0;

            if(invoiceQty > 0)
            {
                shippingAdditional =
                    shippingCost / invoiceQty;
            }

            row.find('.shipping-additional')
                .val(
                    shippingAdditional.toFixed(4)
                );

            row.find('.shipping-additional-hidden')
                .val(
                    shippingAdditional.toFixed(4)
                );

            let cifPrice =
                tlUsd +
                shippingAdditional;

            row.find('.cif-price')
                .val(
                    cifPrice.toFixed(4)
                );

            row.find('.cif-price-hidden')
                .val(
                    cifPrice.toFixed(4)
                );

            let tlTotal =
                itemPrice *
                invoiceQty;

            row.find('.tl-total')
                .val(
                    tlTotal.toFixed(2)
                );

            row.find('.tl-total-hidden')
                .val(
                    tlTotal.toFixed(2)
                );
        }

        $(document).on(
            'keyup change',
            '.cont-qty,.original-price,#tcmb,#shipping_cost,#percentage,#add_percentage',
            function(){

                $('.product-row').each(function(){

                    calculateRow($(this));

                });

            }
        );

    });

</script>