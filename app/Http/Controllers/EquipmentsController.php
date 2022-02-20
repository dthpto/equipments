<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipmentsController extends Controller
{
    public function getEquipments($id = null){
        echo 'get eq  ' . $id;
    }

    public function createEquipment(){
        echo 'create eq';
    }

    public function updateEquipment($id = null){
        echo 'update eq ' . $id;
    }

    public function deleteEquipment($id = null){
        echo 'delete eq ' . $id;
    }
}

