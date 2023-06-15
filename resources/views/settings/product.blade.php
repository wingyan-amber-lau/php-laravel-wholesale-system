@extends('layouts.page',['title' => '貨品設定'])


@section('content')
<div>
<h2>貨品設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="product-table">
    <thead>
        <tr class="table-info">
            <th>貨品編號</th>
            <th>貨品</th>
            <th>單位</th>
            <th>單價</th>
            <th>成本</th>
            <th>包裝</th>
            <th>種類</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{$product->product_code}}</td>
            <td>{{$product->product_name}}</td>
            <td>{{$product->unit}}</td>
            <td>{{$product->unit_price}}</td>
            <td>{{$product->unit_cost}}</td>
            <td>{{$product->packing}}</td>
            <td>{{$product->value_name}}</td>
            <td><button type="button" id="{{$product->id}}" name="{{$product->id}}" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
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
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯貨品</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="product-id" name="product-id" value=""/>
                <tr>
                    <th>貨品編號：</th>
                    <td colspan="3"><input type="text" class="form-control text-uppercase" id="product-code" name="product-code"/></td>
                </tr>
                <tr>
                    <th>貨品：</th>
                    <td colspan="3"><input type="text" class="form-control" id="product-name" name="product-name"/></td>
                </tr>
                <tr>
                    <th>種類：</th>
                    <td colspan="3">
                        <select type="text" class="form-control" id="category" name="category">
                            <option></option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->value_code}}-{{$category->value_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>單位：</th>
                    <td colspan="3"><input type="text" class="form-control" id="unit" name="unit"/></td>
                </tr>
                <tr>
                    <th>單價：</th>
                    <td colspan="3"><input type="text" class="form-control" id="unit-price" name="unit-price"/></td>
                </tr>
                <tr>
                    <th>成本</th>
                    <td colspan="3"><input type="text" class="form-control" id="unit-cost" name="unit-cost"/></td>
                </tr>
                <tr>
                    <th>包裝：</th>
                    <td colspan="3"><input type="text" class="form-control" id="packing" name="packing"/></td>
                </tr>
                <tr>   
                    <th>備註：</th>
                    <td colspan="3"><input type="text" class="form-control" id="remarks" name="remarks" maxlength="100"/></td>
                </tr>
                <tr>
                    <th>扣貨：</th>
                    <td colspan="3"><input type="checkbox" class="form-control" id="count-inventory" name="count-inventory"/></td>
                </tr>
            </table>
            <div>
                其他種類 <i class="fas fa-plus-square blue btn-fa" onclick="appendCategory()"></i>
                <input type="hidden" id="category-iteration" name="category-iteration" value="0"/>
                <table id="category-table" class="table table-hover table-borderless">
                <!--<tr>
                    <th><i class="fa fa-minus-square"></i> 種類：</th><td><select id="category-id" name="category-id" class="form-control"><option></option></select></td><th>類別：</th><td><select id="category-value-id" name="category-value-id" class="form-control"><option></option></select></td>
                </tr>-->
                </table>
            </div>

      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-primary">儲存</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>

      </div>
      </form>
    </div>
  </div>
</div>
<script>
var other_categories = '';
@foreach($other_categories as $other_category)
    other_categories = other_categories +'<option value="{{$other_category->id}}" data-code="{{$other_category->category_code}}">{{$other_category->category_name}}</option>';
@endforeach
</script>

<link rel="stylesheet" href="{{asset('js/datatables/jquery.dataTables.min.css')}}">
<script src="{{asset('js/datatables/jquery.dataTables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('fontawsome-5.12.0/css/all.css')}}">
<script src="{{asset('fontawsome-5.12.0/js/all.js')}}"></script>
<script src="{{asset('js/productSettings.js')}}"></script>
@endsection
