<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Products;
use Doctrine\DBAL\Types\JsonType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => true,
            'data' => $category
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
        $categoryToValidate = $request->only('name');
        $messages =[
            'name.required' => 'Este campo es requerido',
            'name.min' => 'Debe de tener un minimo de 5 caracteres',
            'name.max' => 'Debe de tener un maximo de 30 carecteres',
            'name.unique' => 'Esta categoria ya existe',
        ];
        $validate = Validator::make($categoryToValidate, [
            'name' => 'required|min:5|max:30|unique:categories'
        ], $messages);

        if(!$validate->fails()){
            $category = new Category();
            $category->name = $request->name;
            if($category->save()){
                 return response()->json([
                   'status' => true,
                    'data' => 'Nueva categoria añadida exitosamente'
                 ], 200);
            }else{
                return response()->json([
                    'status' => false,
                     'data' => 'No se ha podido ñadir la categoria'
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
        $productsCategory  = DB::table('products')->where('category_name' , $name)->get(); 
         if($productsCategory->isEmpty()){
            return response()->json([
               'status' => false,
               'data' => 'No se encontraron productos con esta categoria'
            ], 400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $data[] = $productsCategory
             ], 200);
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
        $categoryToUpdate = $request->only('name');
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'status' => false,
                'data' => 'Esta Categoria no existe en los registros'
            ], 400);
        }

        $messages =[
            'name.min' => 'Debe de tener un minimo de 5 caracteres',
            'name.max' => 'Debe de tener un maximo de 30 caracteres',
        ];

        $validate = Validator::make($categoryToUpdate ,[
            'name' => 'min:5|max:30',
        ], $messages);

        if(!$validate->fails()){
            $updateCategory = $category->fill($request->all())->save();
            if($updateCategory){
                 return response()->json([
                     'status' => true,
                     'data' => 'La Categoria se ha actualizado'
                 ], 200);
            }else{
                 return response()->json([
                     'status' => false,
                     'data' => 'No se ha podido actualizar la categoria'
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
        $category = Category::find($id);
        if($category){
            if($category->delete()){
                return response()->json([
                    'status' => true,
                    'data' => 'La categoria se ha eliminado'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => 'La categoria no se ha podido eliminar'
                ], 500);
            }
        }else{
            return response()->json([
               'status' => false,
                'data' => 'Esta ategoria no existe'
            ], 400);
        }
    }
}
