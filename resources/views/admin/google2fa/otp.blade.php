<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{__('global.short_title')}}</title>
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/png">
    <link href="{{asset('css/admin/'.ldir().'/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/'.ldir().'/style.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <center>
        <div class="container">
            <div class="row justify-content-center align-items-center " style="height: 70vh;S">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading font-weight-bold">Register</div>
                        <hr>
                        @foreach ($errors->all() as $error)
                        <div class="text-danger"> {{$error}}</div>

                        @endforeach

                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{route('admin.2fa.valdiate')}}">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <p>Please enter the <strong>OTP</strong> generated on your Authenticator App. <br> Ensure you submit the current one because it refreshes every 30 seconds.</p>
                                    <label for="one_time_password" class="col-md-4 control-label">One Time Password</label>


                                    <div class="col-md-6">
                                        <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
</body>

</html>