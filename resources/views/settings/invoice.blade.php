@extends('layouts.page',['title' => '發票設定'])


@section('content')
<div>
<h2>發票設定<!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button>--></h2>
<table class="table table-hover" id="invoice-setting-table">
    <thead>
        <tr class="table-info">
            <th>發票訊息</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$invoice_message}}</td>
            <td><button type="button" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
        </tr>

    </tbody>
</table>
</div>

<!-- Modal -->
<div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEditModalLabel"><b>編輯發票訊息</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <tr>
                    <th>發票訊息：</th>
                    <td><input type="text" class="form-control" id="invoice-message" name="invoice-message" value="{{$invoice_message}}"/></td>
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
<script src="{{asset('js/settings/invoiceSettings.js')}}"></script>
{{-- <script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}"> --}}
@endsection
