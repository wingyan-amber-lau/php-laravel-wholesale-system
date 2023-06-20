<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\District;
use DB;

class ChecklistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function getByDate(Request $request){
        $weekday_map = array(
            0 => 'sun',
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
        );

        if ($request->input('delivery-date'))
            $delivery_date = $request->input('delivery-date');
        else $delivery_date = date('Y-m-d');
       // else $delivery_date = '2019-03-15';
        $weekday = $weekday_map[date('w', strtotime($delivery_date))];
        $checklists = array();
        if ($weekday!='sun'){
            $sql = "
            WITH delivery_list AS (
                SELECT i.customer_name,
                i.invoice_code,
                i.payment_status,
                i.status,
                d.car_no_$weekday car_no,
                i.district_name
                FROM invoices i, 
                customers c, 
                districts d,
                orders o
                WHERE i.customer_code = c.customer_code
                AND d.district_code = i.district_code
                AND o.invoice_code = i.invoice_code
                AND i.delivery_date = '$delivery_date'
                AND i.status <> 'VOID'
                ORDER BY d.car_no_$weekday,
                d.order_in_car_$weekday,
                c.district_id,
                c.delivery_order
                )
            SELECT customer_name,
                    invoice_code,
                    payment_status,
                    status,
                    car_no,
                    district_name,
                    count(1) cnt
                    FROM delivery_list
                    GROUP BY i.customer_name,
                    invoice_code,
                    payment_status,
                    status,
                    car_no,
                    district_name
                    ";
            $checklists = DB::select($sql);
        }
        //$checklists = stdClassArrToArray($checklists);
        return view("pages.checklist", ["checklists"=>$checklists, "delivery_date"=>$delivery_date]);
    }

    public function changeStatus(Request $request){
        $payment_status = $request->input('status');
        $invoice_code = $request->input('invoice_code');
        if ($payment_status == 'NONE')
            $status = 'PREP';
        else $status = 'DELV';
        DB::table('invoices')
            ->where('invoice_code',$invoice_code)
            ->update(['payment_status' => $payment_status, 'status' => $status]);
    }
}
