<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceMonthRequest;
use App\Http\Resources\ContractResource;
use App\Http\Resources\InvoiceContractResouce;
use App\Http\Resources\InvoiceResource;
use App\Jobs\SendMailStoreInvoiceContract;
use App\Jobs\SendMailStoreInvoiceMonth;
use App\Mail\SendInvoiceContractEmail;
use App\Models\Bed;
use App\Models\Contract;
use App\Models\ContractHistoryInvoice;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\ProjectService;
use App\Models\Receipt;
use App\Models\Room;
use App\Models\RoomHistoryInvoice;
use App\Models\ServiceIndex;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    use TraitResponse;
    public function indexBasedOnMonth(Request $request)
    {
        $data['month'] = [];
        $data['year'] = Carbon::now()->format('Y');
        foreach (config('Months.months') as $t) {
            $data['month'][] = $t;
        }
        if ($request->has('month_year')) {
            $month_year = $request->month_year;
            $modInsertDate = date('Y-m-d H:i:s', strtotime($month_year));
        } else {
            $modInsertDate = null;
        }
        $serviceIndex = RoomHistoryInvoice::date($modInsertDate)->has('room.contracts')->filter($request->only(['project_id', 'building_id', 'floor_id', 'status']))->with(
            [
                'room' => function ($query) {
                    $query->select('id', 'name', 'floor_id');
                }, 'room.floor' => function ($query) {
                    $query->select('id', 'name', 'building_id');
                }, 'room.floor.building' => function ($query) {
                    $query->select('id', 'name', 'project_id');
                }, 'room.floor.building.project' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )->paginate(10);

        return InvoiceResource::collection($serviceIndex);
    }
    public function createInvoiceBasedOnMonth(Request $request, $id)
    {
        $roomHistoryInvoice = RoomHistoryInvoice::find($id);
        if ($roomHistoryInvoice == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        if ($roomHistoryInvoice->status == config('Months.status.da_chot')) {
            return $this->responseApi('', 404, '???? t???o h??a ????n cho ph??ng n??y');
        }
        $contract = $roomHistoryInvoice->room->contracts;
        if ($contract == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        $serviceIndex = ServiceIndex::where('room_id', $roomHistoryInvoice->room_id)->where('created_at', $roomHistoryInvoice->created_at)->first();
        $previousMonth = Carbon::parse($serviceIndex->created_at)->subMonth();
        $modInsertDate =  date('Y-m-d H:i:s', strtotime($previousMonth));
        $serviceIndexsPrevious = ServiceIndex::where('created_at', 'like', '%' . $modInsertDate . '%')->where('room_id', $serviceIndex->room_id)->first();
        if ($serviceIndexsPrevious != null) {
            $currentWaterValue = (int)$serviceIndex->index_water - (int)$serviceIndexsPrevious->index_water;
            $currentElectricValue = (int)$serviceIndex->index_electric - (int)$serviceIndexsPrevious->index_electric;
            if ($currentWaterValue > 0) {
                $serviceIndex->index_water_calculated = (int)$serviceIndex->index_water - (int)$serviceIndexsPrevious->index_water;
            } else {
                $serviceIndex->index_water_calculated = 0;
            }
            if ($currentElectricValue > 0) {
                $serviceIndex->index_electric_calculated = (int)$serviceIndex->index_electric - (int)$serviceIndexsPrevious->index_electric;
            } else {
                $serviceIndex->index_electric_calculated = 0;
            }
        } else {
            $serviceIndex->index_water_calculated = $serviceIndex->index_water;
            $serviceIndex->index_electric_calculated = $serviceIndex->index_electric;
        }
        $projectService = $roomHistoryInvoice->room->floor->building->project->projectServices;
        if ($serviceIndex->status == config('Months.status.chua_chot')) {
            $data['service_index_info'][] = 'Ch??a ch???t ti???n ??i???n n?????c ' . Carbon::parse($serviceIndex->created_at)->format("d-m-y");
            $data['service_index_info'][] = $serviceIndex->status;
        } else {
            $data['service_index_info'][] = '???? ch???t ti???n ??i???n n?????c ' . Carbon::parse($serviceIndex->created_at)->format("d-m-y");
            $data['service_index_info'][] = $serviceIndex->status;
        }
        // if ($contract->deposit_state == config('Months.status.chua_chot')) {
        //     $arr = [
        //         'name' => 'Ti???n c???c',
        //         'unit_price' => $contract->deposit,
        //         'unit' => \config('service_unit.other.unit'),
        //         'permanent' => 1,
        //         'total_money' => $contract->deposit
        //     ];
        //     $data['project_service_info'][] = $arr;
        // }
        foreach ($projectService as $p) {
            if ($p->name == config('service_unit.electric.name')) {
                $p->index = $serviceIndex->index_electric;
                $p->index_calculated = $serviceIndex->index_electric_calculated;
            }
            if ($p->name == config('service_unit.water.name')) {
                $p->index = $serviceIndex->index_water;
                $p->index_calculated = $serviceIndex->index_water_calculated;
            }
            $p->date_collect = $roomHistoryInvoice->created_at;
            $p->total_money = $p->unit_price * $p->index_calculated;
            if ($p->permanent == 0) {
                $data['project_service_info'][] = $p;
            }
        }

        $data['room_info']['room']['id'] = $roomHistoryInvoice->room->id;
        $data['room_info']['room']['name'] = $roomHistoryInvoice->room->name;
        $data['room_info']['floor']['id'] = $roomHistoryInvoice->room->floor->id;
        $data['room_info']['floor']['name'] = $roomHistoryInvoice->room->floor->name;
        $data['room_info']['building']['id'] = $roomHistoryInvoice->room->floor->building->id;
        $data['room_info']['building']['name'] = $roomHistoryInvoice->room->floor->building->name;
        $data['room_info']['project']['id'] = $roomHistoryInvoice->room->floor->building->project->id;
        $data['room_info']['project']['name'] = $roomHistoryInvoice->room->floor->building->project->name;
        return $data;
    }
    public function storeInvoiceBasedOnMonth(StoreInvoiceMonthRequest $request, $id)
    {
        $roomHistoryInvoice = RoomHistoryInvoice::find($id);
        if ($roomHistoryInvoice == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        if ($roomHistoryInvoice->status == config('Months.status.da_chot')) {
            return $this->responseApi('', 404, '???? t???o h??a ????n cho ph??ng n??y');
        }
        $data['invoices'] = $request->only(['payment_type', 'note']);
        $data['invoices']['total_money'] = 0;
        $data['invoice_details'] = $this->createInvoiceBasedOnMonth($request, $id);
        foreach ($data['invoice_details']['project_service_info'] as $p) {
            $data['invoices']['total_money'] += $p->total_money;
        }
        if ($data['invoice_details']['service_index_info'][1] == config('Months.status.chua_chot')) {
            return $this->responseApi('', 404, 'Ch??a ch???t ??i???n n?????c cho th??ng kh??ng th??? l??u h??a ????n n??y');
        }
        try {
            DB::beginTransaction();
            $invoice = Invoice::create(array_merge(
                [
                    'status' => config('Months.status.chua_chot'),
                    'staff_id' => auth()->user()->id,
                    'created_at' => Carbon::parse($roomHistoryInvoice->created_at)->format('Y-m-d H:i:s')
                ],
                $data['invoices']
            ));
            Receipt::create(array_merge(
                $request->only(['total_money']),
                [
                    'invoice_id' => $invoice->id,
                    'note' => 'Phi???u thu cho h??a ????n theo ph??ng ' . $data['invoice_details']['room_info']['room']['name'] . ' theo th??ng : ' . Carbon::parse($roomHistoryInvoice->created_at)->format('Y-m-d H:i:s'),
                    'collection_date' => Carbon::now()->toDateTimeString(),
                    'amount_of_money' => $data['invoices']['total_money'],
                    'receipt_reason_id' => 2,
                    'payment_type' => $request->input('payment_type')
                ]
            ));
            SendMailStoreInvoiceMonth::dispatch($data);
            $data['invoice_details']['project_service_info']  = json_encode($data['invoice_details']['project_service_info']);
            $data['invoice_details']['room_info']  = json_encode($data['invoice_details']['room_info']);
            $data['invoice_details']['invoice_content'] = config('invoice.invoice_content.theo_thang');
            InvoiceDetail::create(array_merge(
                $data['invoice_details'],
                [
                    'invoice_id' => $invoice->id,
                    'created_at' => Carbon::parse($roomHistoryInvoice->created_at)->format('Y-m-d H:i:s')

                ]
            ));
            $roomHistoryInvoice = RoomHistoryInvoice::find($id);
            $roomHistoryInvoice->status = config('Months.status.da_chot');
            $roomHistoryInvoice->save();
            DB::commit();
            return $this->responseApi([], 200, 'L??u h??a ????n th??nh c??ng');
        } catch (Exception $th) {
            DB::rollBack();
            Log::info("T???o h??a ????n cho ph??ng : " . $id . ' t???i th???i ??i???m ' . Carbon::now() . ' th???t b???i ' . ' Ng?????i t???o : ' . auth()->user() ?? 'not found' . '. L???i : ');
            Log::info($th);
            return $this->responseApi($th, 500, 'T???o h??a ????n th???t b???i !');
        }
    }
    public function showInvoiceBasedOnMonth(Request $request, $id)
    {
        $roomHistoryInvoice = RoomHistoryInvoice::find($id);
        if ($roomHistoryInvoice == null) {
            return $this->responseApi('', 400, "H??a ????n kh??ng t???n t???i ho???c c?? th??? ???? b??? x??a kh???i l???ch s??? h??a ????n. Check danh s??ch phi???u thu");
        }
        $invoiceDetail = InvoiceDetail::where('room_info', 'LIKE', '%' . '{"room":{"id":' . $roomHistoryInvoice->room_id . ',"' . '%')->where('created_at', $roomHistoryInvoice->created_at)->first();
        $invoice = $invoiceDetail->invoice;
        $data['invoice'] = $invoice;
        $data['invoice_detail'] = $invoiceDetail;
        $data['invoice_detail']->project_service_info = json_decode($data['invoice_detail']->project_service_info);
        $data['invoice_detail']->room_info = json_decode($data['invoice_detail']->room_info);
        //{"room":{"id":3
        // dd('{"room":{"id":' . $roomHistoryInvoice->room_id);
        // foreach ($data['invoice_detail']->project_service_info as $p) {
        //     dump($p->id);
        // }
        return $this->responseApi($data, 201, "L???y d??? li???u th??nh c??ng");
    }
    public function indexBasedOnContract(Request $request)
    {
        $data['month'] = [];
        $data['year'] = Carbon::now()->format('Y');
        foreach (config('Months.months') as $t) {
            $data['month'][] = $t;
        }

        if ($request->has('month_year')) {
            $month_year = $request->month_year;
            $modInsertDate = date('Y-m-d H:i:s', strtotime($month_year));
        } else {
            $modInsertDate = null;
        }
        $serviceIndex = ContractHistoryInvoice::date($modInsertDate)->filter($request->only(['project_id', 'building_id', 'floor_id', 'status']))->with(
            [
                'contract.room' => function ($query) {
                    $query->select('id', 'name', 'floor_id');
                }, 'contract.room.floor' => function ($query) {
                    $query->select('id', 'name', 'building_id');
                }, 'contract.room.floor.building' => function ($query) {
                    $query->select('id', 'name', 'project_id');
                }, 'contract.room.floor.building.project' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )->paginate(10);

        return InvoiceContractResouce::collection($serviceIndex);
    }
    public function createInvoiceBasedOnContract(Request $request, $id)
    {
        $contractHistoryInvoice = ContractHistoryInvoice::find($id);
        if ($contractHistoryInvoice == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        if ($contractHistoryInvoice->status == config('Months.status.da_chot')) {
            return $this->responseApi('', 404, '???? t???o h??a ????n cho h???p ?????ng n??y');
        }
        $contract = $contractHistoryInvoice->contract;
        if ($contract == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        $projectService = ProjectService::find(json_decode($contract->project_service_id));
        foreach ($projectService as $p) {

            $p->date_collect = $contractHistoryInvoice->created_at;
            if ($p->index != null) {
                $p->total_money = $p->unit_price * $p->index;
            } else {
                $p->total_money = $p->unit_price;
            }

            if ($p->permanent == 1) {
                $data['project_service_info'][] = $p;
            }
        }
        $monthToCollect = $contract->room->floor->building->project->cycle_collect; //chu k??? thu
        $dateCollect = Carbon::parse($contractHistoryInvoice->created_at);  // th??ng ?????u ti??n
        $endDayOfFirstMonth = Carbon::parse($contractHistoryInvoice->created_at)->endOfMonth(); // ng??y cu???i c??ng c???a th??ng ?????u ti??n
        $endMonth = Carbon::parse($contractHistoryInvoice->created_at)->addMonths($monthToCollect); // th??ng cu???i c??ng ph???i thu
        $diffInMonth = $endMonth->diffInMonths($dateCollect); // kh??c bi???t th??ng gi???a th??ng ?????u ti???n v?? th??ng cu???i c??ng
        $dayDiffInFirstMonth = $endDayOfFirstMonth->diffInDays($dateCollect); // kh??c bi???t ng??y cu???i c??ng c???a th??ng ?????u ti??n v?? ng??y trong th???i gian th??ng ?????u ti??n 
        $lastDayInFirstMonth = Carbon::parse($contractHistoryInvoice->created_at)->endOfMonth()->format('d'); // ng??y cu???i c??ng c???a th??ng ?????u ti??n
        $firstMonthDayRatio = $dayDiffInFirstMonth / (int)$lastDayInFirstMonth; // t??? l??? gi???a kh??c bi???t ng??y cu???i c??ng c???a th??ng ?????u ti??n v?? ng??y trong th???i gian th??ng ?????u ti??n v?? ng??y cu???i c??ng c???a th??ng ?????u ti??n
        $price = $contract->price * $firstMonthDayRatio; // ti???n ph??ng c???a th??ng ?????u ti???n sinh vi??n 


        for ($i = 1; $i < $diffInMonth; $i++) {
            $price += $contract->price; // ti???n ph??ng c???a c??c th??ng ti???p theo

        }

        $endDayOfLastMonth = Carbon::parse($contractHistoryInvoice->created_at)->addMonths($monthToCollect)->endOfMonth();
        $dayDiffInLastMonth = $endDayOfLastMonth->diffInDays(Carbon::parse($contractHistoryInvoice->created_at)->addMonths($monthToCollect)); // kh??c bi???t ng??y cu???i c??ng c???a th??ng cu???i c??ng v?? ng??y trong th???i gian th??ng cu???i c??ng 
        $lastDayInLastMonth = Carbon::parse($contractHistoryInvoice->created_at)->endOfMonth()->format('d'); // ng??y cu???i c??ng c???a th??ng cu???i c??ng
        $lastMonthDayRatio = $dayDiffInLastMonth / (int)$lastDayInLastMonth; // t??? l??? gi???a kh??c bi???t ng??y cu???i c??ng c???a th??ng cu???i c??ng v?? ng??y trong th???i gian th??ng cu???i c??ng v?? ng??y cu???i c??ng c???a th??ng cu???i c??ng

        $price += $contract->price * $lastMonthDayRatio; // ti???n ph??ng c???a th??ng cu???i c??ng
        if (isset($data['project_service_info']) && $data['project_service_info'] != null) {
            foreach ($data['project_service_info'] as $p) {
                $p->total_money = $p->total_money * $firstMonthDayRatio;
            }
            foreach ($data['project_service_info'] as $p) {
                $projectServicePrice = 0;
                for ($i = 1; $i < $diffInMonth; $i++) {
                    $projectServicePrice += $p->total_money;
                }
                if ($diffInMonth > 1) {
                    $p->total_money = $projectServicePrice;
                }
            }
            foreach ($data['project_service_info'] as $p) {
                $p->name = $p->name . ' (T??? th??ng ' . Carbon::parse($contractHistoryInvoice->created_at)->format('d-m-y') . ' ?????n th??ng ' . Carbon::parse($contractHistoryInvoice->created_at)->addMonths($monthToCollect)->format('d-m-y') . ' ) ';
                $p->total_money += $p->total_money * $lastMonthDayRatio;
                $p->total_money  = round($p->total_money, -2, PHP_ROUND_HALF_UP);
            }
        }


        $price  = round($price, -2, PHP_ROUND_HALF_UP);
        $data['room_fee_info'] = [
            'name' => 'Ti???n ph??ng (T??? th??ng ' . Carbon::parse($contractHistoryInvoice->created_at)->format('m-y') . ' ?????n th??ng ' . Carbon::parse($contractHistoryInvoice->created_at)->addMonths($monthToCollect)->format('m-y') . ' ) ',
            'unit_price' => $contract->price,
            'unit' => config('service_unit.other.unit'),
            'date_collect' => $contractHistoryInvoice->created_at,
            'total_money' => $price
        ];
        $data['student_info'] = $contract->user;
        $data['room_info']['room']['id'] = $contractHistoryInvoice->contract->room->id;
        $data['room_info']['room']['name'] = $contractHistoryInvoice->contract->room->name;
        $data['room_info']['floor']['id'] = $contractHistoryInvoice->contract->room->floor->id;
        $data['room_info']['floor']['name'] = $contractHistoryInvoice->contract->room->floor->name;
        $data['room_info']['building']['id'] = $contractHistoryInvoice->contract->room->floor->building->id;
        $data['room_info']['building']['name'] = $contractHistoryInvoice->contract->room->floor->building->name;
        $data['room_info']['project']['id'] = $contractHistoryInvoice->contract->room->floor->building->project->id;
        $data['room_info']['project']['name'] = $contractHistoryInvoice->contract->room->floor->building->project->name;
        return $data;
    }
    public function storeInvoiceBasedOnContract(Request $request, $id)
    {
        $contractHistoryInvoice = ContractHistoryInvoice::find($id);

        if ($contractHistoryInvoice == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        if ($contractHistoryInvoice->status == config('Months.status.da_chot')) {
            return $this->responseApi('', 404, '???? t???o h??a ????n cho h???p ?????ng n??y');
        }
        $contract = $contractHistoryInvoice->contract;
        if ($contract == null) {
            return $this->responseApi('', 404, '???????ng d???n kh??ng t???n t???i');
        }
        $data['invoices'] = $request->only(['payment_type', 'note']);
        $data['invoices']['total_money'] = 0;
        $data['invoice_details'] = $this->createInvoiceBasedOnContract($request, $id);
        if (isset($data['invoice_details']['project_service_info']) && $data['invoice_details']['project_service_info'] != null) {
            foreach ($data['invoice_details']['project_service_info'] as $p) {
                $data['invoices']['total_money'] += $p->total_money;
            }
        }

        $data['invoices']['total_money'] += $data['invoice_details']['room_fee_info']['total_money'];

        try {
            DB::beginTransaction();
            $invoice = Invoice::create(array_merge(
                [
                    'contract_id' => $contract->id,
                    'status' => config('Months.status.chua_chot'),
                    'staff_id' => auth()->user()->id,
                    'student_id' => $data['invoice_details']['student_info']->id,
                    'created_at' => Carbon::parse($contractHistoryInvoice->created_at)->format('Y-m-d H:i:s')


                ],
                $data['invoices']
            ));
            Receipt::create(array_merge(
                $request->only(['total_money']),
                [
                    'invoice_id' => $invoice->id,
                    'note' => 'Phi???u thu cho h??a ????n theo h???p ?????ng ' . $contract->id . ' sinh vi??n : ' . $data['invoice_details']['student_info']->name . '. Id sinh vi??n : ' . $data['invoice_details']['student_info']->id,
                    'collection_date' => Carbon::now()->toDateTimeString(),
                    'amount_of_money' => $data['invoices']['total_money'],
                    'receipt_reason_id' => 2,
                    'payment_type' => $request->input('payment_type')

                ]
            ));
            SendMailStoreInvoiceContract::dispatch($data);
            if (isset($data['invoice_details']['project_service_info']) && $data['invoice_details']['project_service_info'] != null) {
                $data['invoice_details']['project_service_info']  = json_encode($data['invoice_details']['project_service_info']);
            }
            $data['invoice_details']['room_info']  = json_encode($data['invoice_details']['room_info']);
            $data['invoice_details']['room_fee_info']  = json_encode($data['invoice_details']['room_fee_info']);
            $data['invoice_details']['student_info']  = json_encode($data['invoice_details']['student_info']);
            $data['invoice_details']['invoice_content'] = config('invoice.invoice_content.theo_chu_ky');
            InvoiceDetail::create(array_merge(
                $data['invoice_details'],
                [
                    'invoice_id' => $invoice->id,
                    'created_at' => Carbon::parse($contractHistoryInvoice->created_at)->format('Y-m-d H:i:s')

                ]
            ));
            $contractHistoryInvoice = ContractHistoryInvoice::find($id);
            $contractHistoryInvoice->status = config('Months.status.da_chot');
            $contractHistoryInvoice->save();
            DB::commit();
            return $this->responseApi([], 200, 'L??u h??a ????n th??nh c??ng');
        } catch (Exception $th) {
            DB::rollBack();
            Log::info("T???o h??a ????n cho ph??ng : " . $id . ' t???i th???i ??i???m ' . Carbon::now() . ' th???t b???i ' . ' Ng?????i t???o : ' . auth()->user() ?? 'not found' . '. L???i : ');
            Log::info($th);
            return $this->responseApi($th, 500, 'T???o h??a ????n th???t b???i !');
        }
    }
    public function showInvoiceBasedOnContract(Request $request, $id)
    {
        $contractHistoryInvoice = ContractHistoryInvoice::find($id);
        if ($contractHistoryInvoice == null) {
            return $this->responseApi('', 400, "H??a ????n kh??ng t???n t???i ho???c c?? th??? ???? b??? x??a kh???i l???ch s??? h??a ????n. Check danh s??ch phi???u thu");
        }
        $invoice = Invoice::where('contract_id', $contractHistoryInvoice->contract_id)->where('created_at', $contractHistoryInvoice->created_at)->first();
        $data['invoice'] = $invoice;
        $data['invoice_detail'] = $invoice->invoiceDetail;
        $data['invoice_detail']->project_service_info = json_decode($data['invoice_detail']->project_service_info);
        $data['invoice_detail']->room_info = json_decode($data['invoice_detail']->room_info);
        $data['invoice_detail']->room_fee_info = json_decode($data['invoice_detail']->room_fee_info);
        $data['invoice_detail']->student_info = json_decode($data['invoice_detail']->student_info);
        //{"room":{"id":3
        // dd('{"room":{"id":' . $contractHistoryInvoice->room_id);
        // foreach ($data['invoice_detail']->project_service_info as $p) {
        //     dump($p->id);
        // }
        return $this->responseApi($data, 201, "L???y d??? li???u th??nh c??ng");
    }
    public function updateStatusInvoice(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if ($invoice == null) {
            return $this->responseApi('', 400, "H??a ????n kh??ng t???n t???i ho???c c?? th??? ???? b??? x??a kh???i l???ch s??? h??a ????n. Check danh s??ch phi???u thu");
        }
        $invoice->update([
            'status' => config('Months.status.da_chot'),
            'date_payment' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return $this->responseApi([], 201, 'C???p nh???t th??nh c??ng');
    }
}
