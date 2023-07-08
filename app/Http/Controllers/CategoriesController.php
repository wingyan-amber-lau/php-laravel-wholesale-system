<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\CategoryValue;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = 'SELECT * FROM categories';
        $categories = DB::select($sql);
        //$categories = arrayPaginator($categories, $request);
        // return view('settings.category',['categories'=>$categories]);
        return ['categories'=>$categories];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getByID($id)
    {
        return $category = Category::where('id',$id)->first();
        //$category = category::where('category_code',$category_code)->first();
        //$msg = "This is a simple message.";
        //return $category;
        // return response()->json($category, 200);
        //
    }

    public function save(Request $request){
        if ($request->input('data') == null){
            return response('Invalid data',500);
        }
        // $data = handleData($request->input('data'));
        $data = $request->input('data');
        return Category::updateOrCreate(
                [
                    'id'=>$data['category-id']
                ],
                [
                    'category_code'=>strtoupper($data['category-code']),
                    'category_name'=>$data['category-name'],
                    'for_product'=>isset($data['for-product'])?1:0
                ]
        );
        
    }

    public function getCategoryValueByCategoryID($id){
        // $category_id = $request->input('category_id');
        $sql = "SELECT id,
                value_code,
                value_name,
                (SELECT MAX(order_in_category) FROM category_values 
                WHERE category_id = ?) max_order
                FROM category_values 
                WHERE category_id = ?
                ORDER BY order_in_category";
        $category_values = DB::select($sql,[$id,$id]);
        // $category_values = stdClassArrToArray($category_values);
        return response()->json($category_values,200);
    }

    public function saveCategoryOrder(Request $request){
        if ($request->input('category_order') == null)
            return response('Invalid data',500);
        $category_values = $request->input('category_order');
        try {
            if ($category_values){
                foreach($category_values as $ind=>$category_value){
                    DB::table('category_values')
                        ->where('id',$category_value)
                        ->update(['order_in_category'=> $ind+1]);
                }
            }
        } catch (Exception $e){
            return response('Error:'.$e->getMessage(),500);
        }  
    }

    public function getCategoryValueByID($id)
    {
        // $category_value_id = $request->input('category_value_id');
        $category_value = CategoryValue::where('id',$id)
                    ->get();
        //$category = category::where('category_code',$category_code)->first();
        //$msg = "This is a simple message.";
        //return $category;
        return response()->json($category_value, 200);
        //
    }

    public function saveCategoryValue(Request $request){
        if ($request->input('data') == null)
            return response('Invalid data',500);

        // $data = handleData($request->input('data'));
        $data = $request->input('data');
        if (!$data['order-in-category']){
            $sql = "SELECT MAX(order_in_category) max_order 
                    FROM category_values 
                    WHERE category_id = ?";
            $max_order = DB::select($sql,[$data['category-id-2']]);
            $max_order = $max_order[0]->max_order + 1;
        }
        else {
            $max_order = $data['order-in-category'];
        }
        return CategoryValue::updateOrCreate(
                [
                    'id'=>$data['value-id']
                ],
                [
                    'value_code'=>strtoupper($data['value-code']),
                    'value_name'=>$data['value-name'],
                    'category_id'=>$data['category-id-2'],
                    'order_in_category'=>$max_order
                ]
        );
    }
}
