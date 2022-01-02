<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptRequest;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Receipts_reason;
use App\Models\User;
use App\Traits\TraitResponse;
use App\Http\Resources\receiptResource;
use Illuminate\Support\Facades\DB;
use Exception;

class ReceiptController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {
        return $this->responseApi(Receipt::latest()->filter($request->only(['collection_date', 'name', 'receipt_reason_id']))->paginate(10), 200);
    }
    public function create()
    {
        $data['receipt_reasons'] = Receipts_reason::get(['id', 'title']);
        $data['users'] = [];
        User::chunk(200, function ($main) use (&$data) {
            foreach ($main as $m) {
                $data['users']['id'] = $m->id;
                $data['users']['first_name'] = $m->first_name;
            }
        });
        $data['payment_type'] = ['Tiền mặt', 'Chuyển khoản'];
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function store(ReceiptRequest $request)
    {
        $Users = DB::table('users')->find($request->user_id);
        //dd($Users);
        if (isset($Users) && !empty($Users) && $Users->user_type === 0) {
            try {
                DB::beginTransaction();
                Receipt::create([
                    'collection_date' => $request->collection_date,
                    'user_id' => $request->user_id,
                    'amount_of_money' => $request->amount_of_money,
                    'payment_type' => $request->payment_type,
                    'note' => $request->note,
                    'receipt_reason_id' => $request->receipt_reason_id,
                ]);
                DB::commit();
                return $this->responseApi("", 200, 'Lập phiếu thu thành công !');
            } catch (Exception $exception) {

                DB::rollBack();
                return $this->responseApi($exception, 400);
            }
        }
        return $this->responseApi("", 400, 'Lập phiếu thu thất bại !');
    }
    public function show($id)
    {
        $data = Receipt::with(['invoice', 'invoice.invoiceDetail','users'])->find($id);
        if (!isset($data) && empty($data)) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }
        if ($data->invoice != null) {
            $data->invoice->invoiceDetail->project_service_info = json_decode($data->invoice->invoiceDetail->project_service_info);
            $data->invoice->invoiceDetail->room_info = json_decode($data->invoice->invoiceDetail->room_info);
            $data->invoice->invoiceDetail->room_fee_info = json_decode($data->invoice->invoiceDetail->room_fee_info);
            $data->invoice->invoiceDetail->student_info = json_decode($data->invoice->invoiceDetail->student_info);
        }

        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
}
