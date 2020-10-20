<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\FacturesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Decimal;
use App\Http\Controllers\FacturesController;

class SalesController extends Controller
{
    public function to_Sell(Request $request){
          $productsToSell = $request->only('products','units');

          $messages =[
              'products.required' => 'Este campo es requerido',
              'units.required' => 'Este campo es requerido'
          ];

          $validate = Validator::make($productsToSell ,[
             'products' => 'required',
             'units' => 'required'
          ], $messages);

          if(!$validate->fails()){
              $product = $request->only('products');
              $units = $request->only('units');
              $units = (int)$units['units'];
              $productsAvailable = DB::table('products')->where('name' , $product )->get();
               
               if(!$productsAvailable->isEmpty()){  
                  foreach($productsAvailable as $clave){
                      $id = $clave->id;
                      $productsUnitsDB = (int)$clave->stock;
                      $actualPrice = (float)$clave->precio_actual;
                      $iva = (float)$clave->iva;
                      $available = $clave->disponible;
                      $description = $clave->descripcion;
                  }

                  if($available === 'no'){
                    return response()->json([
                        'status' => false,
                        'data' => 'No diponible actualmente'
                     ], 400);
                  }
                  if($productsUnitsDB === 0){
                    return response()->json([
                        'status' => false,
                        'data' => 'Este producto esta fuera de stock'
                     ], 400);
                  }
        
                   if( $units > $productsUnitsDB){
                       return response()->json([
                          'status' => false,
                          'data' => 'Solo hay disponible la cantidad de '. $productsUnitsDB . ' unidades'
                       ], 400);
                    } 
                 
                   $subTotal = round( $actualPrice * $units, $precision =2 , $mode = PHP_ROUND_HALF_UP) ;
                    if($iva !== 0.00){
                      $iva *=  $units; 
                      $total = $subTotal;
                      $total +=  $iva;
                   }else{
                       $total = $subTotal;
                   }
                   $totalFormatted = (float)number_format($total,2);

                   $stockSubtraction = Products::find($id);
                   $stockSubtraction->stock = $productsUnitsDB - $units;
                   $stockSubtraction->save();

                   $Fact = new FacturesController();
                   $Fact->truncateTable();
                   $Fact->insertFacture($product['products'],$actualPrice,$units,$subTotal,$iva,$totalFormatted);
 
                  
                    return response()->json([
                        'status' => true,
                        'Producto' => $product['products'],
                        'Descripcion' => $description,
                        'Precio' => $actualPrice,
                        'Unidades' => $units,
                        'Subtotal' => $subTotal,
                        'IVA' => $iva,
                        'total' => $totalFormatted,

                    ], 200); 
                   
               }else{
                  return response()->json([
                      'status' => false,
                      'data' => 'Este producto no existe'
                  ], 400);
             } 
          }else{
              return response()->json([
                 'status' => false,
                 'data' => $validate->errors()
              ], 400);
          }

         
    }

}
