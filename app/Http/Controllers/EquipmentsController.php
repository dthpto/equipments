<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Rules\valid_sn;


class EquipmentsController extends Controller
{

    public function getEquipments($id = null){
        $status = 200;
        $data = DB::table('equipments')
            ->select('equipments.*', 'equipment_types.type_name', 'equipment_types.sn_mask')
            ->join('equipment_types', 'equipments.equipment_type_id', '=', 'equipment_types.id');


        if($id) $data = $data->where('equipments.id', $id);

        $data = $data->get();
        if($data->count() == 0){
            $data = ['error' => 'Not found'];
            $status = 404;
        }

        return response()->json($data, $status);
    }

    public function createEquipment(Request $request){
        /*
         * input data format:
         * {
         *      'equipment_type_id' : 1,
         *      'comment': "some comment",
         *      'serial_number: ["asldfsus", "SOMESN", "SNDNDNJ99-aa"] || 'serial_number: "HJKT77-ll"
         * }
         */
        $rules = [
            'equipment_type_id' => 'required|integer|exists:equipment_types,id',
            'comment'           => 'nullable',
            'serial_number'     => ['required', 'string', 'unique:equipments,serial_number', new valid_sn($request->all())]
        ];
        $response = [];
        $raw_data = $request->all();

        if(!is_array($raw_data['serial_number'])){ // received one record - add it to array

            $raw_data['serial_number'] = [$raw_data['serial_number']];
        }
        $eq = [
            'equipment_type_id' => $raw_data['equipment_type_id'],
            'comment'           => $raw_data['comment']];
        $success_inserts = 0;
        foreach ($raw_data['serial_number'] as $sn){
            $eq['serial_number'] = $sn;
            $validator = Validator::make($eq, $rules);
            if($validator->fails()){
                $response['response']['errors'][] = ['messages' => $validator->messages(), 'sn' => $sn];

            } else {
                $validated = $validator->validated();
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
        $code = 200; // не уверен какой код правильно возвращать при создании записей

        return response()->json($response, $code);
    }

    public function updateEquipment(Request $request, $id){
        $response = [];
        $response['success'] = 0;
        $code = 200;
        $rules = [
            'equipment_type_id' => 'required|integer|exists:equipment_types,id',
            'serial_number'     => ['required', 'string', 'unique:equipments,serial_number', new valid_sn($request->all())],
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

