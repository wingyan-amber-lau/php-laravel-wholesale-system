@extends('layouts.page',['title' => '搜尋發票'])


@section('content')
<h1 class="text-center">搜尋發票</h1>
<form method="GET" action="{{url('invoiceResult')}}" id="form" autocomplete="off">
@csrf
<table>
<tr>
    <th style="width:30%">發票編號：</th>
    <td>
        <input type="text" id="invoice-code" name="invoice-code" value="{{{isset($data['invoice_code'])?$data['invoice_code']:''}}}" autocomplete="off">
        <input hidden="text" id="invoice-code-search" name="invoice-code-search" value="{{{isset($data['invoice_code'])?$data['invoice_code']:''}}}">
    </td>
</tr>
<tr>
    <th>發票日期：</th>
    <td>
        <input type="text" id="invoice-date" name="invoice-date" value="{{{isset($data['invoice_date'])?$data['invoice_date']:''}}}" autocomplete="off">
        <input type="hidden" id="invoice-date-search" name="invoice-date-search" value="{{{isset($data['invoice_date'])?$data['invoice_date']:''}}}">
    </td>
</tr>
<tr>
    <th>送貨日期：</th>
    <td>
        <input type="text" id="delivery-date" name="delivery-date" value="{{{isset($data['delivery_date'])?$data['delivery_date']:date('Y-m-d')}}}" onchange="toggleCarNo(this)" autocomplete="off">
        <input type="hidden" id="delivery-date-search" name="delivery-date-search" value="{{{isset($data['delivery_date'])?$data['delivery_date']:''}}}">
    </td>
</tr>
<tr>
    <th>顧客編號：</th>
    <td>
        <input id="customer-code" name="customer-code" value="{{{isset($data['customer_code'])?$data['customer_code']:''}}}" type="text" class="text-uppercase" autocomplete="off">
        <input id="customer-code-search" name="customer-code-search" value="{{{isset($data['customer_code'])?$data['customer_code']:''}}}" type="hidden">
    </td>
</tr>
<tr>
    <th>顧客：</th>
    <td>
        <input  id="customer-name" name="customer-name" value="{{{isset($data['customer_name'])?$data['customer_name']:''}}}" type="text" autocomplete="off">
        <input  id="customer-name-search" name="customer-name-search" value="{{{isset($data['customer_name'])?$data['customer_name']:''}}}" type="hidden">
    </td>
</tr>
<tr>
    <th>車號：</th>
    <td>
        <input id="car-no" name="car-no" value="{{{isset($data['car_no'])?$data['car_no']:''}}}" type="number" min="1" max="9">
        <input id="car-no-search" name="car-no-search" value="{{{isset($data['car_no'])?$data['car_no']:''}}}" type="hidden">
    </td>
</tr>
<tr>
    <th>區域：</th>
    <td colspan="5">
        <!--<input  id="district-code" name="district-code" type="text" value="{{{isset($data['district_code'])?$data['district_code']:''}}}">-->
        <select multiple="multiple" name="district-code[]" id="district-code" style="width:auto">
        @foreach ($districts as $district)
            @if ($loop->iteration % 5 == 1)
            <optgroup label="" style="display:table-row">
            @endif
            <option value="{{$district->district_code}}" style="display:table-cell" @php if(isset($data['district_code']) && strpos($data['district_code'],"'".$district->district_code."'")!==false) echo "selected";@endphp>{{$district->district_name}}</option>
            @if ($loop->iteration % 5 == 0)
            </optgroup>
            @endif
        @endforeach        
        </select>
        <input  id="district-code-search" name="district-code-search" type="hidden" value="{{{isset($data['district_code'])?$data['district_code']:''}}}">
    </td>
</tr>
<tr>
    <td>
        <button type="submit">搜尋</button>
    </td>
    <td>
        <input type="hidden" name="invoice-selected" id="invoice-selected" value="">
        <button type="button" onclick="printInvoice()">列印</button>
    </td>
</tr>
</table>
</form>

<div>
@if (isset($invoices) && $invoices)
<table class="table table-hover table-striped" id="result">
    <thead>
        <tr>
            <th><input type="checkbox" name="check-all" id="check-all" onclick="checkAll2();updateInvoiceSelected('all')"/></th>
            <th>發票編號</th>
            <th>發票日期</th>
            <th>送貨日期</th>
            <th>顧客編號</th>
            <th>顧客</th>
            <th>區域</th>
            <th>狀態</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($invoices as $invoice)
        <tr @if ($invoice->status=='VOID') class="red" @endif>
            <td><input type="checkbox" name="{{$invoice->invoice_code}}" id="{{$invoice->invoice_code}}" onclick="updateInvoiceSelected(this.id)"/></td>
            <td><a href="{{url('order')}}/{{$invoice->invoice_code}}"  target="_blank">{{$invoice->invoice_code}}</a></td>
            <td>{{$invoice->invoice_date}}</td>
            <td>{{$invoice->delivery_date}}</td>
            <td>{{$invoice->customer_code}}</td>
            <td>{{$invoice->customer_name}}</td>
            <td>{{$invoice->district_name}}</td>
            <td>{{$invoice->status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


@else
沒有相關發票。
@endif

</div>
<script src="{{asset('js/search/invoiceSearch.js')}}"></script>
<script src="{{asset('js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.css')}}">
@endsection
