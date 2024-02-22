@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
@endsection
@section('content')
<div class="main-page">
    @php
        $menu = [[
            'title' => 'Manage '.$book->trans('title'),
            'type' => 'active'
        ]];
    @endphp
    @include('web.partials.book-top-bar', ['menu'=>$menu])
    <section class="section">
        <div class="container-fluid">
            @include('web.partials.book-nav-section')
            <div class="mt-4">
                @include('web.partials.authors-nav-section')
            </div>
            <div class="panel">
                <header class="panel-heading">
                    <div class="panel-title d-flex flex-wrap justify-content-between">
                        <h2 class="h5">
                            @if($type == 'active') {{__('global.book_authors')}} @else {{__('global.book_invitations')}} @endif
                        </h2>
                        <div class="px-2">
                            <input type="checkbox" id="authors-toggle" data-toggle="toggle" data-on="{{__('global.show_active_authors')}}" data-off="{{__('global.show_inactive_authors')}}" data-width="240" data-height="37" data-onstyle="primary" data-offstyle="secondary" @if($type=='active' ) checked @endif>
                        </div>
                    </div>
                </header>
                <div class="panel-body p-20">
                    @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                    <div class="container-fluid">
                        <form class="form" method="POST" id="invite-author-form" action="{{route('web.dashboard.books.authors.store', ['book'=>$book->id])}}">
                            {{@csrf_field()}}
                            <input type="hidden" name="book_id" value= "{{$book->id}}" />
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <label class="my-1"
                                        for="register_type-input">{{ __('global.authors_source_list') }}</label>
                                    <select class="form-control my-1" required id="register_type-input" name="register_type">
                                        <option value="" {{select_value(null, 'register_type', "")}} selected>{{__('global.choose')}}</option>
                                        <option value="1"
                                            {{ select_value(null, 'register_type', "1") }}>
                                            {{ __('global.registered_penpeers') }}</option>
                                        <option value="2"
                                            {{ select_value(null, 'register_type', "2") }}>
                                            {{ __('global.non_registered_penpeers') }}</option>
                                    </select>
                                </div>

                                <div id="name-input-div" class="form-group col-12 col-md-3 d-none">
                                    <label class="my-1 mr-2" for="name">{{__('global.name')}}</label>
                                    <input class="form-control mb-0" type="text" placeholder="{{__('global.name')}}" value="{{old('name')}}" id="name" name="name">
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('name') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                        @endif
                                </div>

                                <div id="email-input-div" class="form-group col-12 col-md-3 d-none">
                                    <label class="my-1 mr-2" for="email-input">{{__('global.email')}}</label>
                                    <input class="form-control mb-0" type="text" placeholder="{{__('global.email')}}" value="{{old('email')}}" id="email" name="email">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            @foreach($errors->get('email') as $error)
                                            {{$error}}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group col-12 col-md-3" id="invite-author">
                                    <label class="my-1 mr-2">.</label>
                                    <button type="submit"
                                        class="btn btn-primary my-1">{{ __('global.invite') }}</button>
                                    <a href="{{ route('web.authors') . "?book_id=" . $book->id . "&register_type=1" }}"
                                        class="btn btn-primary my-1 d-none"
                                        id="continue">{{ __('global.continue') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="container-fluid">
                        <div class="row">
                            @if($type == 'active')
                            <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('global.author')}}</th>
                                        <th>{{__('global.role')}}</th>
                                        <th>{{__('global.status')}}</th>
                                        <th>{{ __('global.assigned_chapters') }}</th>
                                        @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                            <th>{{__('global.actions')}}</th>
                                        @endif
                                    </tr>

                                </thead>
                                <tbody class="line-height-35">
                                    @foreach($book->book_participants()->active()->get() as $book_participant)
                                    @php
                                        $valid_author = (! $book_participant->user->validity && ! $book_participant->user->free_period_after_expiry)? true : false;
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <td>
                                            <a @if($valid_author) data-title='This author needs to renew subscription plan to continue.' @endif
                                                class="text-secondary text-underline @if($valid_author) text-danger @endif"
                                                href="{{route('web.author_books', ['author' => $book_participant->user->id, 'type' => 'lead_books'])}}"
                                                data-toggle="tooltip"
                                                data-placement="top">
                                                @if($valid_author) <i class='fa-solid fa-triangle-exclamation'></i> @endif
                                                {{$book_participant->user->name}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$book_participant->book_role->trans('display_name')}}
                                        </td>
                                        <td>{{$book_participant->status_text}}</td>

                                        <td>
                                            @foreach($book_participant->book_special_chapters as $book_special_chapter)
                                            <a  class="text-secondary text-underline" href="{{route('web.dashboard.books.editions.special_chapters.index', ['book' => $book->id , 'edition' => 1])}}">
                                            {{$book_special_chapter->special_chapter->trans('display_name')}}</a>,
                                            @endforeach
                                            @foreach($book_participant->book_chapters as $book_chapter)
                                            <a class="text-secondary text-underline"
                                                href="{{ route('web.dashboard.books.editions.chapters.edit', ['book' => $book->id , 'edition' => $book_chapter->book_edition->edition_number , 'chapter' => $book_chapter->id ]) }}">
                                                {{$book_chapter->name}}</a>,
                                            @endforeach
                                        </td>
                                        @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                            <td>
                                                @if($book_participant->book_role->id != 1 )
                                                    <form method="post" id="delete-author" class="delete-author d-inline-block" action="{{route('web.dashboard.books.authors.destroy', ['book'=>$book, 'author'=>$book_participant])}}">
                                                        @method('delete')
                                                        @csrf
                                                        <a type="submit" class="delete-author btn btn-danger mx-1" title="delete-author" data-toggle="tooltip" data-placement="top"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></a>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>{{__('global.author')}}</th>
                                        <th>{{ __('global.invited_at') }}</th>
                                        <th>{{__('global.status')}}</th>
                                        @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                            <th>{{__('global.actions')}}</th>
                                        @endif
                                    </tr>

                                </thead>
                                <tbody class="line-height-35">
                                    @foreach($book->book_participants()->notActive()->get() as $book_participant)
                                        <tr>
                                            <td class="dt-control"></td>
                                            <td>
                                                <a href="{{ route('web.author_books', ['author' => $book_participant->user->id, 'type' => 'lead_books']) }}"
                                                    class="text-secondary text-underline">
                                                {{$book_participant->user->name}}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="blog-date">
                                                    {{$book_participant->created_at}}
                                                </span>
                                            </td>
                                            <td>{{$book_participant->status_text}}</td>
                                            @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                                <td class="d-flex flex-wrap" style="gap: 4px">
                                                    <form method="post"  class="remind-participant d-inline-block" action="{{route('web.dashboard.books.authors.email_reminder', ['book'=>$book,'type' => 'byUsername', 'email'=> $book_participant->user->email ])}}">
                                                        @csrf
                                                        <a type="submit" class="remind-participant btn btn-primary" title="{{__('global.remind_participant')}}" data-toggle="tooltip" data-placement="top"><i class="fa-solid fa-bell" aria-hidden="true"></i></a>
                                                    </form>

                                                    <form method="post"  class="delete-invitation d-inline-block" action="{{route('web.dashboard.books.authors.delete_invitation', ['book'=>$book, 'type' => 'book_participants', 'id'=> $book_participant->id ])}}">
                                                        @method('delete')
                                                        @csrf
                                                        <a type="submit" class="delete-invitation btn btn-danger" title="{{__('global.delete_invitation')}}" data-toggle="tooltip" data-placement="top"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></a>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @foreach($book->email_invitations()->PendingAndRejected()->get() as $email_invitation)
                                        <tr>
                                            <td class="dt-control"></td>
                                            <td>{{$email_invitation->email}}</td>
                                            <td>
                                                <span class="blog-date">
                                                    {{$email_invitation->created_at}}
                                                </span>
                                            </td>
                                            <td>{{ $email_invitation->status == 0 ? __('global.pending') : __('global.rejected') }}
                                            </td>
                                            @if($book->lead_author->id == auth()->id() && !$book->deleted_at)
                                                <td>
                                                    <form method="post"  class="remind-participant d-inline-block" action="{{route('web.dashboard.books.authors.email_reminder', ['book'=>$book,'type' => 'byEmail', 'email'=> $email_invitation->email ])}}">
                                                        @csrf
                                                        <a type="submit" class="remind-participant btn btn-primary mx-1"
                                                            title="{{ __('global.remind_participant') }}"
                                                            data-toggle="tooltip" data-placement="top"><i class="fa-solid fa-bell"
                                                                aria-hidden="true"></i></a>
                                                    </form>
                                                    <form method="post"  class="delete-invitation d-inline-block" action="{{route('web.dashboard.books.authors.delete_invitation', ['book'=>$book, 'type' => 'email_invitations', 'id'=> $email_invitation->id ])}}">
                                                        @method('delete')
                                                        @csrf
                                                        <a type="submit" class="delete-invitation btn btn-danger mx-1"
                                                            title="{{ __('global.delete_invitation') }}"
                                                            data-toggle="tooltip" data-placement="top"><i class="fa-solid fa-trash-can"
                                                                aria-hidden="true"></i></a>
                                                    </form>
                                                </td>
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@section('scripts')
@include('web.partials.datatable')
<script>
    $('#authors-toggle').change(function() {
        if ($(this).prop('checked')) {
            location.href = "{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}"
        } else {
            location.href = "{{route('web.dashboard.books.authors.index', ['book'=>$book->id])}}?pending"
        }
    });

    $('.invalid-feedback').show();

    // Registered or non-registered user
    document.getElementById("register_type-input").addEventListener("change", () => {
        var selected_value = +$("#register_type-input").val();


        if (selected_value === 1) {
            $("#name-input-div").addClass("d-none");
            $("#name").removeAttr('required');

            $("#email-input-div").addClass("d-none");
            $("#email").removeAttr('required');

            $("#invite-author button").addClass("d-none");
            $("#invite-author a").removeClass("d-none");
        } else if (selected_value === 2) {
            $("#name-input-div").removeClass("d-none");
            $("#name").attr('required', 'required');

            $("#email-input-div").removeClass("d-none");
            $("#email").attr('required', 'required');

            $("#invite-author button").removeClass("d-none");
            $("#invite-author a").addClass("d-none");
        } else {
            $("#invite-author button").removeClass("d-none");
            $("#invite-author a").addClass("d-none");

            $("#name-input-div").addClass("d-none");
            $("#name").removeAttr('required');

            $("#email-input-div").addClass("d-none");
            $("#email").removeAttr('required');
        }
        $('#book_role-input').val('');
        $("#email").val('');
        $("#name").val('');
    });

    $('body').on('click', '#delete-author', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.delete_this_author') }}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, function() {
            $('#delete-author').submit();

        });
    });

    $('body').on('click', '.remind-participant', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.send_email_again') }}",
            type: 'success',
            confirmButtonClass: "btn-primary",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{__('global.ok')}}"
        }, function() {
            $(e.currentTarget).submit();
        });
    });

    $('body').on('click', '.delete-invitation', function(e) {
        e.preventDefault();
        swal({
            title: "{{ __('global.delete_this_invitation') }}",
            type: 'error',
            confirmButtonClass: "btn-danger",
            showCancelButton: true,
            cancelButtonText: "{{__('global.cancel')}}",
            confirmButtonText: "{{ __('global.ok') }}"
        }, function() {
            $(e.currentTarget).submit();
        });
    });

    // Format dates
    document.querySelectorAll(".blog-date").forEach(item => {
        const dateTime = new Date(`${item.textContent}+00:00`);
        const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
        const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" })

        item.textContent = `${date}, ${time}`;
    })

    $('.table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row(tr);
        if (row.child.isShown()) {
            const dateTime = new Date(`${$(tr).next().find(".blog-date").text()}+00:00`);
            const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
            const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" })

            $(tr).next().find(".blog-date").text(`${date}, ${time}`)
        }
    });
</script>




@endsection
