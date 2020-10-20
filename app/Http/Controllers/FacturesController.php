<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacturesModel;
use Illuminate\Support\Facades\DB;

class FacturesController extends Controller
{
    public function insertFacture($concept,$price,$units,$subtotal,$iva,$total){
       $facture = new FacturesModel();
       $facture->concept = $concept;
       $facture->price = $price;
       $facture->units = $units;
       $facture->subtotal = $subtotal;
       $facture->iva = $iva;
       $facture->total = $total;
       $facture->save();
    }

    public function truncateTable(){
        $rows = FacturesModel::count();
        if($rows >= 100){
            DB::table('factures_models')->truncate();
        }




    }


}
