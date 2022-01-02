<?php

namespace App\Http\Controllers\Api\Students;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\Receipt;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {

        //{"id":1
        $invoiceDetail = InvoiceDetail::where('student_info', 'LIKE', '%' . '{"id":' . auth()->user()->id . ',"' . '%')->get();
        if ($invoiceDetail == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        $data = [];
        foreach ($invoiceDetail as $iD) {
            $data['receipts'][] = Receipt::where('invoice_id', $iD->invoice_id)->first();
        }

        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function edit(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        if ($receipt == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        if ($receipt->invoice == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        $data['receipt'] = $receipt;
        $data['invoice'] = $receipt->invoice;
        $data['invoice_detail'] = $receipt->invoice->invoiceDetail;
        $data['invoice_detail']->project_service_info = json_decode($data['invoice_detail']->project_service_info);
        $data['invoice_detail']->room_info = json_decode($data['invoice_detail']->room_info);
        $data['invoice_detail']->room_fee_info = json_decode($data['invoice_detail']->room_fee_info);
        $data['invoice_detail']->student_info = json_decode($data['invoice_detail']->student_info);
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
}
