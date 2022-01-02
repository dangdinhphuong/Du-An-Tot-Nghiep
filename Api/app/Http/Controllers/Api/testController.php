<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectService;
use Illuminate\Http\Request;

class testController extends Controller
{
    //
    public  $data = [];
    public  $student = [];


    public function testfaker()
    {

        for ($i = 1; $i < 6; $i++) {
            $name = config('service_unit.name')[array_rand(config('service_unit.name'))]; // tien dien
            $student[$i]['name'] = $name;
            $student[$i]['unit_price'] = mt_rand(10000, 100000);

            $student[$i]['project_id'] = (int)$this->generateNumberValue(); // 1
            $project_id = $student[$i]['project_id'];
            if ($name == config('service_unit.electric.name')) {
                $student[$i]['unit'] = config('service_unit.unit')[0];
            } elseif ($name == config('service_unit.water.name')) {
                $student[$i]['unit'] = config('service_unit.unit')[1];
            }
            $this->data['data'][$project_id] = $name; // data[1] => tien dien
            $student[$i]['permanent'] = 0;
            $this->student['data'][] = $student[$i];
        }
        foreach ($this->data['data'] as $key => $value) {
            $arrToPush['unit_price'] = mt_rand(10000, 100000);
            $arrToPush['project_id'] = $key;
            $arrayToCompare = config('service_unit.name');
            $arrayChanged = [];
            $arrayChanged[] = (string)$value;
            $arrToPush['name'] =  $this->returnNameDiff($arrayChanged, $arrayToCompare); // tien nuoc
            // dump($arrToPush['name']);
            if ($arrToPush['name'] == config('service_unit.electric.name')) {
                $arrToPush['unit'] = config('service_unit.unit')[0];
            } elseif ($arrToPush['name'] == config('service_unit.water.name')) {
                $arrToPush['unit'] = config('service_unit.unit')[1];
            }
            $arrToPush['permanent'] = 0;
            $this->student['data'][] =  $arrToPush;
        }


        // dump($this->student['data']);
        foreach ($this->student['data'] as $projectService) {
            // dump($projectService);
            ProjectService::create($projectService);
        }
    }

    public  function generateNumberValue()
    {
        $value = mt_rand(1, 5);
        // call the same function if the value exists already
        if ($this->idExists($value)) {
            return $this->generateNumberValue($value);
        }

        // otherwise, it's valid and can be used
        return $value;
    }

    public  function idExists($value)
    {
        // check the $data and return a boolean

        if (in_array($value, $this->data)) {
            return true;
        } else {
            $this->data[] = $value;
            return false;
        }
    }
    public function returnNameDiff($arrayChanged, $arrayToCompare, $debug = false) // tien dien + tien nuoc vs tien dien
    {
        $array =  array_diff($arrayToCompare, $arrayChanged);
        if ($debug) {
            dump($debug, 'debug value');
        }

        foreach ($array as $a => $value) {
            if ($value != null && !is_numeric($value)) {
                return $value;
            }
            // dump($value);
        }
    }
}
