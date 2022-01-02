<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\TraitResponse;
use App\Models\Receipts_reason;
use App\Http\Requests\ReceiptReason;
use App\Http\Requests\ReceiptReasonDeleteRequest;
use Exception;

class Receipts_reasonController extends Controller
{
    use TraitResponse;
    private $receipts_reason;
    public function __construct(Receipts_reason $receipts_reason)
    {
        $this->receipts_reason = $receipts_reason;
    }

    public function index(Request $request)
    {
        $receiptsReason = $this->receipts_reason->orderBy('id', 'DESC')->paginate(10);
        if (isset($receiptsReason) && !empty($receiptsReason)) {
            return $this->responseApi($receiptsReason, 200);
        }

        return $this->responseApi("", 200, "Không có lý do nào tồn tại");
    }

    public function detail(Request $request, $id)
    {
        $receiptsReason = $this->receipts_reason->find($id);
        if (isset($receiptsReason) && !empty($receiptsReason)) {
            return $this->responseApi($receiptsReason, 200);
        }
        return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
    }

    public function store(ReceiptReason $request)
    {
        try {
            DB::beginTransaction();
            $this->receipts_reason->create([
                'title' => $request->title,
                'description' => $request->description
            ]);
            DB::commit();
            return $this->responseApi("", 200, 'Thêm mới Lý do thu thành công !');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseApi("", 400, 'Thêm mới Lý do thu thất bại !');
        }
    }

    public function update(ReceiptReason $request, $id)
    {
        $receiptsReason = $this->receipts_reason->find($id);
        if (isset($receiptsReason) && !empty($receiptsReason)) {
            // dd($request->title);
            try {
                DB::beginTransaction();
                $receiptsReason->update([
                    'title' => $request->title,
                    'description' => $request->description
                ]);
                DB::commit();
                return $this->responseApi("", 200, 'Sửa lý do thu thành công !');
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }
        return $this->responseApi("", 400, 'Sửa lý do thu thất bại !');
    }

    public function destroy(ReceiptReasonDeleteRequest $request, $id)
    {
        $receiptsReason = $this->receipts_reason->find($id);
        if (isset($receiptsReason) && !empty($receiptsReason)) {
            if ($receiptsReason->id == '48' || $receiptsReason->id == 48) {
                return $this->responseApi("", 400, 'Xóa lý do thu thất bại !.Đây là lý do thu mặc định');
            }
            if ($receiptsReason->id == '49' || $receiptsReason->id == 49) {
                return $this->responseApi("", 400, 'Xóa lý do thu thất bại !.Đây là lý do thu mặc định');
            }
            $receiptsReason->delete();
            return $this->responseApi("", 200, "Xóa lý do thu thành công");
        } else {
            return $this->responseApi("", 400, 'Xóa lý do thu thất bại !');
        }
    }
}
