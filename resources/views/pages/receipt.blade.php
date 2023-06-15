@extends('layouts.page')


@section('content')
<div onload="clearReceipt()">
<h1 class="text-center"> <button class="btn btn-info btn-sm" onclick="prevReceipt('all')">&#8592;</button><button class="btn btn-primary btn-sm" onclick="prevReceipt('supplier')">&#8592;</button> 入貨單 <button class="btn btn-primary btn-sm" onclick="nextReceipt('supplier')" >&#8594;</button><button class="btn btn-info btn-sm" onclick="nextReceipt('all')" >&#8594;</button> <button class="btn btn-success btn-sm" onclick="newReceipt()">+</button> <a class="btn btn-sm btn-warning" href="{{url('searchReceipt')}}" target="_blank"><img class="icon" src="{{ asset('img/magnifier.png') }}" alt="magnifier-icon"></a> @if (isset($data->status) && $data->status!='VOID') <button class="btn btn-sm btn-danger" onclick="voidReceipt()"><i class="fas fa-trash-alt"></i></button> @endif </h1>
<form onsubmit="saveReceipt();return false;" automcomplete="off">
<table class="supplier-table">
<tr>
    <th>供應商編號</th>
    <td><input id="supplier-code" name="supplier-code" value="{{{isset($data->supplier_code)?$data->supplier_code:''}}}" type="text" class="text-uppercase" {{{isset($data->supplier_code)?'readonly':''}}} onchange="getSupplier()"></td>
    <th>供應商</th>
    <td><input  id="supplier-name" name="supplier-name" value="{{{isset($data->supplier_name)?$data->supplier_name:''}}}" type="text" tabindex="-1" readonly ></td>
    <th>電郵</th>
    <td><input  id="email" name="email" type="text" value="{{{isset($data->email)?$data->email:''}}}" tabindex="-1" readonly ></td>
    <th>地址</th>
    <td><textarea  id="address" name="address" readonly tabindex="-1">{{{isset($data->address)?$data->address:''}}}</textarea></td>
</tr>
<tr>
    <th>聯絡人</th>
    <td><input id="contact-person" name="contact-person" value="{{{isset($data->contact_person)?$data->contact_person:''}}}" type="text" readonly tabindex="-1"></td>
    <th>電話</th>
    <td><input id="phone" name="phone" value="{{{isset($data->phone)?$data->phone:''}}}" type="text" readonly tabindex="-1"></td>
    <th>電話2</th>
    <td><input id="phone_2" name="phone_2" value="{{{isset($data->phone_2)?$data->phone_2:''}}}" type="text" readonly tabindex="-1"></td>
    <th>Fax</th>
    <td><input id="fax" name="fax" value="{{{isset($data->fax)?$data->fax:''}}}" type="text" readonly tabindex="-1"></td>
    </tr>
</table>
<table class="receipt-table">
<tr>
    <th>入貨單編號</th>
    <td><input type="text" id="receipt-code" name="receipt-code" value="{{{isset($data->receipt_code)?$data->receipt_code:''}}}"><input type="hidden" id="receipt-id" name="receipt-id" value="{{{isset($data->id)?$data->id:''}}}" tabindex="-1"></td>
    <th>日期</th>
    <td><input type="text" id="receipt-date" name="receipt-date" value="{{{isset($data->receipt_date)?$data->receipt_date:''}}}" readonly tabindex="-1"></td>
    <th>入貨日期</th>
    <td><input type="text" id="delivery-date" name="delivery-date" value="{{{isset($data->delivery_date)?$data->delivery_date:date('Y-m-d')}}}" tabindex="-1" onchange="enableSaveInovice()"></td>
    <th rowspan="3">備註</th>
    <td rowspan="3"><textarea id="remarks" name="remarks" tabindex="-1"  onchange="enableSaveInovice()">{{{isset($data->remarks)?$data->remarks:''}}} </textarea></td>
</tr>
</table>
<input type="hidden" name="max-row" id="max-row" value="{{{(isset($data->max_row)&&$data->max_row>20)?$data->max_row:20}}}"/>
<table class="custom-table">
    <thead class="table-info">
        <tr><th>總數</th><th><input id="receipt-total" name="receipt-total" type="text" value="{{{isset($data->total_amount)?'$'.number_format($data->total_amount,2):'$0'}}}"  readonly tabindex="-1"/></th></tr>
        <tr>
            <th style="width: 3%">序號</th>
            <th style="width: 17%">種類</th>
            <th style="width: 10%" colspan="2">貨品編號</th>
            <th style="width: 25%">貨品名稱</th>
            <th style="width: 3%">數量</th>
            <th style="width: 3%">單位</th>
            <th style="width: 7%">單價</th>
            <!--<th style="width: 5%">折扣</th>-->
            <th style="width: 7%">總價</th>
            <th style="width: 20%">包裝</th>
        </tr>
    </thead>
    <tbody id="receipt-item-list">
        @php
        (isset($data->max_row)&&$data->max_row>=20)?$max_row = $data->max_row+1:$max_row=20;
        @endphp
        @for ($i = 1; $i <= $max_row; $i++)
        <tr>
            <td><input id="{{$i}}-order-item-no" name="{{$i}}-order-item-no" data-field="order-item-no" type="text" value="{{$i}}" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-category" name="{{$i}}-category" data-field="category" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['category_name']:''}}}" type="text" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-product-code" name="{{$i}}-product-code" data-field="product-code" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['product_code']:''}}}" type="text" class="text-uppercase" onchange="getProduct(this.id)" onfocus="appendNewRow(this)"/></td><td><button id="{{$i}}-btn-check-product" class="btn btn-sm" name="{{$i}}-btn-check-product" type="button" tabindex="-1" onclick="checkProductLastOrderDate(this.id)">&#x2605;</button></td>
            <td><input id="{{$i}}-product-name" name="{{$i}}-product-name" data-field="product-name" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['product_name']:''}}}" type="text" readonly tabindex="-1"/></td>
            <td><input id="{{$i}}-amount" name="{{$i}}-amount" type="text" data-field="amount" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['amount']:''}}}" onchange="countProductTotal(this.id)" onfocus="select(this)"/></td>
            <td><input id="{{$i}}-unit" name="{{$i}}-unit" type="text" data-field="unit" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['unit']:''}}}" readonly tabindex="-1"/></td>
            <td>   
                <input id="{{$i}}-unit-cost" name="{{$i}}-unit-cost" data-field="unit-cost" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['unit_cost']:''}}}" onchange="countProductTotal(this.id)" type="number" step="0.01"/>
            </td>
           <td><input id="{{$i}}-total-cost" name="{{$i}}-total-cost" data-field="total-cost" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['total_cost']:''}}}" readonly type="number" step=".01" tabindex="-1"/></td>
            <td><input id="{{$i}}-packing" name="{{$i}}-packing" data-field="packing" value="{{{isset($data->receipt_items[$i-1])?$data->receipt_items[$i-1]['packing']:''}}}" type="text" tabindex="-1"/></td>
        </tr>
        @endfor

    </tbody>
</table>
<div class="text-center m-1"><button class="btn btn-primary btn-lg btn-fix" id="btn-save-receipt" type="submit" disabled>儲存</button></div>
</form>
</div>

<link rel="stylesheet" href="{{asset('fontawsome-5.12.0/css/all.css')}}">
<script src="{{asset('fontawsome-5.12.0/js/all.js')}}"></script>
<script src="{{asset('js/receipt.js')}}"></script>
@endsection
