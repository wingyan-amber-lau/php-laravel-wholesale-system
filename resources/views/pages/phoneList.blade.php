@extends('layouts.page')


@section('content')
<h1>電話清單</h1>
<table>
<tr><th>客戶</th><th>稱呼</th><th>電話1</th><th>電話2</th></tr>
@foreach ($phonelists as $phonelist)
@if (!isset($district_name) || $district_name != $phonelist->district_name)
<tr><th colspan="4">{{$phonelist->district_name}}</th></tr>
@endif
<tr>
    <td>{{$phonelist->customer_code}} {{$phonelist->customer_name}}</td>
    <td>{{$phonelist->contact_person}}</td>
    <td>{{$phonelist->phone}}</td>
    <td>{{$phonelist->phone_2}}</td>
</tr>
@php 
$district_name = $phonelist->district_name;
@endphp
@endforeach
</table>

<style>
table{
    border:solid 1px black;
    border-collapse: collapse;
}
td,th,tr{
    border:solid 1px black;
}
</style>
@endsection