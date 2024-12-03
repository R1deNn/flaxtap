@php
    use App\Models\Order;
    use App\Models\OrderDetail;

    $details = OrderDetail::where('order_id', $orders->id)->get();

    $totalAmount = 0;
@endphp
<h2 style="text-align: center">Товарный чек №{{ $orders->id }} от {{$orders->created_at->format('j') . ' ' . $orders->created_at->format('F') . ' ' . $orders->created_at->format('Y') . ' года'}}</h2>
<table style="width: 100%" id="myTable">
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
    <tbody>
        <tr>
            <th>ФИО</th>
            <th>Адрес</th>
            <th>Телефон</th>
            <th>Почта</th>
            <th>ВКонтакте</th>
        </tr>
        <tr>
            <td>{{ $orders->fio }}</td>
            <td>{{ $orders->adress }}</td>
            <td>{{ $orders->tel }}</td>
            <td>{{ $orders->email }}</td>
            <td>{{ $orders->vk }}</td>
        </tr>
    </tbody>
</table>
<button id="printButton" class="mt-4 bg-gray-500 border-1 border-b-gray-950 text-black font-bold py-2 px-4 rounded">Печать</button>
<script>
    // Получаем ссылку на кнопку
    const printButton = document.getElementById('printButton');

    // Добавляем обработчик события нажатия на кнопку
    printButton.addEventListener('click', function() {
        // Создаем новый объект окна печати
        const printWindow = window.open('', '_blank');

        // Получаем ссылку на таблицу
        const table = document.getElementById('myTable');

        // Создаем новый документ в окне печати
        const printDocument = printWindow.document;

        // Добавляем стили для печати
        const printStyle = printDocument.createElement('style');
        printStyle.innerHTML = `
    table {
      border-collapse: collapse;
      width: 100%;
      text-align: center;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }
    tbody {
        margin-top: 24px;
    }
  `;
        printDocument.head.appendChild(printStyle);

        // Копируем содержимое таблицы в документ печати
        const tableClone = table.cloneNode(true);
        printDocument.body.appendChild(tableClone);

        // Открываем окно печати и запускаем процесс печати
        printWindow.print();

        location.reload();
    });
</script>
