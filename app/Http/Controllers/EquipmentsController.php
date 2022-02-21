<?php

namespace App\Http\Controllers;

use Database\Seeders\equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Rules\valid_sn;
use App\Models\equipments;

class EquipmentsController extends Controller
{

    public function getEquipments($id = null){
        $status = 200;
        if($id){
            $data = DB::table('equipments')->find($id);
            if(!$data){
                $data = ['error' => 'Not found'];
                $status = 404;
            }
        } else {
            $data = DB::table('equipments')->get();
        }

        return response()->json($data, $status);
    }




    public function createEquipment(Request $request){
        $rules = [
            'equipment_type_id' => 'required|integer|exists:equipment_types,id',
            'serial_number'     => ['required', 'string', 'unique:equipments,serial_number', new valid_sn],
            'comment'           => 'nullable'
        ];
        $response = [];
        $raw_data = $request->all();
        if(!isset($raw_data['items'])){ // received one record - add it to array
            $raw_data = ['items' => ['0' => $raw_data]];
        }

        $success_inserts = 0;
        foreach ($raw_data['items'] as $eq){
            $validator = Validator::make($eq, $rules);
            if($validator->fails()){
                $response['response']['errors'][] = ['messages' => $validator->messages(), 'data' => $eq];
            } else {
                $validated = $validator->validated();
                // $response['response']['validated'][] = $validated;
                if($result = DB::table('equipments')->insert($validated))
                    $success_inserts++;
            }
        }

        $response['affected_rows'] = $success_inserts;
        if($success_inserts > 0){
            $response['success'] = 1;
        } else {
            $response['success'] = 0;
        }
        $code = 200; // не уверен какой код правильно возвращать при создании или не создании записей

        return response()->json($response, $code);
    }

    public function updateEquipment(Request $request, $id){
        $response = [];
        $response['success'] = 0;
        $code = 200;
        $rules = [
            'equipment_type_id' => 'required|integer|exists:equipment_types,id',
            'serial_number'     => ['required', 'string', 'unique:equipments,serial_number', new valid_sn],
            'comment'           => 'nullable'
        ];
        $raw_data = $request->all();

        $validator = Validator::make($raw_data, $rules);
        if($validator->fails()){
            $response['response']['errors'][] = ['messages' => $validator->messages(), 'data' => $raw_data];
        } else {
            $validated = $validator->validated();
            // $response['response']['validated'][] = $validated;
            if(DB::table('equipments')->where('id', $id)->update($validated)){
                $response['response']['data'] = $validated;
                $response['success'] = 1;
                $code = 201;
            }
        }
        return response()->json($response, $code);
    }

    public function deleteEquipment($id){
        $result = DB::table('equipments')->delete($id);
        if($result){
            $data = [
                'status' => 'success'
            ];
            $status = 200;
        } else {
            $data = [
                'status' => 'fail'
            ];
            $status = 404;
        }
        return response()->json($data, $status);
    }
}

