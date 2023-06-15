@extends('layouts.page')


@section('content')
<h1 class="text-center">搜尋入貨單</h1>
<form method="GET" action="{{url('receiptResult')}}" id="form">
@csrf
<table>
<tr>
    <th>入貨單編號：</th>
    <td>
        <input type="text" id="receipt-code" name="receipt-code" value="{{{isset($data['receipt_code'])?$data['receipt_code']:''}}}" autocomplete="off">
        <input hidden="text" id="receipt-code-search" name="receipt-code-search" value="{{{isset($data['receipt_code'])?$data['receipt_code']:''}}}">
    </td>
</tr>
<tr>
    <th>記錄日期：</th>
    <td>
        <input type="text" id="receipt-date" name="receipt-date" value="{{{isset($data['receipt_date'])?$data['receipt_date']:''}}}" autocomplete="off">
        <input type="hidden" id="receipt-date-search" name="receipt-date-search" value="{{{isset($data['receipt_date'])?$data['receipt_date']:''}}}">
    </td>
</tr>
<tr>
    <th>入貨日期：</th>
    <td>
        <input type="text" id="delivery-date" name="delivery-date" value="{{{isset($data['delivery_date'])?$data['delivery_date']:date('Y-m-d')}}}" onchange="toggleCarNo(this)" autocomplete="off">
        <input type="hidden" id="delivery-date-search" name="delivery-date-search" value="{{{isset($data['delivery_date'])?$data['delivery_date']:''}}}">
    </td>
</tr>
<tr>
    <th>供應商編號：</th>
    <td>
        <input id="supplier-code" name="supplier-code" value="{{{isset($data['supplier_code'])?$data['supplier_code']:''}}}" type="text" class="text-uppercase" autocomplete="off">
        <input id="supplier-code-search" name="supplier-code-search" value="{{{isset($data['supplier_code'])?$data['supplier_code']:''}}}" type="hidden">
    </td>
</tr>
<tr>
    <th>供應商：</th>
    <td>
        <input  id="supplier-name" name="supplier-name" value="{{{isset($data['supplier_name'])?$data['supplier_name']:''}}}" type="text" autocomplete="off">
        <input  id="supplier-name-search" name="supplier-name-search" value="{{{isset($data['supplier_name'])?$data['supplier_name']:''}}}" type="hidden">
    </td>
    
</tr>
<tr>
    <td>
        <button type="submit">搜尋</button>
    </td>
</tr>
</table>
</form>

<div>
@if (isset($receipts) && $receipts)
<table class="table table-hover table-striped" id="result">
    <thead>
        <tr>
            <th>入貨單編號</th>
            <th>記錄日期</th>
            <th>入貨日期</th>
            <th>供應商編號</th>
            <th>供應商</th>
            <th>總數</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($receipts as $receipt)
        <tr>
            <td><a href="{{url('receipt')}}/{{$receipt->receipt_code}}"  target="_blank">{{$receipt->receipt_code}}</a></td>
            <td>{{$receipt->receipt_date}}</td>
            <td>{{$receipt->delivery_date}}</td>
            <td>{{$receipt->supplier_code}}</td>
            <td>{{$receipt->supplier_name}}</td>
            <td>{{$receipt->total_amount}}</td>

        </tr>
    @endforeach
    </tbody>
</table>


@else
沒有相關入貨單。
@endif

</div>
<script src="{{asset('js/search/receiptSearch.js')}}"></script>
<script src="{{asset('js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.css')}}">

@endsection
