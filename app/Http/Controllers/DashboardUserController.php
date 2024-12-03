<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Orchid\Attachment\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Orchid\Attachment\Models\Attachment;

class DashboardUserController extends Controller
{
    /**
     * Показать список заказов для пользователя
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function your_orders()
    {
        return view('dashboard-yourorders',[
            'orders' => Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    /**
     * Показать страницу настроек пользователя
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard_settings()
    {
        $certificates = Attachment::where('user_id', auth()->user()->id)->where(
            'group',
            null
        )->get();
        return view('dashboard-setting',[
            'certificates' => $certificates,
        ]);
    }

    public function remove_certificate(Request $request)
    {
        $attachment = Attachment::where('id', $request->certificate_id)->first();
        $attachment->delete();
        return back()->with('deleted', 'Сертификат успешно удален');
    }

    public function upload_certificate(Request $request)
    {
        $file = new File($request->file('user_certificate'));
        $attachment = $file->load();

        return back()->with('success', 'Сертификат успешно загружен');
    }


    /**
     * Обновить пароль пользователя
     *
     * @param Request $request объект с данными формы
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password_update(Request $request)
    {
        // Валидация входящих данных
        $request->validate([

        ]);

        // Получить текущего пользователя
        $user = auth()->user();

        // Проверить совпадение старого и нового пароля
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Пароли не совпадают');
        }

        // Обновить пароль и сохранить
        $user->password = Hash::make($request->password_repeat);
        $user->save();

        // Перенаправить на предыдущую страницу с уведомлением об успехе
        return back()->with('success', 'Пароль успешно изменен');
    }
}
