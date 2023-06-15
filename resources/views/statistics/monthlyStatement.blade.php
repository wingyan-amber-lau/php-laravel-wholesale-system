@extends('layouts.page')


@section('content')
<h1>月結單</h1>
<form method="POST" target="_BLANK" action="{{url('monthlyStatement')}}" autocomplete="off">
@csrf
<table class="ml-3">
<tr>
<th>結算月份</th>
<td><input type="text" name="delivery-month" id="delivery-month" required/></td>
</tr>
<tr>
<th>顧客編號</th>
<td><input type="text" name="customer-code" id="customer-code" required/></td>
</tr>
<tr><td><button type="submit">提交</button></td></tr>
</table>

</form>
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.css')}}">
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/statistics/monthlyStatement.js')}}"></script>
@endsection