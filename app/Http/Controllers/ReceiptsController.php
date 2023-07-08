<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use DB;
use Carbon\Carbon;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.receipt');
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
     * @param  string  $receipt_code
     * @return \Illuminate\Http\Response
     */
    public function show($receipt_code)
    {
        //$receipt = Receipt::where('receipt_code',$receipt_code)->first()->toArray();
        $receipt = DB::table('receipts')
                    ->select('receipts.*')
                    ->where('receipts.receipt_code',$receipt_code)
                    ->first();
        //$receipt = stdClassArrToArray($receipt);
        if ($receipt){
            $receipt_items = ReceiptItem::where('receipt_id',$receipt->id)->orderBy('item_no')->get()->toArray();
            $receipt->receipt_items = stdClassArrToArray($receipt_items);
            $receipt->max_row = sizeof($receipt_items);
            // return view("pages.receipt", ["data"=>$receipt]);
        }
        // else return view('pages.receipt');
        return ["data"=>$receipt];
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

    public function exist($receipt_code){
        return Receipt::where('receipt_code',$receipt_code)
                        ->get()
                        ->count();
    }

    /*public function getDailyMaxReceiptNumber(){
        $max_receipt_code = Receipt::where(DB::raw('substr(receipt_date,1,10)'),date('Y-m-d'))
                        ->max('receipt_code');
        return ltrim(substr($max_receipt_code,-4),'0');
    }*/

    /*public function getDeliveryDate($district_code){
        $weekday = date('w');
        $time = date('Gis');
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
        if (isset($weekday_map[$weekday]) && $deliver_arr[0][$weekday_map[$weekday]] && $time<'160000')
            return date('Y-m-d');
        else {
            for ($i = $weekday+1,$j=1;$j<=6;$j++){
                if ($i>6)
                    $i = $i%6;
                if ($deliver_arr[0][$weekday_map[$i]]){
                    return  Carbon::now()
                    ->next($i)
                    ->toDateString();
                }
                $i++;
            }
        }

    }*/

    public function save(Request $request){
        if ($request->input('data') == null)
            return response("Invalid Data", 500);
        $data = $request->input('data');
        //return $this->exist($data['receipt-code']);
        if (!$this->exist($data['receipt-code'])){
            return $this->insert($data);
        }else{
            return $this->update($data);
        }
    }

    public function insert($data){
        //$max_receipt_num = $this->getDailyMaxReceiptNumber();
        //$delivery_date = ($data['delivery-date']=='')?$this->getDeliveryDate($data['district-code']):$data['delivery-date'];
        //$receipt_code = date('Ymd').str_pad(++$max_receipt_num,4,'0',STR_PAD_LEFT);
        $id = Receipt::insertGetId(
            [
                'receipt_code'=>$data['receipt-code'],
                'receipt_date'=>date('Y-m-d H:i:s'),
                'delivery_date'=>$data['delivery-date'],
                'supplier_code'=>strtoupper($data['supplier-code']),
                'supplier_name'=>$data['supplier-name'],
                'contact_person'=>$data['contact-person'],
                'phone'=>$data['phone'],
                'phone_2'=>$data['phone_2'],
                'fax'=>$data['fax'],
                'address'=>$data['address'],
                'email'=>$data['email'],
                'total_amount'=>str_replace(array('$',','),'',$data['receipt-total']),
                'remarks'=>$data['remarks'],
                'created_at'=>DB::raw('now()'),
                'updated_at'=>DB::raw('now()')

            ]
        );
        if ($id){
            $this->insertReceiptItem($data,$id,$data['receipt-code']);
            return response()->json(array('receipt_code'=> $data['receipt-code']), 200);
        }
    }

    public function insertReceiptItem($data,$receipt_id,$receipt_code){
        $order_no = 1;
        for ($i=1;$i<=$data['max-row'];$i++){
            if (isset($data[$i.'-total-cost']) && $data[$i.'-total-cost']){
                ReceiptItem::insert(
                        [
                            'item_no'=>$order_no,
                            'receipt_code'=>$receipt_code,
                            'category_name'=>$data[$i.'-category'],
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'product_name'=>$data[$i.'-product-name'],
                            'amount'=>$data[$i.'-amount'],
                            'unit'=>$data[$i.'-unit'],
                            'unit_cost'=>$data[$i.'-unit-cost'],
                            'total_cost'=>$data[$i.'-total-cost'],
                            'packing'=>$data[$i.'-packing'],
                            'receipt_id'=>$receipt_id,
                            'created_at'=>DB::raw('now()'),
                            'updated_at'=>DB::raw('now()')
                        ]
                );
                $order_no++;
            }
        }
        //$this->sortReceiptItem($receipt_code);
    }

    public function update($data){
        Receipt::where('receipt_code',$data['receipt-code'])
        ->update(
            [
                'delivery_date'=>$data['delivery-date'],
                'supplier_code'=>strtoupper($data['supplier-code']),
                'supplier_name'=>$data['supplier-name'],
                'contact_person'=>$data['contact-person'],
                'phone'=>$data['phone'],
                'phone_2'=>$data['phone_2'],
                'fax'=>$data['fax'],
                'address'=>$data['address'],
                'email'=>$data['email'],
                'total_amount'=>str_replace(array('$',','),'',$data['receipt-total']),
                'remarks'=>$data['remarks'],
                'updated_at'=>DB::raw('now()')

            ]
        );

            $this->updateReceiptItem($data,$data['receipt-code']);
            return response()->json(array('receipt_code'=> $data['receipt-code']), 200);

    }

    public function updateReceiptItem($data,$receipt_code){
        ReceiptItem::where('receipt_code',$receipt_code)->delete();
        $order_no = 1;
        for ($i=1;$i<=$data['max-row'];$i++){
            if (isset($data[$i.'-total-cost']) && $data[$i.'-total-cost']){
                ReceiptItem::insert(
                        [
                            'item_no'=>$order_no,
                            'receipt_code'=>$receipt_code,
                            'category_name'=>$data[$i.'-category'],
                            'product_code'=>strtoupper($data[$i.'-product-code']),
                            'product_name'=>$data[$i.'-product-name'],
                            'amount'=>$data[$i.'-amount'],
                            'unit'=>$data[$i.'-unit'],
                            'unit_cost'=>$data[$i.'-unit-cost'],
                            'total_cost'=>$data[$i.'-total-cost'],
                            'packing'=>$data[$i.'-packing'],
                            'receipt_id'=>$data['receipt-id'],
                            'created_at'=>DB::raw('now()'),
                            'updated_at'=>DB::raw('now()')
                        ]
                );
                $order_no++;
            }
        }
        //$this->sortReceiptItem($receipt_code);
    }

    /*public function sortReceiptItem($receipt_code){
        $sql = "SELECT o.id, o.product_code
                FROM orders o,
                categories c,
                category_values cv,
                product_categories pc
                WHERE o.product_code = pc.product_code
                AND pc.category_value_id = cv.id
                AND pc.category_id = c.id
                AND c.category_code = 'INV'
                AND o.receipt_code = $receipt_code
                ORDER BY cv.order_in_category,
                o.product_code";
        $receipt_items = DB::select($sql);
        $order_no = 1;
        foreach ($receipt_items as $receipt_item){
            Order::where('id',$receipt_item->id)
                 ->update(
                    [
                        'order_no'=>$order_no
                    ]
                    );
            $order_no++;
        }
    }*/

    public function getNext(Request $request){
        $receipt_id = $request->query('receiptId');
        $all_or_supplier = $request->query('allOrSupplier');
        $supplier_code = $request->query('supplierCode');
        if ($all_or_supplier == 'supplier')
            $where_clause = ' AND supplier_code=:supplier_code';
        else $where_clause = '';
        $sql = 'SELECT receipt_code
                FROM receipts
                WHERE id=(
                    SELECT MIN(id)
                    FROM receipts
                    WHERE id>:receipt_id'.$where_clause.') ';
        $criteria = array("receipt_id"=>$receipt_id);
        if ($all_or_supplier == 'supplier')
            $criteria['supplier_code'] = $supplier_code;
        $next_receipt_code = DB::select($sql,$criteria);
        if ($next_receipt_code){
            $next_receipt_code = stdClassArrToArray($next_receipt_code);
            return $next_receipt_code[0]['receipt_code'];
        }else return 0;
    }

    public function getPrev(Request $request){
        $receipt_id = $request->query('receiptId');
        $all_or_supplier = $request->query('allOrSupplier');
        $supplier_code = $request->query('supplierCode');
        //$receipt_code = '201904200004';
        if (!$receipt_id){
            $receipt_id = '999999999999';
        }
        if ($all_or_supplier == 'supplier')
            $where_clause = ' AND supplier_code=:supplier_code';
        else $where_clause = '';
        $sql = 'SELECT receipt_code
                FROM receipts
                WHERE id=(
                    SELECT MAX(id)
                    FROM receipts
                    WHERE id<:receipt_id'.$where_clause.') ';

        $criteria = array("receipt_id"=>$receipt_id);
        if ($all_or_supplier == 'supplier')
            $criteria['supplier_code'] = $supplier_code;

        $prev_receipt_code = DB::select($sql,$criteria);
        if ($prev_receipt_code){
            $prev_receipt_code = stdClassArrToArray($prev_receipt_code);
            return $prev_receipt_code[0]['receipt_code'];
        }else return 0;
    }

    /*public function getByDeliveryDate($date){
        $date = '2019-03-15';
        $sql = 'SELECT id,
        receipt_code,
        receipt_date,
        delivery_date,
        supplier_code,
        cusomter_name
        FROM receipts
        WHERE delivery_date=:delivery_date';
        $criteria = array('delivery_date'=>$date);
    }*/

    public function getSearchResult(Request $request){     
        $data['receipt_code'] = $request->input('receipt-code');
        $data['receipt_date'] = $request->input('receipt-date');
        $data['delivery_date'] = $request->input('delivery-date');
        $data['supplier_code'] = $request->input('supplier-code');
        $data['supplier_name'] = $request->input('supplier-name');

        
        $order_by = "";
        $sql = "SELECT i.receipt_code,
                i.receipt_date,
                i.delivery_date,
                i.supplier_code,
                i.supplier_name,
                i.total_amount
                FROM receipts i
                WHERE 1 ";
        if ($data['receipt_code']){
            $sql .= " AND i.receipt_code like '%".$data['receipt_code']."%'";
        }
        if ($data['receipt_date']){
            $sql .= " AND i.receipt_date like '%".$data['receipt_date']."%'";
        }
        if ($data['delivery_date']){
            $sql .= " AND i.delivery_date like '%".$data['delivery_date']."%'";
        }
        if ($data['supplier_code']){
            $sql .= " AND i.supplier_code like '%".$data['supplier_code']."%'";
        }
        if ($data['supplier_name']){
            $sql .= " AND i.supplier_name like '%".$data['supplier_name']."%'";
        }


        if (!$order_by)
            $order_by = " ORDER BY i.delivery_date desc";
        $sql .= $order_by;
        $receipts = DB::select($sql);
        if ($receipts){
            //$search_result = stdClassArrToArray($search_result);
            //$receipts = arrayPaginator($receipts, $request);
            return ["data"=>$data,"receipts"=>$receipts];
        }else return ["data"=>$data,"receipts"=>0];


    }

    /*public function print($check_all,$receipt_list = null,$receipt_code = null, $receipt_date = null, $delivery_date = null, $supplier_code = null, $supplier_name = null, $district_code = null,$car_no = null){
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
        $sql = "SELECT i.receipt_code,
        i.supplier_code,
        i.supplier_name,
        i.delivery_date,
        i.phone,
        i.remarks,
        cv.value_name
        FROM receipts i,
        districts d,
        suppliers c,
        category_values cv,
        categories cat
        WHERE i.district_code = d.district_code
        AND c.supplier_code = i.supplier_code
        AND cat.id = cv.category_id
        AND cv.value_code = i.payment_method
        AND cat.category_code = 'PYM'";
        if ($check_all=="true"){
            if ($receipt_list !== "null")
                $sql .= " AND i.receipt_code NOT IN ($receipt_list)";
        }else{
            $sql .= " AND i.receipt_code IN ($receipt_list)";
        }
        if ($receipt_code !== "null")
            $sql .= " AND i.receipt_code like '%$receipt_code%'";
        if ($receipt_date !== "null")
            $sql .= " AND i.receipt_date like '%$receipt_date%'";
        if ($delivery_date !== "null"){
            $weekday = $weekday_map[date('w', strtotime($delivery_date))];
            $order_by = " ORDER BY d.car_no_$weekday, d.order_in_car_$weekday, i.district_code,c.delivery_order";
            $sql .= " AND i.delivery_date like '%$delivery_date%'";
        }
        if ($supplier_name !== "null")
            $sql .= " AND i.supplier_name like '%$supplier_name%'";
        if ($supplier_code !== "null")
            $sql .= " AND i.supplier_code like '%$supplier_code%'";
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
        $receipts = DB::select($sql);
        if ($receipts){
            $receipts = stdClassArrToArray($receipts);
            foreach ($receipts as $ind=>$receipt){
                $sql = "SELECT product_code,
                product_name,
                unit,
                unit_price,
                amount,
                discount,
                total_price
                FROM orders
                WHERE receipt_code=:receipt_code
                ORDER BY order_no";
                $criteria = array(
                    'receipt_code' => $receipt['receipt_code']
                );
                $orders = DB::select($sql,$criteria);
                $receipts[$ind]['orders'] = stdClassArrToArray($orders);
            }

        }
        $data = ["receipts" => $receipts];
        //var_dump($receipts);
        $pdf = PDF::loadView('pdf.myPDF', $data);

        //return $pdf->download('itsolutionstuff.pdf');
        return $pdf->stream();
    }*/

    /*public function void(Request $request){
        $receipt_code = $request->input('receipt_code');
        DB::table('receipts')
        ->where('receipt_code',$receipt_code)
        ->update(['status'=>'VOID']);
    }*/



}
