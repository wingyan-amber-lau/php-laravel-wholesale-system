<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\District;
use App\Models\CustomerSellingPrice;
use DB;
use PDF;
use Carbon\Carbon;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice_setting = DB::table('invoice_settings')
        ->select('text')
        ->first();
        return ['invoice_message' => $invoice_setting->text];
        // return view('settings.invoice',['invoice_message' => $invoice_setting->text]);
    }

    public function saveSetting(Request $request){
        if ($request->input('data') == null)
            return response("Invalid data",500);
        // $data = handleData($request->input('data'));
        $data = $request->input('data');
        return DB::table('invoice_settings')
        ->update(
            [
                'text'=>$data['invoice-message'],
            ]
        );
         //   return response()->json(array('invoice_code'=> $invoice_code), 200);
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
     * @param  string  $invoice_code
     * @return \Illuminate\Http\Response
     */
    public function show($invoice_code)
    {
        //$invoice = Invoice::where('invoice_code',$invoice_code)->first()->toArray();
        $invoice = DB::table('invoices')
                    ->join('category_values','invoices.payment_method','category_values.value_code')
                    ->join('categories','categories.id','category_values.category_id')
                    ->select('invoices.*','category_values.value_name')
                    ->where('categories.category_code','PYM')
                    ->where('invoices.invoice_code',$invoice_code)
                    ->first();
        //$invoice = stdClassArrToArray($invoice);
        if ($invoice){
            $order = Order::where('invoice_id',$invoice->id)->orderBy('order_no')->get()->toArray();
            $invoice->order = stdClassArrToArray($order);
            $invoice->max_row = sizeof($order);
            // return view("pages.order", ["data"=>$invoice]);
        }
        // else return view('pages.order');
        return  ["data"=>$invoice];
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
    // public function update(Request $request, $id)
    // {
    //     //
    // }

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

    public function exist($invoice_code){
        return Invoice::where('invoice_code',$invoice_code)
                        ->get()
                        ->count();
    }

    public function getDailyMaxInvoiceNumber(){
        $max_invoice_code = Invoice::where(DB::raw('substr(invoice_date,1,10)'),date('Y-m-d'))
                        ->max('invoice_code');
        return ltrim(substr($max_invoice_code,-4),'0');
    }

    public function getDeliveryDate($district_code){
        $sql = 'SELECT mon,
                    tue,
                    wed,
                    thu,
                    fri,
                    sat
                FROM districts
                WHERE district_code = :district_code';
        $criteria = array(
            'district_code' => $district_code
        );
        $district = DB::select($sql,$criteria);
        $deliver_arr = stdClassArrToArray($district);
        $weekday_map = array(
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
        );
        $date = date("Y-m-d");

        while (!$this->isWorkingDay($date) || !$this->isDeliveryDay($weekday_map,$deliver_arr,$date)){
            $date = date('Y-m-d', strtotime($date . " +1 days"));
        }
        return $date;
    }

    private function isDeliveryDay($weekday_map,$deliver_arr,$date){
        $weekday = date('w',strtotime($date));
        
        if (strtotime($date) == strtotime(date("Y-m-d"))) //if today,also consider time 
            return (isset($weekday_map[$weekday]) && $deliver_arr[0][$weekday_map[$weekday]] && date('Gis') < '160000');
        return (isset($weekday_map[$weekday]) && $deliver_arr[0][$weekday_map[$weekday]]);
    }

    private function isWorkingDay($date){
        if (date('w',strtotime($date)) == 0)
            return false;
        $sql = 'SELECT 1 FROM holidays WHERE holiday_date = :holiday_date';
        $criteria = array(
            'holiday_date' => $date
        );
        $holiday = DB::select($sql,$criteria);
        return (sizeof($holiday) == 0);
    }

    public function getDeliveryDateFromRequest(Request $request){
        return $this->getDeliveryDate($request->query('districtCode'));
    }

    public function save(Request $request){
        if ($request->input('data') == null)
            return response("Invalid Data", 500);
        // $data = handleData($request->input('data'));
        $data = $request->input('data');
        //return $this->exist($data['invoice-code']);
        if (!$this->exist(substr($data['invoice-code'],3))){
            return $this->insert($data);
        }else{
            return $this->update($data);
        }
    }

    public function insert($data){
        $max_invoice_num = $this->getDailyMaxInvoiceNumber();
        $delivery_date = ($data['delivery-date']=='')?$this->getDeliveryDate($data['district-code']):$data['delivery-date'];
        $invoice_code = date('Ymd').str_pad(++$max_invoice_num,4,'0',STR_PAD_LEFT);
        $id = Invoice::insertGetId(
            [
                'invoice_code'=>$invoice_code,
                'invoice_date'=>date('Y-m-d H:i:s'),
                'delivery_date'=>$delivery_date,
                'customer_code'=>strtoupper($data['customer-code']),
                'customer_name'=>$data['customer-name'],
                'payment_method'=>$data['payment-method'],
                'contact_person'=>$data['contact-person'],
                'phone'=>$data['phone'],
                'fax'=>'',
                'address'=>$data['address'],
                'district_name'=>$data['district-name'],
                'district_code'=>$data['district-code'],
                'total_amount'=>str_replace(array('$',','),'',$data['invoice-total']),
                'remarks'=>$data['remarks'],
                'created_at'=>DB::raw('now()'),
                'updated_at'=>DB::raw('now()')

            ]
        );
        if ($id){
            $this->insertInvoiceItem($data,$id,$invoice_code);
            return response()->json(array('invoice_code'=> $invoice_code), 200);
        }
    }

    public function insertInvoiceItem($data,$invoice_id,$invoice_code){
        $order_no = 1;
        for ($i=1;$i<=$data['max-row'];$i++){
            if (isset($data[$i.'-total-price']) && $data[$i.'-total-price']){
                Order::insert(
                        [
                            'order_no'=>$order_no,
                            'invoice_code'=>$invoice_code,
                            'category_name'=>$data[$i.'-category'],
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'product_name'=>$data[$i.'-product-name'],
                            'amount'=>str_replace('*','個',$data[$i.'-amount']),
                            'unit'=>$data[$i.'-unit'],
                            'unit_price'=>$data[$i.'-unit-price'],
                            'unit_cost'=>$data[$i.'-unit-cost'],
                            'discount'=>($data[$i.'-discount'])?$data[$i.'-discount']:0,
                            'discount_once'=>isset($data[$i.'-discount-once'])?1:0,
                            'total_price'=>$data[$i.'-total-price'],
                            'total_cost'=>(int)$data[$i.'-unit-cost'] * (int)$data[$i.'-amount'],
                            'packing'=>$data[$i.'-packing'],
                            'invoice_id'=>$invoice_id,
                            'created_at'=>DB::raw('now()'),
                            'updated_at'=>DB::raw('now()')
                        ]
                );
                if ($data[$i.'-discount'] > 0 && !isset($data[$i.'-discount-once'])){
                    CustomerSellingPrice::updateOrCreate(
                        [
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'customer_code'=>strtoupper($data['customer-code'])
                        ],
                        [
                            'unit_price'=>$data[$i.'-unit-price'] - (($data[$i.'-discount'])?$data[$i.'-discount']:0),
                            'updated_by'=>Auth::user()->username
                        ]
                    );
                }
                $order_no++;
                
            }
        }
        $this->sortInvoiceItem($invoice_code);
    }

    public function update($data){
        $invoice_code = substr($data['invoice-code'],3);
        Invoice::where('invoice_code',$invoice_code)
        ->update(
            [
                'delivery_date'=>$data['delivery-date'],
                'customer_code'=>strtoupper($data['customer-code']),
                'customer_name'=>$data['customer-name'],
                'contact_person'=>$data['contact-person'],
                'phone'=>$data['phone'],
                'fax'=>'',
                'address'=>$data['address'],
                'district_name'=>$data['district-name'],
                'district_code'=>$data['district-code'],
                'total_amount'=>str_replace(array('$',','),'',$data['invoice-total']),
                'remarks'=>$data['remarks'],
                'updated_at'=>DB::raw('now()')

            ]
        );

            $this->updateInvoiceItem($data,$invoice_code);
            return response()->json(array('invoice_code'=> $invoice_code), 200);

    }

    public function updateInvoiceItem($data,$invoice_code){
        Order::where('invoice_code',$invoice_code)->delete();
        $order_no = 1;
        for ($i=1;$i<=$data['max-row'];$i++){
            if (isset($data[$i.'-total-price']) && $data[$i.'-total-price']){
                Order::insert(
                        [
                            'order_no'=>$order_no,
                            'invoice_code'=>$invoice_code,
                            'category_name'=>$data[$i.'-category'],
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'product_name'=>$data[$i.'-product-name'],
                            'amount'=>str_replace('*','個',$data[$i.'-amount']),
                            'unit'=>$data[$i.'-unit'],
                            'unit_price'=>$data[$i.'-unit-price'],
                            'unit_cost'=>$data[$i.'-unit-cost'],
                            'discount'=>($data[$i.'-discount'])?$data[$i.'-discount']:0,
                            'discount_once'=>isset($data[$i.'-discount-once'])?1:0,
                            'total_price'=>$data[$i.'-total-price'],
                            'total_cost'=>(int)$data[$i.'-unit-cost'] * (int)$data[$i.'-amount'],
                            'packing'=>$data[$i.'-packing'],
                            'invoice_id'=>$data['invoice-id'],
                            'created_at'=>DB::raw('now()'),
                            'updated_at'=>DB::raw('now()')
                        ]
                );
                if ($data[$i.'-discount'] > 0 && !isset($data[$i.'-discount-once'])){
                    CustomerSellingPrice::updateOrCreate(
                        [
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'customer_code'=>strtoupper($data['customer-code'])
                        ],
                        [
                            'unit_price'=>$data[$i.'-unit-price'] - (($data[$i.'-discount'])?$data[$i.'-discount']:0),
                            'updated_by'=>Auth::user()->username
                        ]
                    );
                }
                $order_no++;
            }
        }
        $this->sortInvoiceItem($invoice_code);
    }

    public function sortInvoiceItem($invoice_code){
        $sql = "SELECT o.id, o.product_code
                FROM orders o,
                categories c,
                category_values cv,
                product_categories pc
                WHERE o.product_code = pc.product_code
                AND pc.category_value_id = cv.id
                AND pc.category_id = c.id
                AND c.category_code = 'INV'
                AND o.invoice_code = $invoice_code
                ORDER BY cv.order_in_category,
                o.product_code";
        $invoice_items = DB::select($sql);
        $order_no = 1;
        foreach ($invoice_items as $invoice_item){
            Order::where('id',$invoice_item->id)
                 ->update(
                    [
                        'order_no'=>$order_no
                    ]
                    );
            $order_no++;
        }
    }

    public function getNext(Request $request){
        $invoice_code = $request->query('invoiceCode');
        $all_or_customer = $request->query('allOrCustomer');
        $customer_code = $request->query('customerCode');
        if ($all_or_customer == 'customer')
            $where_clause = ' AND customer_code=:customer_code';
        else $where_clause = '';
        $sql = 'SELECT invoice_code
                FROM invoices
                WHERE id=(
                    SELECT MIN(id)
                    FROM invoices
                    WHERE invoice_code>:invoice_code'.$where_clause.') ';
        $criteria = array("invoice_code"=>$invoice_code);
        if ($all_or_customer == 'customer')
            $criteria['customer_code'] = $customer_code;
        $next_invoice_code = DB::select($sql,$criteria);
        if ($next_invoice_code){
            $next_invoice_code = stdClassArrToArray($next_invoice_code);
            return $next_invoice_code[0]['invoice_code'];
        }else return 0;
    }

    public function getPrev(Request $request){
        $invoice_code = $request->input('invoiceCode');
        $all_or_customer = $request->input('allOrCustomer');
        $customer_code = $request->input('customerCode');
        //$invoice_code = '201904200004';
        if (!$invoice_code){
            $invoice_code = '999999999999';
        }
        if ($all_or_customer == 'customer')
            $where_clause = ' AND customer_code=:customer_code';
        else $where_clause = '';
        $sql = 'SELECT invoice_code
                FROM invoices
                WHERE id=(
                    SELECT MAX(id)
                    FROM invoices
                    WHERE invoice_code<:invoice_code'.$where_clause.') ';

        $criteria = array("invoice_code"=>$invoice_code);
        if ($all_or_customer == 'customer')
            $criteria['customer_code'] = $customer_code;

        $prev_invoice_code = DB::select($sql,$criteria);
        if ($prev_invoice_code){
            $prev_invoice_code = stdClassArrToArray($prev_invoice_code);
            return $prev_invoice_code[0]['invoice_code'];
        }else return 0;
    }

    public function getByDeliveryDate($date){
        $date = '2019-03-15';
        $sql = 'SELECT id,
        invoice_code,
        invoice_date,
        delivery_date,
        customer_code,
        cusomter_name
        FROM invoices
        WHERE delivery_date=:delivery_date';
        $criteria = array('delivery_date'=>$date);
    }

    public function getSearchResult(Request $request){
        $weekday_map = array(
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
        );
        
        $data['invoice_code'] = $request->input('invoice-code');
        $data['invoice_date'] = $request->input('invoice-date');
        $data['delivery_date'] = $request->input('delivery-date');
        $data['customer_code'] = $request->input('customer-code');
        $data['customer_name'] = $request->input('customer-name');
        $data['district_code'] = $request->input('district-code');
        $data['car_no'] = $request->input('car-no');

        
        $order_by = "";
        $sql = "SELECT i.invoice_code,
                i.invoice_date,
                i.delivery_date,
                i.customer_code,
                i.customer_name,
                i.district_name,
                i.status
                FROM invoices i,
                districts d,
                customers c
                WHERE i.district_code = d.district_code 
                AND c.customer_code = i.customer_code";
        if ($data['invoice_code']){
            $sql .= " AND i.invoice_code like '%".$data['invoice_code']."%'";
        }
        if ($data['invoice_date']){
            $sql .= " AND i.invoice_date like '%".$data['invoice_date']."%'";
        }
        if ($data['delivery_date']){
            $weekday = $weekday_map[date('w', strtotime($data['delivery_date']))];
            $sql .= " AND i.delivery_date like '%".$data['delivery_date']."%'";
            $order_by = " ORDER BY d.car_no_$weekday, d.order_in_car_$weekday, i.district_code,c.delivery_order";
        }
        if ($data['customer_code']){
            $sql .= " AND i.customer_code like '%".$data['customer_code']."%'";
        }
        if ($data['customer_name']){
            $sql .= " AND i.customer_name like '%".$data['customer_name']."%'";
        }
        if ($data['district_code']){
            foreach ($data['district_code'] as $ind=>$district_code){
                $data['district_code'][$ind] = "'".$district_code."'";
            }
            $data['district_code'] = implode(",",$data['district_code']);    
            $sql .= " AND i.district_code in (".$data['district_code'].")";
        }
        if ($data['car_no']){
            $sql .= " AND d.car_no_".$weekday." = ".$data['car_no'];
            $order_by = " ORDER BY d.order_in_car_$weekday, i.district_code,c.delivery_order";
        }

        if (!$order_by)
            $order_by = " ORDER BY i.delivery_date desc,i.district_code,c.delivery_order";
        $sql .= $order_by;
        $invoices = DB::select($sql);
        if ($invoices){
            //$search_result = stdClassArrToArray($search_result);
            //$invoices = arrayPaginator($invoices, $request);
            return ["data"=>$data,"invoices"=>$invoices,"districts"=>District::all()];
        }else return ["data"=>$data,"invoices"=>0,"districts"=>District::all()];


    }

    public function print($check_all,$invoice_list = null,$invoice_code = null, $invoice_date = null, $delivery_date = null, $customer_code = null, $customer_name = null, $district_code = null,$car_no = null){
        set_time_limit(3000);
        ini_set('memory_limit', '256M');
        $weekday_map = array(
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
        );

        $order_by = "";
        $sql = "SELECT i.invoice_code,
        i.customer_code,
        i.customer_name,
        i.delivery_date,
        i.phone,
        i.remarks,
        cv.value_name
        FROM invoices i,
        districts d,
        customers c,
        category_values cv,
        categories cat
        WHERE i.district_code = d.district_code
        AND c.customer_code = i.customer_code
        AND cat.id = cv.category_id
        AND cv.value_code = i.payment_method
        AND cat.category_code = 'PYM'";
        if ($check_all=="true"){
            if ($invoice_list !== "null")
                $sql .= " AND i.invoice_code NOT IN ($invoice_list)";
        }else{
            $sql .= " AND i.invoice_code IN ($invoice_list)";
        }
        if ($invoice_code !== "null")
            $sql .= " AND i.invoice_code like '%$invoice_code%'";
        if ($invoice_date !== "null")
            $sql .= " AND i.invoice_date like '%$invoice_date%'";
        if ($delivery_date !== "null"){
            $weekday = $weekday_map[date('w', strtotime($delivery_date))];
            $order_by = " ORDER BY d.car_no_$weekday, d.order_in_car_$weekday, i.district_code,c.delivery_order";
            $sql .= " AND i.delivery_date like '%$delivery_date%'";
        }
        if ($customer_name !== "null")
            $sql .= " AND i.customer_name like '%$customer_name%'";
        if ($customer_code !== "null")
            $sql .= " AND i.customer_code like '%$customer_code%'";
        if ($district_code !== "null"){  
            $sql .= " AND i.district_code in (".$district_code.")";
        }
    
        if ($car_no !== "null"){
            $sql .= " AND d.car_no_$weekday = $car_no";
            $order_by = " ORDER BY d.order_in_car_$weekday, i.district_code,c.delivery_order";
        }
        if (!$order_by)
            $order_by = " ORDER BY i.delivery_date desc,i.district_code,c.delivery_order";
        $sql .= $order_by;
        $invoices = DB::select($sql);
        if ($invoices){
            $invoices = stdClassArrToArray($invoices);
            foreach ($invoices as $ind=>$invoice){
                $sql = "SELECT product_code,
                product_name,
                unit,
                unit_price,
                amount,
                discount,
                total_price
                FROM orders
                WHERE invoice_code=:invoice_code
                ORDER BY order_no";
                $criteria = array(
                    'invoice_code' => $invoice['invoice_code']
                );
                $orders = DB::select($sql,$criteria);
                $invoices[$ind]['orders'] = stdClassArrToArray($orders);
            }

        }
        $invoice_setting = DB::table('invoice_settings')
        ->select('text')
        ->first();
        $data = ["invoices" => $invoices,"message" => $invoice_setting->text];
        //var_dump($invoices);
        $pdf = PDF::loadView('pdf.myPDF', $data);

        //return $pdf->download('itsolutionstuff.pdf');
        return $pdf->stream();
    }

    public function void(Request $request){
        $invoice_code = $request->input('invoice_code');
        return DB::table('invoices')
        ->where('invoice_code',$invoice_code)
        ->update(['status'=>'VOID']);
    }

    public function test(){
       
        DB::tales('tests')->insert([1]);
    }



}
