<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class OrderCheckScreen extends Screen
{
    /**
     * @var Order
     */

    public $order;
    public $orderDetails;

    public function query(Order $order): iterable
    {
        $order_detail = OrderDetail::where('order_id', $order->id)->get();
        return [
            'orders' => $order,
            'orderDetails' => $order_detail
        ];
    }

    public function __construct(Order $order)
    {
        $orderID = $order->id;
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Товарный чек' . ' #';
    }

    public function description(): string
    {
        return 'Здесь вы можете посмотреть товарный чек';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Выйти'))
                ->icon('bs.arrow-left')
                ->method('back'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('exports.order-export'),
        ];
    }

    public function back(Request $request)
    {
        return redirect()->route('platform.orders');
    }
}
