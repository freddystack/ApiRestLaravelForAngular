<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProuctsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::all();
        return response()->json([
            'status' => true,
            'data' => $products
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $productsToValidate = $request->only('category_name','name','descripcion','disponible','stock','precio_actual');
        $messages =[
            'category_name.required' => 'El campo categoria no puede quedar vacio',
            'category_name.min' => 'Debe de tener un minimo de 2 caracteres',
            'category_name.max' => 'Debe de tener un minimo de 40 caracteres',
            'name.required' => 'Este campo no puede quedar vacio',
            'name.min' => 'Debe de tener un minimo de 10 caracteres',
            'name.max' => 'Debe de tener un maximo de 30 caracteres',
            'name.unique' => 'El nombre de este producto ya existe',
            'descripcion.required' => 'Este campo es requerido',
            'descripcion.min' => 'Debe de tener un minimo de 10 caracteres',
            'descripcion.max' => 'Debe de tener un maximo de 70 caracteres',
            'disponible.required' => 'Este campo es requerido',
            'disponible.min' => 'Debe de tener un minimo y maximo de 2 caracteres',
            'disponible.max' => 'Debe de tener un maximo de 2 caracteres',
           
            'stock.required' => 'Este campo es requerido',
            'precio_actual.required' => 'Este campo es requerido',
            'imagen.mimes' => 'Tiene que ser formato jpeg,png,gif',
        ];
        $validate = Validator::make($productsToValidate, [
            'category_name' => 'required|min:2|max:40',
            'name' => 'required|min:5|max:30|unique:products',
            'descripcion' => 'required|min:10|max:70',
            'disponible' => 'required|min:2|max:2',
            'stock' => 'required',
            'precio_actual' => 'required',
            'imagen' => 'mimes:jpeg,png,gif',
        ], $messages);
        if(!$validate->fails()){
            $products = new Products();
            $products->category_name = $request->category_name;
            $products->name = $request->name;
            $products->descripcion = $request->descripcion;
            $products->disponible = $request->disponible;
            $products->stock = $request->stock;
            $products->precio_actual = $request->precio_actual;
            $products->imagen = $request->imagen;

            if($products->save()){
                 return response()->json([
                   'status' => true,
                    'data' => 'Producto añadido exitosamente'
                 ], 200);
            }else{
                return response()->json([
                    'status' => false,
                     'data' => 'No se ha podido guardar el producto'
                  ], 500);
            } 
        }else{
            return response()->json([
                'status' => false,
                'data' => $validate->errors()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $product = DB::table('products')->where('name',$name)->first();
        if($product){
             return response()->json([
                'status' => true,
                'data' => $product
             ],200);
        }else{
            return response()->json([
                'status' => false,
                'data' => 'El produncto con el nombre '. $name .' no existe'
             ],400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productsToUpdate = $request->only('category_name','name','descripcion','disponible','imagen','stock','precio_actual','precio_anterior','descuento');
        $product = Products::find($id);
        if(!$product){
            return response()->json([
                'status' => false,
                'data' => 'Este Producto no existe en los registros'
            ], 400);
        }

        $messages =[
            'category_name.required' => 'El campo categoria no puede quedar vacio',
            'name.min' => 'Debe de tener un minimo de 5 caracteres',
            'name.max' => 'Debe de tener un maximo de 30 caracteres',
            'descripcion.min' => 'Debe de tener un minimo de 10 caracteres',
            'descripcion.max' => 'Debe de tener un maximo de 70 caracteres',
            'disponible.min' => 'Debe de tener un minimo y maximo de 2 caracteres',
            'disponible.max' => 'Debe de tener un maximo de 2 caracteres',
           
            'stock.required' => 'Este campo no puede quedar vacio',
            'precio_actual.required' => 'Este campo no puede quedar vacio',
            'imagen.mimes' => 'Tiene que ser formato jpeg,png,gif',
        ];

        $validate = Validator::make($productsToUpdate ,[
            'category_name' => 'required',
            'name' => 'min:5|max:30',
            'descripcion' => 'min:10|max:70',
            'disponible' => 'min:2|max:2',
            'stock' => 'required',
            'precio_actual' => 'required',
           
            'imagen' => 'mimes:jpeg,png,gif',
        ], $messages);

        if(!$validate->fails()){
            $updateProduct = $product->fill($request->all())->save();
            if($updateProduct){
                 return response()->json([
                     'status' => true,
                     'data' => 'El producto se ha actualizado'
                 ], 200);
            }else{
                 return response()->json([
                     'status' => false,
                     'data' => 'No se ha podido actualizar el producto'
                 ], 500);
            }
        }else{
            return response()->json([
                'status' => false,
                'error' => $validate->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        if($product){
            if($product->delete()){
                return response()->json([
                    'status' => true,
                    'data' => 'El producto se ha eliminado'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => 'El producto no se ha podido eliminar'
                ], 500);
            }
        }else{
            return response()->json([
               'status' => false,
                'data' => 'Este producto no existe'
            ], 400);
        }
    }
}
