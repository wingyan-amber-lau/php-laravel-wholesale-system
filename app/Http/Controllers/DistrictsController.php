<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Http\Controllers;

use App\Models\District;
Use DB;
use Illuminate\Http\Request;

class DistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$districts = District::all();
        $sql = 'SELECT * FROM districts';
        $districts = DB::select($sql);
        //$districts = arrayPaginator($districts, $request);
        return view('settings.district',['districts'=>$districts]);
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

    public function get(){
        $sql = "SELECT district_code, district_name FROM districts";
        $districts = DB::select($sql);
        $districts = stdClassArrtoArray($districts);
        return $districts;
    }

    public function getByID(Request $request)
    {
        $district_id = $request->input('district_id');
        $district = District::where('id',$district_id)
                    ->get();
        //$district = district::where('district_code',$district_code)->first();
        //$msg = "This is a simple message.";
        //return $district;
        return response()->json(array('district'=> $district), 200);
        //
    }

    public function save(Request $request){
        $data = handleData($request->input('data'));
        $result = District::updateOrCreate(
                [
                    'id'=>$data['district-id']
                ],
                [
                    'district_code'=>strtoupper($data['district-code']),
                    'district_name'=>$data['district-name'],
                    'mon'=>isset($data['mon'])?$data['mon']:0,
                    'car_no_mon'=>($data['car-no-mon'])?$data['car-no-mon']:0,
                    'order_in_car_mon'=>($data['order-in-car-mon'])?$data['order-in-car-mon']:0,
                    'tue'=>isset($data['tue'])?$data['tue']:0,
                    'car_no_tue'=>($data['car-no-tue'])?$data['car-no-tue']:0,
                    'order_in_car_tue'=>($data['order-in-car-tue'])?$data['order-in-car-tue']:0,
                    'wed'=>isset($data['wed'])?$data['wed']:0,
                    'car_no_wed'=>($data['car-no-wed'])?$data['car-no-wed']:0,
                    'order_in_car_wed'=>($data['order-in-car-wed'])?$data['order-in-car-wed']:0,
                    'thu'=>isset($data['thu'])?$data['thu']:0,
                    'car_no_thu'=>($data['car-no-thu'])?$data['car-no-thu']:0,
                    'order_in_car_thu'=>($data['order-in-car-thu'])?$data['order-in-car-thu']:0,
                    'fri'=>isset($data['fri'])?$data['fri']:0,
                    'car_no_fri'=>($data['car-no-fri'])?$data['car-no-fri']:0,
                    'order_in_car_fri'=>($data['order-in-car-fri'])?$data['order-in-car-fri']:0,
                    'sat'=>isset($data['sat'])?$data['sat']:0,
                    'car_no_sat'=>($data['car-no-sat'])?$data['car-no-sat']:0,
                    'order_in_car_sat'=>($data['order-in-car-sat'])?$data['order-in-car-sat']:0

                ]
        );
    }
}
