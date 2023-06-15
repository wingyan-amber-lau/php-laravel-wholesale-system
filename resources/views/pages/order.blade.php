@extends('layouts.page',['title' => '發票'])


@section('content')
<div onload="clearInvoice()">
<h1 class="text-center">@if (isset($data))<button class="btn btn-sm btn-secondary" onclick="printInvoice()"><i class="fas fa-print"></i></button>@endif <button class="btn btn-info btn-sm" onclick="prevInvoice('all')">&#8592;</button><button class="btn btn-primary btn-sm" onclick="prevInvoice('customer')">&#8592;</button> 發票 <button class="btn btn-primary btn-sm" onclick="nextInvoice('customer')" >&#8594;</button><button class="btn btn-info btn-sm" onclick="nextInvoice('all')" >&#8594;</button> <button class="btn btn-success btn-sm" onclick="newInvoice()">+</button> <a class="btn btn-sm btn-warning" href="{{url('searchInvoice')}}" target="_blank"><img class="icon" src="{{ asset('img/magnifier.png') }}" alt="magnifier-icon"></a> @if (isset($data->status) && $data->status!='VOID') <button class="btn btn-sm btn-danger" onclick="voidInvoice()"><i class="fas fa-trash-alt"></i></button> @endif </h1>
<h4>發票狀態：@if (isset($data->status)) @if ($data->status =='VOID') <span class="red">{{$data->status}}</span> @else {{$data->status}} @endif @else N/A @endif<!-- 顧客資料<button class="btn btn-sm btn-primary">+</button>--></h4>
<form onsubmit="saveInvoice();return false;" autocomplete="off" onchange="enableSaveInvoice();">
<table class="customer-table">
<tr>
    <th>顧客編號</th>
    <td><input id="customer-code" name="customer-code" required value="{{{isset($data->customer_code)?$data->customer_code:''}}}" type="text" class="text-uppercase" {{{isset($data->customer_code)?'readonly':''}}} onfocusout="getCustomer('customer_code')"></td>
    <th>顧客</th>
    <td><input  id="customer-name" name="customer-name" value="{{{isset($data->customer_name)?$data->customer_name:''}}}" type="text" tabindex="-1" readonly ></td>
    <th>區域</th>
    <td><input  id="district-name" name="district-name" type="text" value="{{{isset($data->district_name)?$data->district_name:''}}}" tabindex="-1" readonly ></td>
    <th>付款方式</th>
    <td><input  id="payment-method-display" name="payment-method-display" type="text" value="{{{isset($data->value_name)?$data->value_name:''}}}" tabindex="-1" readonly ></td>
    <td><input  id="district-code" name="district-code" type="hidden" value="{{{isset($data->district_code)?$data->district_code:''}}}"></td>
    <td><input  id="payment-method" name="payment-method" type="hidden" value="{{{isset($data->payment_method)?$data->payment_method:''}}}"></td>
</tr>
<tr>
    <th>聯絡人</th>
    <td><input id="contact-person" name="contact-person" value="{{{isset($data->contact_person)?$data->contact_person:''}}}" type="text" readonly tabindex="-1"></td>
    <th>電話</th>
    <td><input id="phone" name="phone" value="{{{isset($data->phone)?$data->phone:''}}}" type="text" tabindex="-1" {{{isset($data->customer_code)?'readonly':''}}} onfocusout="getCustomer('phone')"></td>
    <th rowspan="3">地址</th>
    <td rowspan="3"><textarea  id="address" name="address" readonly tabindex="-1">{{{isset($data->address)?$data->address:''}}}</textarea></td>
    <th rowspan="3">備註</th>
    <td rowspan="3"><textarea  id="remarks" name="remarks" readonly tabindex="-1">{{{isset($data->remarks)?$data->remarks:''}}}</textarea></td>
</tr>
</table>
<table class="invoice-table">
<tr>
    <th>發票編號</th>
    <td><input type="text" id="invoice-code" name="invoice-code" value="{{{isset($data->invoice_code)?'INV'.$data->invoice_code:''}}}" readonly tabindex="-1"><input type="hidden" id="invoice-id" name="invoice-id" value="{{{isset($data->id)?$data->id:''}}}" tabindex="-1"></td>
    <th>日期</th>
    <td><input type="text" id="invoice-date" name="invoice-date" value="{{{isset($data->invoice_date)?$data->invoice_date:''}}}" readonly tabindex="-1"></td>
    <th>送貨日期</th>
    <td><input type="text" id="delivery-date" name="delivery-date" value="{{{isset($data->delivery_date)?$data->delivery_date:''}}}" tabindex="-1" onchange="enableSaveInvoice()"></td>
    <th rowspan="3">備註</th>
    <td rowspan="3"><textarea id="remarks" name="remarks" tabindex="-1"  onchange="enableSaveInvoice()">{{{isset($data->remarks)?$data->remarks:''}}} </textarea></td>
