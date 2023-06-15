<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\DailyIncome;
use App\Models\DailyExpenditure;
use PDF;

class SettlementsController extends Controller
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

    public function getDailyByDate(Request $request){
        if ($request->input('balance-date')){
            $balance_date = $request->input('balance-date');
        }
        else $balance_date = date('Y-m-d');
        $income_arr = array();
        $expenditure_arr = array();
        $income_sql = "SELECT income_item,
                        item_descr,
                        pending_amount,
                        received_amount
                        FROM daily_incomes
                        WHERE balance_date = :balance_date";
        $expense_sql = "SELECT expense_item,
                        item_descr,
                        cost,
                        paid_amount
                        FROM daily_expenditures
                        WHERE balance_date = :balance_date";
        $criteria = array(
            "balance_date" => $balance_date
        );
        $expenditures = DB::select($expense_sql,$criteria);
        $incomes = DB::select($income_sql,$criteria);
        foreach($incomes as $income){
            $income_arr[$income->income_item][$income->item_descr]['pending_amount'] = $income->pending_amount;
            $income_arr[$income->income_item][$income->item_descr]['received_amount'] = $income->received_amount;
        }
        foreach($expenditures as $expenditure){
            $expenditure_arr[$expenditure->expense_item]['cost'] = $expenditure->cost;
            $expenditure_arr[$expenditure->expense_item]['paid_amount'] = $expenditure->paid_amount;
            $expenditure_arr[$expenditure->expense_item]['item_descr'] = $expenditure->item_descr;
        }

        return view('statistics.dailySettlement',['balance_date' => $balance_date, 'income' => $income_arr, 'expenditure' => $expenditure_arr]);
    }

    public function updateDailySettlement(Request $request){
        $balance_date = $request->input('balance_date');
        $value =  $request->input('value');
        $data =  $request->input('data');
        if ($data['type'] == 'income'){
            $this->updateDailyIncome($balance_date,$value,$data);
        }
        else{
            $this->updateDailyExpenditure($balance_date,$value,$data);
        }
    }

    private function updateDailyIncome($balance_date,$value,$data){
        DB::table('daily_incomes')
        ->updateOrInsert(
            [
                'balance_date' => $balance_date,
                'income_item' => $data['item'],
                'item_descr' => $data['descr']
            ],
            [
                $data['field'] => $value
            ]
        );
    }

    private function updateDailyExpenditure($balance_date,$value,$data){
        //TODO:add checking for valid importor
        DB::table('daily_expenditures')
        ->updateOrInsert(
            [
                'balance_date' => $balance_date,
                'expense_item' => $data['item']
            ],
            [
                $data['field'] => $value
            ]
        );
    }

    public function monthlyStatements(Request $request){
        $customer_code = $request->input('customer-code');
        $delivery_month = $request->input('delivery-month');
        $sql = "SELECT i.customer_code,
                i.customer_name,
                i.invoice_code,
                i.phone,
                i.delivery_date,
                i.total_amount,
                CEILING(COUNT(1)/15) num_of_invoice
                FROM invoices i,
                orders o
                WHERE i.invoice_code = o.invoice_code
                AND i.status <> 'VOID'
                AND i.customer_code = :customer_code
                AND DATE_FORMAT(i.delivery_date,'%Y-%m') = :delivery_month
                GROUP BY i.customer_code,
                i.customer_name,
                i.invoice_code,
                i.phone,
                i.delivery_date,
                i.total_amount";
        $criteria = array(
            'customer_code' => $customer_code,
            'delivery_month' => $delivery_month
        );
        $monthlyStatements = DB::select($sql,$criteria);
        $delivery_month_chi = str_replace('-','年',$delivery_month);
        $data = ["monthlyStatements"=>$monthlyStatements, "delivery_month"=>$delivery_month_chi];

        $pdf = PDF::loadView('pdf.monthlyStatementPDF', $data);

        return $pdf->stream('monthlyStatement_'.$customer_code.'_'.$delivery_month.'.pdf');
    }

    public function showMonthlyStatementPage(){
        return view('statistics.monthlyStatement');
    }
}
