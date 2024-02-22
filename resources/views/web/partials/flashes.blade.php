@if(session()->has('success'))
    <div class="container-fluid">
        <label class="w-100 alert alert-success alert-dismissible">
            {!! session()->get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></label>
    </div>
@endif


@if(session()->has('error'))
    <label class="alert alert-danger w-75 m-auto alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        {!! session()->get('error') !!}
    </label>
@endif
