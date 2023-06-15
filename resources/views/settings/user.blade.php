@extends('layouts.page')


@section('content')
<div>
<h2>帳戶設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="user-table">
    <thead>
        <tr class="table-info">
            <th>帳號名稱</th>
            <th>暱稱</th>
            <th>權限</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>
</div>

<!-- Modal -->
<div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯帳號</b></h5>
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
                    <th>帳號名稱：</th>
                    <td><input type="text" class="form-control text-uppercase" id="customer-code" name="customer-code"/></td>
                </tr>
                <tr>
                    <th>暱稱：</th>
                    <td><input type="text" class="form-control" id="customer-name" name="customer-name"/></td>
                </tr>
                <tr>
                    <th>權限：</th>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <th>舊密碼：</th>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <th>更改密碼：</th>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <th>確認密碼：</th>
                    <td>
                        
                    </td>
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
<script src="{{asset('js/userSettings.js')}}"></script>
{{-- <script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}"> --}}
@endsection