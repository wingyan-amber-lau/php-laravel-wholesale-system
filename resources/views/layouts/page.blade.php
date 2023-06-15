<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">


    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <link rel="stylesheet" href="{{asset('css/typeahead.css')}}">
    <script src="{{asset('js/typeahead.js')}}"></script>
    <script>
        var url = '{{ url('/') }}'+'/';
        var product_code_autocomplete_path = "{{ url('product_code_autocomplete') }}";
        var customer_code_autocomplete_path = "{{ url('customer_code_autocomplete') }}";
        var phone_autocomplete_path = "{{ url('phone_autocomplete') }}";
        var supplier_code_autocomplete_path = "{{ url('supplier_code_autocomplete') }}";
    </script>
    <title>{{$title ?? config('app.name'),''}}</title>
</head>
<body>
    @include('inc.header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-md-12 custom-nav">
                @include('inc.menu')
            </div>
            <div class="col-lg-11 col-md-12">
                <div class="tab-content" id="v-pills-tabContent">
                    @yield('content')
                 </div>
            </div>
        </div>
    </div>
</body>
</html>
