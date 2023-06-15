@extends('layouts.page',['title' => '核對清單'])


@section('content')
<h1>核對清單</h1>
<form action="{{url('checklist')}}" method="POST">
@csrf
<table><tr><th>送貨日期</th><td><input type="text" name="delivery-date" id="delivery-date" value="{{$delivery_date}}" onchange="form.submit();"/></td></tr>
</table>
</form>
<div class="noselect">
@foreach ($checklists as $ind => $checklist)
@if (!isset($checklists[$ind-1]) || $checklist->district_name != $checklists[$ind-1]->district_name)
<br><span class="checklist-district">{{$checklist->district_name}}<br>
@endif
<div class="checklist-box" onclick="changeStatus(this)"  id="{{$checklist->invoice_code}}">
    <div class="checklist-customer">{{$checklist->customer_name}} @if ($checklist->cnt>15) @for ($i=1;$i<$checklist->cnt/15;$i++) M @endfor @endif</div>
    <div class="cross"  @if ($checklist->payment_status!='PEND') style="display:none" @endif><!--<i class="fa fa-close checklist-symbol"></i>--><span class="checklist-symbol">簽單</span></div>
    <div class="circle"  @if ($checklist->payment_status!='PAID') style="display:none" @endif><!--<i class="fa fa-circle-o checklist-symbol"></i>--><span class="checklist-symbol">現金</span></div>
</div>
@if (isset($checklists[$ind+1]) && $checklist->car_no != $checklists[$ind+1]->car_no)
<br><br>
@endif
@endforeach
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{asset('css/checklist.css')}}">
<script src="{{asset('js/checklist.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.css')}}">
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
@endsection