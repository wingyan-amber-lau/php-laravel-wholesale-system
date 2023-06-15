<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use DB;


class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = 'SELECT c.id,
                    c.supplier_code,
                    c.supplier_name,
                    c.contact_person,
                    c.phone,
                    c.phone_2,
                    c.fax,
                    c.address,
                    c.email
                FROM suppliers c';
        $suppliers = DB::select($sql);
        
        return view('settings.supplier',['suppliers'=>$suppliers]);
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
        $supplier = Supplier::where('supplier_id',$id)->first();
        //$msg = "This is a simple message.";
        //return $supplier;
        return response()->json(array('supplier'=> $supplier), 200);
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


    public function getSupplier(Request $request)
    {
        $supplier_code = $request->input('supplierCode');
        $supplier = DB::table('suppliers')
                    ->select('suppliers.*')
                    ->where('suppliers.supplier_code',$supplier_code)
                    ->get();
        //$sql = "SELECT 1 FROM invoices WHERE supplier_code = '$supplier_code' AND status = 'PREP'";
        //$duplicate_invoice = DB::select($sql);
        //$duplicate_invoice = count($duplicate_invoice);
        //return response()->json(array('supplier'=> $supplier,'duplicate_invoice'=> $duplicate_invoice), 200);
        return response()->json(array('supplier'=> $supplier), 200);
        //
    }

    public function getByID(Request $request)
    {
        $supplier_id = $request->input('supplier_id');
        $supplier = DB::table('suppliers')
                    ->select('suppliers.*')
                    ->where('suppliers.id',$supplier_id)
                    ->get();
        return response()->json(array('supplier'=> $supplier), 200);
        //
    }

    public function save(Request $request){
        $data = handleData($request->input('data'));        
        Supplier::updateOrCreate(
                [
                    'id'=>$data['supplier-id']
                ],
                [
                    'supplier_code'=>strtoupper($data['supplier-code']),
                    'supplier_name'=>$data['supplier-name'],
                    'contact_person'=>$data['contact-person'],
                    'phone'=>$data['phone'],
                    'phone_2'=>$data['phone_2'],
                    'fax'=>$data['fax'],
                    'address'=>$data['address'],
                    'email'=>$data['email']
                ]
        );
    }


    /*public function getPhoneList(){
        $sql = "SELECT c.supplier_code,
        c.supplier_name,
        c.phone,
        c.phone_2,
        c.contact_person
        FROM suppliers c
        ORDER BY c.supplier_code";
        $phonelists = DB::select($sql);
        return view('pages.phoneList',['phonelists'=>$phonelists]);
    }*/


    public function autocomplete(Request $request)
    {
        $data = Supplier::select("supplier_code as name","supplier_name as descr")
                ->where("supplier_code","LIKE","%{$request->get('query')}%")
                ->get();
        
                return response()->json($data);
        
                
    }
}
