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
        <div class="container ">
            <div class="row">
                <div class="col-md-8 m-auto">
                    <div class="panel panel-default mt-5">
                        <div class="panel-heading text-white text-center">
                            <h1>Set up Google Authenticator</h1>
                        </div>

                        <div class="panel-body text-center">
                            <p class="text-white" style="font-size: .8rem;">Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code <b>{{ $secret }}</b></p>

                            <div class="panel-body text-center">
                                {!! $QR_Image !!}
                            </div>
                            <br />
                            <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>
                            <div>
                                <a href="{{route('admin.2fa.twoFactorAuthOTP',['secret'=> $secret])}}" class="btn btn-primary">Continue to enter OTP</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
</body>

</html>