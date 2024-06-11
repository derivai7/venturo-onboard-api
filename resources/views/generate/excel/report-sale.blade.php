<table>
    <thead>
    <tr>
        <td style="width: 170px; font-weight: bold" rowspan="2" id="menu">Menu</td>
        <td colspan="{{count($reports['dates'])}}" style="font-weight: bold" id="periode">Periode</td>
        <td style="width: 70px; font-weight: bold" rowspan="2" id="total">Total</td>
    </tr>
    <tr>
        @foreach($reports['dates'] as $date)
            <td style="font-weight: bold">
                {{date('d', strtotime($date))}}
            </td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($reports['data'] as $category)
        @if(!$isCategoryFiltered)
            <tr>
                <td style="font-weight: bold">Grand Total</td>
                @foreach($reports['total_per_date'] as $total)
                    <td>
                        {{ $total > 0 ? 'Rp ' . number_format($total) : '-' }}
                    </td>
                @endforeach
                <td style="font-weight: bold">
                    Rp {{number_format($reports['grand_total'])}}
                </td>
            </tr>
        @endif
        <tr>
            <td style="font-weight: bold">
                {{$category['category_name']}}
            </td>
            <td colspan="{{count($reports['dates']) + 1 }}"></td>
        </tr>
        @foreach($category['products'] as $product)
            <tr>
                <td style="font-weight: bold">
                    {{$product['product_name']}}
                </td>
                @foreach($product['transactions'] as $sale)
                    <td>
                        {{ $sale['total_sale'] > 0 ? 'Rp ' . number_format($sale['total_sale']) : '-' }}
                    </td>
                @endforeach
                <td style="font-weight: bold">
                    Rp {{number_format($product['transactions_total'])}}
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold">
                Total {{$category['category_name']}}
            </td>
            <td colspan="{{count($reports['dates'])}}"></td>
            <td style="font-weight: bold">
                {{ $category['category_total'] > 0 ? 'Rp ' . number_format($category['category_total']) : '-' }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
