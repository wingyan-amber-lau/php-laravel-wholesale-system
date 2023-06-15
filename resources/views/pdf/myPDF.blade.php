<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>列印發票</title>
    <style>
    @page { size: 27cm 21cm; }
    .page_break { page-break-before: always; }
	.chinese {font-family: 'wt002'}
	.invoice-footer{position: fixed;bottom:1.5cm}
    </style>
</head>

<body style="font-family: 'wt002'">
@php $i = 0; @endphp
@foreach ($invoices as $invoice)

	@php
	$is_multi_page = ((count($invoice['orders'])/15)>1);
	$order_count = 1;
	$total_price = 0;
	$invoice_iteration = 1;
	 @endphp

    <div @if ($i!=0) class="page_break"@endif>
		<!--customer info start-->
		<br><br><br>
		<table>
			<tr>
				<td style="width:10cm">{{$invoice['customer_name']}}</td>
				<td rowspan="4" style="width:10cm">{{$invoice['remarks']}} </td>
				<td>{{$invoice['delivery_date']}}</td>
			</tr>
			<tr><td> </td><td>{{$invoice['value_name']}}</td></tr>
			<tr><td></td><td>{{$invoice['customer_code']}}</td></tr>
        	<tr>
				<td>{{$invoice['phone']}}</td>
				<td>INV/{{$invoice['invoice_code']}}@if ($is_multi_page)-{{$invoice_iteration}}@endif</td>
			</tr>
		</table>
		<br>
		<!--customer info end-->
        <!--order details start-->
		<table>
        @foreach ($invoice['orders'] as $order)

            @if ($order_count>15)
			</table>
			<table class="invoice-footer">
				<tr>
					<td style="width:3cm"> </td>
					<td style="width:17cm">{{$message}}</td>
					<td style="width:4.25cm;text-align:right">{{{number_format($total_price,2)}}}</td>
				</tr>
			</table>
                @php
				$order_count = 0;
				$invoice_iteration++;
				$total_price = 0;
				@endphp
    </div>
    <div class="page_break">
		<!--customer info start-->
		<br><br><br>
		<table>
			<tr>
				<td style="width:20cm">{{$invoice['customer_name']}}</td>
				<td>{{$invoice['delivery_date']}}</td>
			</tr>
			<tr><td> </td><td>N/A</td></tr>
			<tr><td></td><td>{{$invoice['customer_code']}}</td></tr>
        	<tr>
				<td>{{$invoice['phone']}}</td>
				<td>INV/{{$invoice['invoice_code']}}@if ($is_multi_page)-{{$invoice_iteration}}@endif</td>
			</tr>
		</table>
		<br>
		<!--customer info end-->
		<table>
            @endif
            <tr>
				<td style="width:3cm">{{$order['product_code']}}</td>
				<td style="width:10cm">{{$order['product_name']}}</td>
				<td style="width:3cm">{{$order['amount']}}{{$order['unit']}}</td>
				<td style="width:3cm;text-align:right">{{{number_format($order['unit_price'],2)}}}</td>
				<td style="width:2cm;text-align:right">{{{($order['discount']=='0.00')?' ':number_format($order['discount'],2)}}}</td>
				<td style="width:3cm;text-align:right">{{{number_format($order['total_price'],2)}}}</td>
			</tr>
            @php
			$order_count++;
			$total_price+=$order['total_price'];
			@endphp
        @endforeach
		</table>
		<table class="invoice-footer" >
			<tr>
				<td style="width:3cm"> </td>
				<td style="width:17cm">{{$message}}</td>
				<td style="width:4.25cm;text-align:right">{{{number_format($total_price,2)}}}</td>
			</tr>
		</table>
    </div>
    @php $i++; @endphp
@endforeach
</body>
</html>
