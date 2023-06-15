<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/

use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;

if ( !function_exists('handleData') )
{
	function handleData($data){
        $arr = array();
		foreach($data as $element){
            $arr[$element['name']] = ($element['value'])?$element['value']:'';
        }
        return $arr;
	}
}

if ( !function_exists('stdClassArrToArray') )
{
	function stdClassArrToArray($stdClassArr){
        $arr = array();
        foreach($stdClassArr as $object)
        {
            $arr[] =  (array) $object;
        }
        return $arr;
	}
}

if ( !function_exists('arrayPaginator') )
{
	 function arrayPaginator($array, $request)
	{
		$page = Input::get('page', 1);
		$perPage = 50;
		$offset = ($page * $perPage) - $perPage;

		return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
			['path' => $request->url(), 'query' => $request->query()]);
	}
}
 ?>
