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
        return view('settings.category',['categories'=>$categories]);
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

    public function getByID(Request $request)
    {
        $category_id = $request->input('category_id');
        $category = Category::where('id',$category_id)
                    ->get();
        //$category = category::where('category_code',$category_code)->first();
        //$msg = "This is a simple message.";
        //return $category;
        return response()->json(array('category'=> $category), 200);
        //
    }

    public function save(Request $request){
        $data = handleData($request->input('data'));
        Category::updateOrCreate(
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

    public function getCategoryValueByCategoryID(Request $request){
        $category_id = $request->input('category_id');
        $sql = "SELECT id,
                value_code,
                value_name,
                (SELECT MAX(order_in_category) FROM category_values 
                WHERE category_id = $category_id) max_order
                FROM category_values 
                WHERE category_id = $category_id
                ORDER BY order_in_category";
        $category_values = DB::select($sql);
        $category_values = stdClassArrToArray($category_values);
        return response()->json(array('category_values' => $category_values),200);
    }

    public function saveCategoryOrder(Request $request){
        $category_values = $request->input('category_order');
        if ($category_values){
            foreach($category_values as $ind=>$category_value){
                DB::table('category_values')
                    ->where('id',$category_value)
                    ->update(['order_in_category'=> $ind+1]);
            }
        }
    }

    public function getCategoryValueByID(Request $request)
    {
        $category_value_id = $request->input('category_value_id');
        $category_value = CategoryValue::where('id',$category_value_id)
                    ->get();
        //$category = category::where('category_code',$category_code)->first();
        //$msg = "This is a simple message.";
        //return $category;
        return response()->json(array('category_value'=> $category_value), 200);
        //
    }

    public function saveCategoryValue(Request $request){
        $data = handleData($request->input('data'));
        if (!$data['order-in-category']){
            $sql = "SELECT MAX(order_in_category) max_order 
                    FROM category_values 
                    WHERE category_id = ".$data['category-id-2'];
            $max_order = DB::select($sql);
            $max_order = $max_order[0]->max_order + 1;
        }
        else {
            $max_order = $data['order-in-category'];
        }
        CategoryValue::updateOrCreate(
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
