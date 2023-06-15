<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\District;
use DB;
use Illuminate\Support\Facades\Log;


class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = 'SELECT c.id,
                    c.customer_code,
                    c.customer_name,
                    c.contact_person,
                    c.phone,
                    c.fax,
                    c.address,
                    d.district_name,
                    c.untrade,
                    c.remarks
                FROM customers c,
                districts d
                WHERE c.district_id = d.id';
        $customers = DB::select($sql);
        //$customers = arrayPaginator($customers, $request);
        $districts = District::all();
        $sql = "SELECT cv.value_code,
                cv.value_name 
                FROM category_values cv,
                categories c
                WHERE c.id = cv.category_id 
                AND c.category_code = 'PYM'";
        $payment_methods = DB::select($sql);
        return view('settings.customer',['customers'=>$customers,'districts'=>$districts,'payment_methods'=>$payment_methods]);
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
        $customer = Customer::where('customer_id','NCW001')->first();
        //$msg = "This is a simple message.";
        //return $customer;
        return response()->json(array('customer'=> $customer), 200);
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


    public function getCustomer(Request $request)
    {
        $customer_code = $request->input('customerCode');
        $invoice_id = $request->input('invoice_id');
        $phone = $request->input('phone');
        $code_or_phone = $request->input('code_or_phone');
        if ($invoice_id == "")
            $invoice_id = 0;
        if ($code_or_phone == 'customer_code')
            $customer = $this->getByIDForInovice($customer_code);
        elseif ($code_or_phone == 'phone')
            $customer = $this->getByPhoneForInvoice($phone);
        $sql = "SELECT 1 FROM invoices WHERE customer_code = '$customer.customer_code' AND status = 'PREP' AND id <> $invoice_id";
        $duplicate_invoice = DB::select($sql);
        $duplicate_invoice = count($duplicate_invoice);
        return response()->json(array('customer'=> $customer,'duplicate_invoice'=> $duplicate_invoice), 200);
    
    }


    private function getByIDForInovice($customer_code){
        $customer = DB::table('customers')
                    ->join('districts','customers.district_id','districts.id')
                    ->join('category_values','category_values.value_code','customers.payment_method')
                    ->join('categories','categories.id','category_values.category_id')
                    ->select(
                        'customers.address',
                        'customers.remarks',
                        'customers.fax',
                        'customers.contact_person',
                        'customers.customer_name',
                        'customers.customer_code',
                        DB::raw("CONCAT(customers.phone,IF(phone_2 <> '','/',''),customers.phone_2,IF(customers.phone_3 <> '','/',''),phone_3) AS phone"),
                        'districts.*',
                        'category_values.value_name',
                        'customers.payment_method')
                    ->where('customers.customer_code',$customer_code)
                    ->where('categories.category_code','PYM')
                    ->where('untrade',0)
                    ->get();
        return $customer;
        //$customer = Customer::where('customer_code',$customer_code)->first();
        //$msg = "This is a simple message.";
        //return $customer;
        
        //
    }

    private function getByPhoneForInvoice($phone){
        $phones = explode('/',$phone);
        $customer = DB::table('customers')
                    ->join('districts','customers.district_id','districts.id')
                    ->join('category_values','category_values.value_code','customers.payment_method')
                    ->join('categories','categories.id','category_values.category_id')
                    ->select(
                        'customers.address',
                        'customers.remarks',
                        'customers.fax',
                        'customers.contact_person',
                        'customers.customer_name',
                        'customers.customer_code',
                        DB::raw("CONCAT(customers.phone,IF(phone_2 <> '','/',''),customers.phone_2,IF(customers.phone_3 <> '','/',''),phone_3) AS phone"),
                        'districts.*',
                        'category_values.value_name',
                        'customers.payment_method')
                    ->where('customers.phone',$phones[0])
                    ->where('customers.phone','<>','')
                    ->where('categories.category_code','PYM')
                    ->where('untrade',0)
                    ->get();
        return $customer;
        //$customer = Customer::where('customer_code',$customer_code)->first();
        //$msg = "This is a simple message.";
        //return $customer;
        
        //
    }

    public function getByID(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $customer = DB::table('customers')
                    ->join('districts','customers.district_id','districts.id')
                    ->select('customers.*','districts.district_code','districts.district_name')
                    ->where('customers.id',$customer_id)
                    ->get();
        //$customer = Customer::where('customer_code',$customer_code)->first();
        //$msg = "This is a simple message.";
        //return $customer;
        return response()->json(array('customer'=> $customer), 200);
        //
    }

    public function save(Request $request){
        $data = handleData($request->input('data'));
        $delivery_order = $data['delivery-order'];
        if ($delivery_order == 0){
            $sql = "SELECT MAX(delivery_order) delivery_order 
                    FROM customers 
                    WHERE district_id =".$data['district'];
            $delivery_order = DB::select($sql);
            $delivery_order = $delivery_order[0]->delivery_order + 1;
        }
        Customer::updateOrCreate(
                [
                    'id'=>$data['customer-id']
                ],
                [
                    'customer_code'=>strtoupper($data['customer-code']),
                    'customer_name'=>$data['customer-name'],
                    'contact_person'=>$data['contact-person'],
                    'phone'=>$data['phone'],
                    'fax'=>$data['fax'],
                    'address'=>$data['address'],
                    'district_id'=>$data['district'],
                    'delivery_order'=>$delivery_order,
                    'payment_method'=>$data['payment-method'],
                    'untrade'=>isset($data['untrade'])?1:0,
                    'remarks'=>$data['remarks']
                ]
        );
    }

    public function getDeliveryOrderByDistrictID(Request $request){
        $district_id = $request->input('district_id');
        $sql = "SELECT customer_code,
                customer_name 
                FROM customers 
                WHERE district_id = $district_id 
                ORDER BY delivery_order";
        $customers = DB::select($sql);
        $customers = stdClassArrToArray($customers);
        return response()->json(array('customers'=> $customers), 200);
    }

    public function saveDeliveryOrder(Request $request){
        $customers = $request->input('customer_order');
        if (!$customers)
            return;
        foreach($customers as $ind=>$customer){
            DB::table('customers')
                ->where('customer_code',$customer)
                ->update(['delivery_order'=> $ind+1]);
        }
    }

    public function getPhoneList(){
        $sql = "SELECT c.customer_code,
        c.customer_name,
        c.phone,
        c.phone_2,
        c.contact_person,
        d.district_name
        FROM customers c,
        districts d 
        WHERE c.district_id = d.id
        AND NOT c.untrade
        ORDER BY c.district_id, 
        c.customer_code";
        $phonelists = DB::select($sql);
        return view('pages.phoneList',['phonelists'=>$phonelists]);
    }

    public function autocomplete(Request $request)
    {
        $data = Customer::select("customer_code as name","customer_name as descr")
                ->where("customer_code","LIKE","%{$request->get('query')}%")
                ->where("untrade",0)
                ->get();
        
                return response()->json($data);
        
                
    }

    public function phoneAutocomplete(Request $request)
    {
        $data = Customer::select(DB::raw("CONCAT(phone,IF(phone_2 <> '','/',''),phone_2,IF(phone_3 <> '','/',''),phone_3) AS name"),DB::raw("CONCAT(customer_code,customer_name) AS descr"))
                ->where(function($query) use ($request){
                    $query->where("phone","LIKE","%{$request->get('query')}%")
                    ->orWhere("phone_2","LIKE","%{$request->get('query')}%")
                    ->orWhere("phone_3","LIKE","%{$request->get('query')}%");
                })
                ->where("untrade",0)
                ->get();
        
                return response()->json($data);
        
                
    }
}
