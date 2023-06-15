@extends('layouts.page',['title' => '地區設定'])


@section('content')
<div>
<h2>地區設定 <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditModal" onclick="add()">+</button></h2>
<table class="table table-hover" id="district-table">
    <thead>
        <tr class="table-info">

            <th>地區編號</th>
            <th>地區</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th>六</th>
            <th>編輯</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($districts as $district)
        <tr>

            <td>{{$district->district_code}}</td>
            <td>{{$district->district_name}}</td>
            <td>@php if($district->mon) echo '&#10004;'.$district->car_no_mon.$district->order_in_car_mon; @endphp</td>
            <td>@php if($district->tue) echo '&#10004;'.$district->car_no_tue.$district->order_in_car_tue; @endphp</td>
            <td>@php if($district->wed) echo '&#10004;'.$district->car_no_wed.$district->order_in_car_wed; @endphp</td>
            <td>@php if($district->thu) echo '&#10004;'.$district->car_no_thu.$district->order_in_car_thu; @endphp</td>
            <td>@php if($district->fri) echo '&#10004;'.$district->car_no_fri.$district->order_in_car_fri; @endphp</td>
            <td>@php if($district->sat) echo '&#10004;'.$district->car_no_sat.$district->order_in_car_sat; @endphp</td>
            <td><button type="button" id="{{$district->id}}" name="{{$district->id}}" class='btn custom-btn' onclick="edit(this.id)"><img class="icon" src="{{ asset('img/edit.png') }}"/></button></td>
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
        <h5 class="modal-title" id="addEditModalLabel"><b>新增/編輯地區</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form" onsubmit="save();return false;">
            <table class="table table-hover table-borderless">
                <input type="hidden" id="district-id" name="district-id" value=""/>
                <tr>
                    <th>地區編號：</th>
                    <td colspan="3"><input type="text" class="form-control" id="district-code" name="district-code" required/></td>
                </tr>
                <tr>
                    <th>地區：</th>
                    <td colspan="3"><input type="text" class="form-control" id="district-name" name="district-name" required/></td>
                </tr>
                <tr>
                    <th>星期一：</th>
                    <td><input type="checkbox" class="form-control" id="mon" name="mon" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="car-no-mon" name="car-no-mon" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="order-in-car-mon" name="order-in-car-mon" placeholder="順序"/></td>
                </tr>
                <tr>
                    <th>星期二：</th>
                    <td><input type="checkbox" class="form-control" id="tue" name="tue" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="car-no-tue" name="car-no-tue" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="order-in-car-tue" name="order-in-car-tue" placeholder="順序"/></td>
                </tr>
                <tr>
                    <th>星期三：</th>
                    <td><input type="checkbox" class="form-control" id="wed" name="wed" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="car-no-wed" name="car-no-wed" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="order-in-car-wed" name="order-in-car-wed" placeholder="順序"/></td>
                </tr>
                <tr>
                    <th>星期四：</th>
                    <td><input type="checkbox" class="form-control" id="thu" name="thu" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9"  class="form-control" id="car-no-thu" name="car-no-thu" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9"  class="form-control" id="order-in-car-thu" name="order-in-car-thu" placeholder="順序"/></td>
                </tr>
                <tr>
                    <th>星期五：</th>
                    <td><input type="checkbox" class="form-control" id="fri" name="fri" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="car-no-fri" name="car-no-fri" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="order-in-car-fri" name="order-in-car-fri" placeholder="順序"/></td>
                </tr>
                <tr>
                    <th>星期六：</th>
                    <td><input type="checkbox" class="form-control" id="sat" name="sat" value="1" onclick="setRequired(this.id)"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="car-no-sat" name="car-no-sat" placeholder="車號"/></td>
                    <td><input type="number" min="0" max="9" class="form-control" id="order-in-car-sat" name="order-in-car-sat" placeholder="順序"/></td>
                </tr>
                <tr>
                    <td colspan="4"><b>區內送貨順序</b><br><div id="customerorder" class="list-group col"></div></td>
                    
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
<script src="{{asset('js/districtSettings.js')}}"></script>
@endsection
