@extends('layouts.page',['title' => '顧客設定'])


@section('content')
<div>
<h2>顧客設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="customer-table">
    <thead>
        <tr class="table-info">
            <th>顧客編號</th>
            <th>顧客</th>
            <th>地區</th>
            <th>聯絡人</th>
            <th>電話</th>
            <th>Fax</th>
            <th>地址</th>
            <th>備註</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td>{{$customer->customer_code}}</td>
            <td>{{$customer->customer_name}}</td>
            <td>{{$customer->district_name}}</td>
            <td>{{$customer->contact_person}}</td>
            <td>{{$customer->phone}}</td>
            <td>{{$customer->fax}}</td>
            <td>{{$customer->address}}</td>
            <td>{{$customer->remarks}}</td>
            <td><button type="button" id="{{$customer->id}}" name="{{$customer->id}}" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Modal -->
<div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯顧客</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="customer-id" name="customer-id" value=""/>
                <input type="hidden" id="delivery-order" name="delivery-order" value="0"/>
                <tr>
                    <th>顧客編號：</th>
                    <td><input type="text" class="form-control text-uppercase" id="customer-code" name="customer-code"/></td>
                </tr>
                <tr>
                    <th>顧客：</th>
                    <td><input type="text" class="form-control" id="customer-name" name="customer-name"/></td>
                </tr>
                <tr>
                    <th>地區：</th>
                    <td>
                        <select type="text" class="form-control" id="district" name="district">
                            <option></option>
                            @foreach($districts as $district)
                                <option value="{{$district->id}}">{{$district->district_code}}-{{$district->district_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>付款方式：</th>
                    <td>
                        <select type="text" class="form-control" id="payment-method" name="payment-method">
                            <option></option>
                            @foreach($payment_methods as $payment_method)
                                <option value="{{$payment_method->value_code}}">{{$payment_method->value_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>聯絡人：</th>
                    <td><input type="text" class="form-control" id="contact-person" name="contact-person"/></td>
                </tr>
                <tr>
                    <th>電話：</th>
                    <td><input type="text" class="form-control" id="phone" name="phone"/></td>
                </tr>
                <tr>
                    <th>Fax：</th>
                    <td><input type="text" class="form-control" id="fax" name="fax"/></td>
                </tr>
                <tr>
                    <th>地址：</th>
                    <td><textarea id="address" class="form-control" name="address"/></textarea></td>
                </tr>
                <tr>
                    <th>備註：</th>
                    <td><textarea id="remarks" class="form-control" name="remarks"/></textarea></td>
                </tr>
                <tr>
                    <th>結業：</th>
                    <td><input type="checkbox" class="form-control" id="untrade" name="untrade"/></td>
                </tr>
            </table>

      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-primary">儲存</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>

      </div>
      </form>
    </div>
  </div>
</div>
<link rel="stylesheet" href="{{asset('js/datatables/jquery.dataTables.min.css')}}">
<script src="{{asset('js/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/customerSettings.js')}}"></script>
{{-- <script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}"> --}}
@endsection
