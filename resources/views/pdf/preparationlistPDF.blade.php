<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>執貨表</title>
    <style>
    @page { size: 29.7cm 21cm; }
    .page_break { page-break-before: always; }
	.chinese {font-family: 'wt002'}
    </style>
</head>

<body style="font-family: 'wt002'">
<div>
@if ($delivery_date)
@php $count = 1; @endphp
@foreach ($preparationlists as $preparationlist)
@if ((isset($value_name) && $value_name != $preparationlist->value_name )|| $count == 42)
</td>
</tr>
</table></div><div class="page_break">
@endif
@if (!isset($value_name) || $value_name != $preparationlist->value_name || $count == 42)
@php $count = 1; @endphp
送貨日期：{{$delivery_date}}<br>
地區：@if ($district_code) {{$district_code}} @else ALL @endif<br>
類別：{{$preparationlist->value_name}}<hr>
<table>
<tr>
@endif

@if (isset($product_code) && $product_code != $preparationlist->product_code)
 <td style="vertical-align:top">
@endif
 @if (!isset($product_code) || $product_code != $preparationlist->product_code)
 @php $count++; @endphp
 </td> <td style="vertical-align:top">
 @for($i=0;$i<mb_strlen($preparationlist->product_name);$i++)
 {{mb_substr($preparationlist->product_name, $i, 1, 'UTF-8')}}<br>
 @endfor
 <br>
 @endif
 {{$preparationlist->amount}}<br>



@php 
$value_name = $preparationlist->value_name;
$product_code = $preparationlist->product_code; 
@endphp
@endforeach
</td></tr></table>
@else
請輸入送貨日期
@endif
</div>
</body>
</html>
