<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{$delivery_month}}月客戶月結單</title>
    <style>
    @page { size: 21cm 29.7cm; }
    .page_break { page-break-before: always; }
	.chinese {font-family: 'wt002'}
    .font-size-24 {font-size: 24pt}
    .font-size-18 {font-size: 18pt}
    .font-size-14 {font-size: 14pt}
    .align-center {text-align: center}
    table {border:solid 1px black;border-collapse: collapse;}
    th {border:solid 1px black}
    td {border-right:solid 1px black}
    </style>
</head>

<body style="font-family: 'wt002'">
<div>
<div class="align-center" style="line-height:0.8"><span class="font-size-24">{{config('app.name'),''}}</span><br>
<span class="font-size-24">{{config('app.name'),''}}</span><br>
{{config('app.address'),''}}<br>
電話：{{config('app.phone'),''}}  傳真：{{config('app.fax'),''}}</div><hr>
<span class="font-size-18">客戶月結單（{{$delivery_month}}月）</span><br>
發出日期： {{date('Y-m-d')}}
<table style="width:100%;">
<tr><td style="width:25%">客戶名稱：</td><td>{{$monthlyStatements[0]->customer_name}}</td></tr>
<tr><td>客戶電話：</td><td>{{$monthlyStatements[0]->phone}}</td></tr>
</table>
<br>

<table style="width:100%;text-align:center;line-height:0.9">
<tr>
    <th style="width:25%"><b>送貨日期</b></th>
    <th style="width:25%"><b>單號</b></th>
    <th style="width:25%"><b>發票張數</b></th>
    <th style="width:25%"><b>金額 (HKD)</b></th>
</tr>
@php
$total = 0;
@endphp
@foreach($monthlyStatements as $monthlyStatement)
@php
$total += $monthlyStatement->total_amount;
@endphp
<tr>
    <td>{{$monthlyStatement->delivery_date}}</td>
    <td>INV/{{$monthlyStatement->invoice_code}}</td>
    <td>{{$monthlyStatement->num_of_invoice}}</td>
    <td>{{$monthlyStatement->total_amount}}</td>
</tr>
@endforeach
<tr><th colspan="3" style="text-align:right;" class="font-size-14">總額 (HKD)： </th><th class="font-size-14">{{number_format($total,2)}}</th></tr>
</table>
</div>
</body>
</html>
