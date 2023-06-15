@extends('layouts.page',['title' => '執貨表'])


@section('content')
<h1>執貨表</h1>
<form target="_blank" action="{{url('preparationlist')}}" method="POST">
@csrf
<table><tr><th>送貨日期</th><td><input type="text" name="delivery-date" id="delivery-date" value="{{$delivery_date}}" required/></td></tr>
<tr>
    <th>區域</th>
    <td>
        <select multiple="multiple" name="district-code[]" id="district-code" style="width:auto" required>
        @foreach ($districts as $district)
            @if ($loop->iteration % 5 == 1)
            <optgroup label="" style="display:table-row">
            @endif
            <option value="{{$district->district_code}}" style="display:table-cell" @php if(isset($district_code) && strpos($district_code,"'".$district->district_code."'")!==false) echo "selected";@endphp>{{$district->district_name}}</option>
            @if ($loop->iteration % 5 == 0)
            </optgroup>
            @endif
        @endforeach        
        </select>
    </td>
</tr>
<tr><td colspan="2" class="text-right"><button type="submit">確定</button></td></tr>
</table>
</form>

<script src="{{asset('js/preparationlist.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.css')}}">
<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
@endsection