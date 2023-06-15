<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use DB;
use App\Models\Category;
use App\Models\District;
use App\Models\ProductCategory;
use PDF;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = "SELECT p.*,
                cv.value_name 
                FROM products p,
                category_values cv 
                WHERE cv.id = p.category_value_id";
        $products = DB::select($sql);
        //$products = arrayPaginator($products, $request);
        $cat_sql = "SELECT id,
                    value_code,
                    value_name 
                    FROM category_values 
                    WHERE category_id = 3 
                    ORDER BY order_in_category";
        $categories = DB::select($cat_sql);
        $other_cat_sql =    "SELECT id,
                            category_code,
                            category_name
                            FROM categories
                            WHERE for_product = 1";
        $other_categories = DB::select($other_cat_sql);
        //$categories = stdClassArrToArray($categories);
        return view('settings.product',['products'=>$products,'categories'=>$categories,'other_categories'=>$other_categories]);
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

    public function getProduct(Request $request)
    {
        $product_code = strtoupper($request->input('productCode'));
        $customer_code = strtoupper($request->input('customerCode'));
        $cust_selling_price = DB::table('customer_selling_prices')
                    ->where('customer_selling_prices.product_code',$product_code)
                    ->where('customer_selling_prices.customer_code',$customer_code)
                    ->get();
        
        $product = DB::table('products')
                    ->join('category_values','category_values.id','products.category_value_id')
                    ->select('products.*','category_values.value_name')
                    ->where('products.product_code',$product_code)
                    ->get();
        if (sizeof($cust_selling_price) > 0)
            $product[0]->unit_price = $cust_selling_price[0]->unit_price;
        //Log::debug($product);
        //Log::debug($cust_selling_price);
        //$product = Product::where('product_code',$product_code)->first();
        //$msg = "This is a simple message.";
        //return $customer;
        return response()->json(array('product'=> $product), 200);
    }

    public function getProductLast5Order(Request $request){
        $product_code = $request->input('product_code');
        $customer_code = $request->input('customer_code');
        $invoice_id = $request->input('invoice_id');
        $sql = "SELECT inv.invoice_code,
                    inv.delivery_date,
                    o.amount,
                    o.unit
                    FROM invoices inv,
                    orders o
                    WHERE inv.invoice_code = o.invoice_code
                    AND inv.customer_code = :customer_code
                    AND o.product_code = :product_code
                    AND inv.id < :invoice_id
                    AND inv.status <> 'VOID'
                    ORDER bY inv.delivery_date DESC
                    limit 5";
        $criteria = array(
            "product_code"=>$product_code,
            "customer_code"=>$customer_code,
            "invoice_id"=>$invoice_id
        );
        if ($product = DB::select($sql,$criteria)){
            $product = stdClassArrToArray($product);
            return $product[0]['delivery_date']?json_encode($product):'沒有紀錄。';
        }
        else return "沒有紀錄。";
    }

    public function getByID(Request $request)
    {
        $product_id = $request->input('product_id');
        $product = DB::table('products')
                    ->join('category_values','products.category_value_id','category_values.id')
                    ->select('products.*','category_values.value_code','category_values.value_name')
                    ->where('products.id',$product_id)
                    ->get();
        $sql = "SELECT pc.category_id,
                pc.category_value_id 
                FROM product_categories  pc,
                categories c
                WHERE pc.product_id = $product_id
                AND c.id = pc.category_id
                AND c.for_product = 1";
        $category_values = DB::select($sql);
        $category_values = stdClassArrToArray($category_values);
        //$customer = Customer::where('customer_code',$customer_code)->first();
        //$msg = "This is a simple message.";
        //return $customer;
        return response()->json(array('product'=> $product,'category_values'=>$category_values), 200);
        //
    }

    public function save(Request $request){
        $data = handleData($request->input('data'));
        $id = Product::updateOrCreate(
                [
                    'id'=>$data['product-id']
                ],
                [
                    'product_code'=>strtoupper($data['product-code']),
                    'product_name'=>$data['product-name'],
                    'unit'=>$data['unit'],
                    'unit_price'=>$data['unit-price'],
                    'unit_cost'=>$data['unit-cost'],
                    'packing'=>$data['packing'],
                    'remarks'=>$data['remarks'],
                    'count_inventory'=>isset($data['count-inventory'])?1:0,
                    'category_value_id'=>$data['category']
                ]
        )->id;
        ProductCategory::where('product_id', '=', $id)->delete();
        for($i=1;$i<5;$i++){
            if (isset($data[$i.'-category-id']) && isset($data[$i.'-category-value-id'])){
                ProductCategory::updateOrCreate(
                    [
                        'category_id' => $data[$i.'-category-id'],
                        'product_id' => $id
                    ],
                    [
                        'category_value_id' => $data[$i.'-category-value-id'],
                        'product_code'=>strtoupper($data['product-code'])
                    ]
                );
            }
        }
    }

    public function genPreparationList(Request $request){
        if ($request->input('delivery-date'))
            $delivery_date = $request->input('delivery-date');
        //else $delivery_date = date('Y-m-d');
        else $delivery_date = '';
        if ($request->input('district-code')){
            $district_codes = $request->input('district-code');
            foreach ($district_codes as $ind=>$district_code){
                $district_codes[$ind] = "'".$district_code."'";
            }
            $district_code = implode(",",$district_codes);
        }
        else $district_code ='';
            
        
        $sql = "SELECT cv.value_name,
                o.product_code,
                o.product_name,
                o.amount
                FROM invoices i, 
                orders o,  
                product_categories pc, 
                category_values cv,
                categories c
                WHERE i.id = o.invoice_id
                AND pc.product_code = o.product_code
                AND cv.id = pc.category_value_id
                AND c.id = pc.category_id 
                AND c.category_code = 'PRE'
                AND i.delivery_date = '$delivery_date'";
        if ($district_code)
            $sql .= "AND i.district_code IN ($district_code)";
        $sql .= "ORDER BY cv.order_in_category,
                o.product_name,
                o.amount";
        $preparationlists = DB::select($sql);
        $data = ["preparationlists"=>$preparationlists, "delivery_date"=>$delivery_date,"district_code"=>$district_code];
        //var_dump($invoices);
        $pdf = PDF::loadView('pdf.preparationlistPDF', $data);

        //return $pdf->download('itsolutionstuff.pdf');
        return $pdf->stream();
    }

    public function autocomplete(Request $request)
    {
        $data = Product::select("product_code as name","product_name as descr")
                ->where("product_code","LIKE","%{$request->get('query')}%")
                ->get();

                /*$dataModified = array();
                foreach ($datas as $data)
                {
                    $dataModified[] = $data->product_code;
                }*/
        
                return response()->json($data);
        
                
    }

    public function checkDuplicateProductOnSameDeliveryDate(Request $request){
        $invoice_id = ($request->input('invoice_id'))?$request->input('invoice_id'):0;
        $product_code = $request->input('productCode');
        $customer_code = $request->input('customer_code');
        $delivery_date = $request->input('delivery_date');
        $sql = "SELECT 1
                FROM invoices i,
                orders o
                WHERE i.invoice_code = o.invoice_code
                AND i.id <> :invoice_id
                AND o.product_code = :product_code
                AND i.customer_code = :customer_code
                AND i.delivery_date = :delivery_date 
                AND i.status <> 'VOID'";
        $criteria = array(
            'invoice_id' => $invoice_id,
            'product_code' => strtoupper($product_code),
            'customer_code' => strtoupper($customer_code),
            'delivery_date' => $delivery_date
        );
       // return json_encode($criteria);
        if ($result = DB::select($sql,$criteria)){
            return 1;
        }
        else return 0;


    }
}
