<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderExportController extends Controller
{
    /**
     * Экспортирует детали заказа в файл Excel
     *
     * @param int $id Id заказа
     * @return \Illuminate\Http\Response
     *
     * @throws \Symfony\Component\HttpFoundation\NotFoundHttpException
     */
    public function export($id)
    {
        return Excel::download(new OrderExport($id), "Детали заказа №$id.xlsx");
    }
}