</tr>
</table>
<input type="hidden" name="max-row" id="max-row" value="{{{(isset($data->max_row)&&$data->max_row>20)?$data->max_row:20}}}"/>
<table class="custom-table">
    <thead class="table-info">
        <tr><th>總數</th><th><input id="invoice-total" name="invoice-total" type="text" value="{{{isset($data->total_amount)?'$'.number_format($data->total_amount,2):'$0'}}}"  readonly tabindex="-1"/></th></tr>
        <tr>
            <th style="width: 3%">序號</th>
            <th style="width: 17%">種類</th>
            <th style="width: 14%" colspan="2">貨品編號</th>
            <th style="width: 20%">貨品名稱</th>
            <th style="width: 6%">數量</th>
            <th style="width: 4%">單位</th>
            <th style="width: 7%">單價</th>
            <th style="width: 7%">每件折扣</th>
            <th style="width: 2%"></th>
            <th style="width: 9%">總價</th>
            <th style="width: 13%">包裝</th>
        </tr>
    </thead>
    <tbody id="invoice-item-list">
        @php
        (isset($data->max_row)&&$data->max_row>=20)?$max_row = $data->max_row+1:$max_row=20;
        @endphp
        @for ($i = 1; $i <= $max_row; $i++)
        <tr>
            <td><input id="{{$i}}-order-item-no" name="{{$i}}-order-item-no" data-field="order-item-no" type="text" value="{{$i}}" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-category" name="{{$i}}-category" data-field="category" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['category_name']:''}}}" type="text" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-product-code" name="{{$i}}-product-code" data-field="product-code" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['product_code']:''}}}" type="text" class="text-uppercase typeahead" onchange="getProduct(this.id)" onfocus="appendNewRow(this)"/></td><td><button id="{{$i}}-btn-check-product" class="btn btn-sm" name="{{$i}}-btn-check-product" type="button" tabindex="-1" onclick="checkProductLastOrderDate(this.id)">&#x2605;</button></td>
            <td><input id="{{$i}}-product-name" name="{{$i}}-product-name" data-field="product-name" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['product_name']:''}}}" type="text" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-amount" name="{{$i}}-amount" type="text" data-field="amount" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['amount']:''}}}" onchange="countProductTotal(this.id)" pattern="[0-9]+[*|個]?[0-9]*"/></td>
            <td><input id="{{$i}}-unit" name="{{$i}}-unit" type="text" data-field="unit" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['unit']:''}}}" readonly tabindex="-1"/></td>
            <td>   
                <input id="{{$i}}-unit-price" name="{{$i}}-unit-price" data-field="unit-price" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['unit_price']:''}}}" type="number" step=".01" tabindex="-1" onchange="countProductTotal(this.id);"/>
                <input id="{{$i}}-unit-cost" name="{{$i}}-unit-cost" data-field="unit-cost" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['unit_cost']:''}}}" type="hidden" tabindex="-1"/>
            </td>
            <td><input id="{{$i}}-discount" name="{{$i}}-discount" data-field="discount" type="number" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['discount']:''}}}" step=".01" tabindex="-1" onchange="countProductTotal(this.id);enableSaveInvoice();"/></td>
            <td><input type="checkbox" id="{{$i}}-discount-once" name="{{$i}}-discount-once" {{{isset($data->order[$i-1])?(($data->order[$i-1]['discount_once'])?'checked=checked':''):''}}} tabindex="-1" ></td>
            <td><input id="{{$i}}-total-price" name="{{$i}}-total-price" data-field="total-price" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['total_price']:''}}}" readonly type="number" step=".01" tabindex="-1"/></td>
            <td><input id="{{$i}}-packing" name="{{$i}}-packing" data-field="packing" value="{{{isset($data->order[$i-1])?$data->order[$i-1]['packing']:''}}}" type="text" readonly tabindex="-1"/></td>
        </tr>
        @endfor

    </tbody>
</table>
<div class="text-center m-1"><button class="btn btn-primary btn-lg btn-fix" id="btn-save-invoice" type="submit" disabled>儲存</button></div>
</form>
<div class="alert-container" id="alert-container" onclick="hideAlertContainer()">
<div class="alert alert-warning alert-center">
    <a href="#" class="close" aria-label="close" onclick="hideAlertContainer()">&times;</a>
    <strong>前5次購買記錄</strong>
    <div id="alert-content">
    </div> 
</div>
</div>

</div>

<link rel="stylesheet" href="{{asset('fontawsome-5.12.0/css/all.css')}}">

<script src="{{asset('fontawsome-5.12.0/js/all.js')}}"></script>
<script src="{{asset('js/invoice.js')}}"></script>


@endsection
