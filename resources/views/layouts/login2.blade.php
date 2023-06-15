<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script>
        var url = '{{ url('/') }}'+'/';
    </script>
    <style>
    .login-form{
        border:1px solid #b3b3b3;
        border-radius: 7px;
        padding:10px;
        margin:auto;
        margin-top:25%;
        width:50%;
        background-color: 	#f8f8f8;
    }
    </style>
    <title>{{config('app.name'),''}}</title>
</head>
<body>


      <div class="container">
         <form class="login-form" action="{{ url('/') }}/login" method="POST">

            <h2><center>{{config('app.name'),''}}</center></h2>
            <h5><center>帳號登入</center></h5>
            {{ csrf_field() }}
            <div class="form-group">
                <label for="username"><b>帳號</b></label>
                <input type="text" class="form-control" placeholder="輸入帳號" name="username" required>
            </div>
            <div class="form-group">
                <label for="password"><b>密碼</b></label>
                <input type="password" class="form-control" placeholder="輸入密碼" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">登入</button>
            </div>

        </form>
    </div>
</body>
</html>
