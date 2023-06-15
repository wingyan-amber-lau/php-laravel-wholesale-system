@extends('layouts.page',['title' => '種類設定'])


@section('content')
<div>
<h2>種類設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="category-table">
    <thead>
        <tr class="table-info">
            <th>種類編號</th>
            <th>種類</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td>{{$category->category_code}}</td>
            <td>{{$category->category_name}}</td>
            <td><button type="button" id="{{$category->id}}" name="{{$category->id}}" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>


<!-- Modal1 -->
<div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯種類</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="category-id" name="category-id" value=""/>
                <tr>
                    <th>種類編號：</th>
                    <td><input type="text" class="form-control" id="category-code" name="category-code"/></td>
                </tr>
                <tr>
                    <th>種類：</th>
                    <td><input type="text" class="form-control" id="category-name" name="category-name"/></td>
                </tr>
                <tr>
                    <th>貨品其他種類：</th>
                    <td><input type="checkbox" class="form-control" id="for-product" name="for-product"/></td>
                </tr>
                <tr id="hidden-row">
                    <td colspan="2"><b>種類內順序</b><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditCategoryValueModal" onclick="addCategoryValue()">+</button><br><div id="categoryorder" class="list-group col"></div></td>
                    
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
<!-- Modal2 -->
<div class="modal fade" id="addEditCategoryValueModal" tabindex="-1" role="dialog" aria-labelledby="addEditCategoryValueModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEditCategoryValueModalLabel"><b>新增/編輯種類類別</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form2" onsubmit="saveCategoryValue();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="value-id" name="value-id" value=""/>
                <input type="hidden" id="category-id-2" name="category-id-2" value=""/>
                <input type="hidden" id="order-in-category" name="order-in-category" value=""/>
                <tr>
                    <th>類別編號：</th>
                    <td><input type="text" class="form-control" id="value-code" name="value-code"/></td>
                </tr>
                <tr>
                    <th>類別：</th>
                    <td><input type="text" class="form-control" id="value-name" name="value-name"/></td>
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
<script src="{{asset('js/Sortable.js')}}"></script>
<script src="{{asset('js/categorySettings.js')}}"></script>
@endsection
