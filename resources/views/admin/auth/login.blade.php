<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta content="PenPeers Team" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PenPeers Admin Panel</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{{asset('css/admin/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin/style.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center m-t-0 m-b-15">
                    <a href="{{route('admin.get_login')}}" class="text-light">{{__('global.short_title')}}</a>
                </h3>
                <h4 class="text-muted text-center m-t-0"><b>{{__('global.sign_in')}}</b></h4>
                <form class="form-horizontal m-t-20" action="@if(request()->route()->getName()=='admin.fake_get_login') login @endif" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="col-12">
                            <input class="form-control" type="text" required="" placeholder="Username" name="username">
                        </div>
                    </div>

                    @if($errors->has('username'))
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                    @endif

                    <div class="form-group">
                        <div class="col-12">
                            <input class="form-control" type="password" required="" placeholder="Password" name="password">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox-signup" type="checkbox" checked name="remember">
                                <label for="checkbox-signup">
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-40">
                        <div class="col-12">
                            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">{{__('global.login')}}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>



    <!-- jQuery  -->
    <script src="{{asset('js/admin/jquery.min.js')}}"></script>
    <script src="{{asset('js/admin/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/admin/modernizr.min.js')}}"></script>
    <script src="{{asset('js/admin/detect.js')}}"></script>
    <script src="{{asset('js/admin/fastclick.js')}}"></script>
    <script src="{{asset('js/admin/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.blockUI.js')}}"></script>
    <script src="{{asset('js/admin/waves.js')}}"></script>
    <script src="{{asset('js/admin/wow.min.js')}}"></script>
    <script src="{{asset('js/admin/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/admin/jquery.scrollTo.min.js')}}"></script>
    <script src="{{asset('js/admin/app.js')}}"></script>
</body>

</html>
