@php
    $grandTotal = 0;
@endphp

<div class="table-responsive">

    <table class="table table-bordered">

        <thead>

        <tr>

            <th>Product Name</th>

            <th>Invoice Qty</th>

            <th>Price PI A</th>

            <th>Total Value</th>

        </tr>

        </thead>

        <tbody>

        @foreach($calculation->items as $item)

            @php

                $pricePiA = $item->direct_usd
                    ? $item->item_price
                    : $item->tl_usd;

                $totalValue =
                    $pricePiA *
                    $item->invoice_qty;

                $grandTotal +=
                    $totalValue;

            @endphp

            <tr>

                <td>
                    {{ $item->english_name }}
                </td>

                <td>
                    {{ number_format(
                        $item->invoice_qty
                    ) }}
                </td>

                <td>
                    {{ number_format(
                        $pricePiA,
                        4
                    ) }}
                </td>

                <td>
                    {{ number_format(
                        $totalValue,
                        2
                    ) }}
                </td>

            </tr>

        @endforeach

        </tbody>

        <tfoot>

        <tr>

            <th colspan="3" class="text-end">
                Grand Total
            </th>

            <th id="grandTotal">
                {{ number_format($grandTotal,2) }}
            </th>

        </tr>

        <tr>

            <th colspan="3" class="text-end">
                Shipping Cost
            </th>

            <th id="shippingCostDisplay">
                0.00
            </th>

        </tr>

        <tr>

            <th colspan="3" class="text-end">
                Final Total
            </th>

            <th id="finalTotal">
                {{ number_format($grandTotal,2) }}
            </th>

        </tr>

        </tfoot>

    </table>

</div>