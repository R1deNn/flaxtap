@php
    use App\Models\Order;
    use App\Models\OrderDetail;

    $details = OrderDetail::where('order_id', $orders->id)->get();

    $totalAmount = 0;
@endphp
<h2 style="text-align: center">Товарный чек №{{ $orders->id }} от {{$orders->created_at->format('j') . ' ' . $orders->created_at->format('F') . ' ' . $orders->created_at->format('Y') . ' года'}}</h2>
<table style="width: 100%">
    <thead>
    <tr>
        <th>Название</th>
        <th>Кол-во</th>
        <th>Цена, руб.</th>
        <th>Сумма, руб.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($details as $detail)
        @php
            $totalAmount += $detail->amount
        @endphp
        <tr>
            <td>{{ $detail->product->title }}</td>
            <td>{{ $detail->amount }}</td>
            <td>
                @if($detail->price == 0)
                    Подарок
                @else
                    {{ $detail->price }}
                @endif
            </td>
            <td>{{ $detail->price * $detail->amount }}</td>
        </tr>
    @endforeach
    <tr>
        <td>Итого: </td>
        <td>{{$totalAmount}}</td>
        <td></td>
        <td>{{ $orders->price }}</td>
    </tr>
    </tbody>
</table>

