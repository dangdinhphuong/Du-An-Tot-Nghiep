<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Models\ContractHistoryInvoice;
use App\Models\Invoice;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class ContractHistoryInvoiceController extends Controller
{
    //
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }
    public function index(Request $request)
    {
        $contractHistoryInvoice = ContractHistoryInvoice::filter($request->only(['contract_id']))->get();
        foreach ($contractHistoryInvoice as $cH) {
            $invoice = Invoice::where('contract_id', $cH->contract_id)->where('created_at', $cH->created_at)->first();
            if ($invoice != null) {
                $cH->invoice_detail = $invoice->invoiceDetail;
                $cH->invoice_detail->project_service_info = json_decode($cH->invoice_detail->project_service_info);
                $cH->invoice_detail->room_info = json_decode($cH->invoice_detail->room_info);
                $cH->invoice_detail->room_fee_info = json_decode($cH->invoice_detail->room_fee_info);
                $cH->invoice_detail->student_info = json_decode($cH->invoice_detail->student_info);
                $cH->total_money = $invoice->total_money;
            }
        }
        return $this->responseApi($contractHistoryInvoice, 200, 'Lấy dữ liệu thành công');
    }
    public function show(Request $request, $id)
    {
        $contractHistoryInvoice = ContractHistoryInvoice::find($id);
        if ($contractHistoryInvoice == null) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }
        return $this->responseApi($contractHistoryInvoice, 200, 'Lấy dữ liệu thành công');
    }
}
