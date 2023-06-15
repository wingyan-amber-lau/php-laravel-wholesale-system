@extends('layouts.page',['title' => '供應商設定']))


@section('content')
<div>
<h2>供應商設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="supplier-table">
    <thead>
        <tr class="table-info">
            <th>供應商編號</th>
            <th>供應商</th>
            <th>聯絡人</th>
            <th>電話1</th>
            <th>電話2</th>
            <th>Fax</th>
            <!--<th>地址</th>-->
            <!--<th>電郵</th>-->
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($suppliers as $supplier)
        <tr>
            <td>{{$supplier->supplier_code}}</td>
            <td>{{$supplier->supplier_name}}</td>
            <td>{{$supplier->contact_person}}</td>
            <td>{{$supplier->phone}}</td>
            <td>{{$supplier->phone_2}}</td>
            <td>{{$supplier->fax}}</td>
            <!--<td>{{$supplier->address}}</td>-->
            <!--<td>{{$supplier->email}}</td>-->
            <td><button type="button" id="{{$supplier->id}}" name="{{$supplier->id}}" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
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
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯供應商</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="supplier-id" name="supplier-id" value=""/>
                <tr>
                    <th>供應商編號：</th>
                    <td><input type="text" class="form-control text-uppercase" id="supplier-code" name="supplier-code"/></td>
                </tr>
                <tr>
                    <th>供應商：</th>
                    <td><input type="text" class="form-control" id="supplier-name" name="supplier-name"/></td>
                </tr>
                <tr>
                    <th>聯絡人：</th>
                    <td><input type="text" class="form-control" id="contact-person" name="contact-person"/></td>
                </tr>
                <tr>
                    <th>電話1：</th>
                    <td><input type="text" class="form-control" id="phone" name="phone"/></td>
                </tr>
                <tr>
                    <th>電話2：</th>
                    <td><input type="text" class="form-control" id="phone_2" name="phone_2"/></td>
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
                    <th>電郵：</th>
                    <td><input type="email" id="email" class="form-control" name="email"/></td>
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
<script src="{{asset('js/supplierSettings.js')}}"></script>
{{-- <script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}"> --}}
@endsection
