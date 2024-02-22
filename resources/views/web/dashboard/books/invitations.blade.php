@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
@endsection
@section('content')
@php
    $type = (Str::contains(request()->route()->getName(), 'received')? 'received' : (Str::contains(request()->route()->getName(), 'author_emailInvitations')?'email_invitations' : 'participants'));
    $route = route("web.dashboard.author_receivedInvitations");
@endphp
<main class="main-page">
    @include('web.partials.dashboard-header', ['title' => __('global.my_invitations'), 'sub_title' => __('global.invitations_sub_heading'), 'current' => '<li class="active"><a href="'.$route.'">'.__('global.my_invitations').'</a></li>'])
    <br><br>
    @include('web.partials.flashes')
    @php
        $received_invitation_notification = count( auth()->user()->unreadNotifications()->where('data','LIKE','%"url_type":"received_invitations"%')->get() ) ;
        $sent_invitation_notification = count( auth()->user()->unreadNotifications()->where(function($query){$query->orWhere('data', 'LIKE', '%"url_type":"accept_registered_author"%')
                                                                                                ->orWhere('data', 'LIKE', '%"url_type":"reject_registered_author"%'); })->get() )  ;
        $email_invitation_notification = count( auth()->user()->unreadNotifications()->where(function($query){$query->orWhere('data', 'LIKE', '%"url_type":"accepted_unregistered_author"%')
                                                                                            ->orWhere('data', 'LIKE', '%"url_type":"reject_unregistered_author"%'); })->get() )  ;
    @endphp
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <nav class="col-md-12">
                    <ul class="nav justify-content-center nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'received') active  @endif" href="{{route('web.dashboard.author_receivedInvitations')}}">{{__('global.received')}}@if($received_invitation_notification > 0)  <span class="badge badge-secondary">{{  $received_invitation_notification }}</span> @endif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'participants') active  @endif" href="{{route('web.dashboard.author_participants')}}">{{__('global.sent')}} @if($sent_invitation_notification > 0)  <span class="badge badge-secondary">{{ $sent_invitation_notification  }} </span> @endif </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($type == 'email_invitations') active  @endif" href="{{route('web.dashboard.author_emailInvitations')}}">{{__('global.direct_mail')}} @if($email_invitation_notification > 0) <span class="badge badge-secondary">{{ $email_invitation_notification }}</span> @endif </a>
                        </li>
                    </ul>
                </nav>
                <div class="col-md-12 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title d-flex">
                                <h2 class="h5">
                                    @if($type == 'received') {{__('global.received_invitations')}} @elseif($type == 'participants') {{__('global.sent_invitations')}} @else {{__('global.direct_email_invitations')}} @endif
                                </h2>
                            </div>
                        </div>
                        <div class="panel-body p-20">
                            <div class="container-fluid">
                                <div class="row">
                                    @if($type == 'received')
                                        <table class="table table-bordered datatable datatable-invitations dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>{{__('global.lead_authors')}}</th>
                                                    <th>{{__('global.book')}}</th>
                                                    <th>{{__('global.role')}}</th>
                                                    <th>{{__('global.status')}}</th>
                                                    <th>{{__('global.date')}}</th>
                                                    <th>{{__('global.actions')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="line-height-35">
                                                @foreach ($receivedInvitations->sortByDesc('created_at') as $item)
                                                <tr>
                                                    @php
                                                        $lead_author = App\Models\BookParticipant::leadAuthor($item->book_id)->first();
                                                        $type = ($item->book->receivable() && ($item->book->completed == 2 || $item->book->completed == 0))? 'lookingForCoAuthor' : 'completed';
                                                    @endphp
                                                    <td></td>
                                                    <td class="dt-control"><a href="{{route('web.author_books', ['author' => $lead_author->user->id, 'type' => 'all_books'])}}" class="text-underline text-secondary">{{$lead_author->user->name}}</td>
                                                    <td><a class="text-underline text-secondary" href="{{route('web.view_book', ['slug' => $item->book->slug , 'type'=> $type])}}">{{$item->book->title}}</a></td>
                                                    <td>{{$item->book_role->display_name}}</td>
                                                    <td  class="@if($item->status_text == 'Pending') text-primary @elseif($item->status_text == 'Rejected') text-danger @else text-success @endif">
                                                        {{$item->status_text}}

                                                        {!!isset($item->answered_at ) ? " on <span class='blog-date'>" . $item->answered_at . "</span>"  : ''!!}
                                                    </td>
                                                    <td><span class="blog-date">{{$item->created_at}}</span></td>
                                                    <td class="d-flex flex-wrap" style="gap: 4px">
                                                        @if ($item->status == 0)
                                                        <form id="accept-form-{{$item->id}}" action="{{route('web.dashboard.author_acceptParticipation')}}" method="post" class="d-inline-block">
                                                            @csrf
                                                            <input type="hidden" name="participant_id" value="{{$item->id}}">
                                                            <button type="submit" id="accept-button" title="accept" itemId="{{$item->id}}" class="btn btn-primary"><i class="fa-solid fa-check"></i></button>
                                                        </form>
                                                        <form id="reject-form-{{$item->id}}" action="{{ route('web.dashboard.author_rejectParticipation' , ['id' => base64_encode($item->id)]) }}" method="get" class="d-inline-block">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{base64_encode($item->id)}}">
                                                            <button type="submit" id="reject-button" title="reject" itemId="{{$item->id}}" class="btn btn-primary"><i class="fa-solid fa-xmark" style="width: 16px"></i></button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @elseif($type == 'email_invitations')
                                        <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>{{__('global.email')}}</th>
                                                    <th>{{__('global.invited_at')}}</th>
                                                    <th>{{__('global.book')}}</th>
                                                    <th>{{__('global.role')}}</th>
                                                    <th>{{__('global.status')}}</th>
                                                </tr>

                                            </thead>
                                            <tbody class="line-height-35">
                                                @foreach ($emailInvitations->sortByDesc('created_at') as $item)
                                                <tr>
                                                    <td></td>
                                                    <td>{{$item->email}}</td>
                                                    <td>{{$item->invited_at}}</td>
                                                    <td><a class="text-underline text-secondary" href="{{ route('web.dashboard.books.authors.index', ['book'=>$item->book->id]) }}">{{ $item->book->title }}</a>
                                                    </td>
                                                    <td>{{$item->book_role->display_name}}</td>
                                                    <td  class="@if($item->status_text == 'Pending') text-primary @elseif($item->status_text == 'Rejected') text-danger @else text-success @endif">
                                                        {{$item->status_text}}
                                                        {!!isset($item->answered_at ) ? " on <span class='blog-date'>" . $item->answered_at . "</span>"  : ''!!}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>{{__('global.authors')}}</th>
                                                    <th>{{__('global.book')}}</th>
                                                    <th>{{__('global.role')}}</th>
                                                    <th>{{__('global.status')}}</th>
                                                    <th>{{__('global.date')}}</th>
                                                    <th>{{__('global.actions')}}</th>
                                                </tr>

                                            </thead>
                                            <tbody class="line-height-35">
                                                @foreach ($participants->sortByDesc('created_at') as $item)
                                                    <tr>
                                                        <td></td>
                                                        <td class="dt-control">
                                                            <a class="text-underline text-secondary" href="{{route('web.author_books', ['author' => $item->user->id, 'type' => 'all_books'])}}">
                                                                {{$item->user->name}}
                                                            </a>
                                                        </td>
                                                        <td><a class="text-underline text-secondary" href="{{route('web.dashboard.books.authors.index', ['book'=>$item->book->id])}}">{{$item->book->title}}</a></td>
                                                        <td>{{$item->book_role->display_name}}</td>
                                                        <td  class="@if($item->status_text == 'Pending') text-primary
                                                                @elseif($item->status_text == 'Rejected') text-danger
                                                                @else text-success @endif">
                                                            {{$item->status_text}}
                                                            {!!isset($item->answered_at ) ? " on <span class='blog-date'>" . $item->answered_at . "</span>"  : ''!!}
                                                        </td>
                                                        <td><span class="blog-date">{{$item->created_at}}</span></td>
                                                        <td>
                                                            <form id="delete-form-{{$item->id}}" action="{{route('web.dashboard.author_deleteParticipation')}}" method="post" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="participant_id" value="{{$item->id}}">
                                                                <button id="delete-button" type="submit" title="delete" itemId="{{$item->id}}" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                                                            </form>
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
            </div>
        </div>
    </section>
</main>
@endsection
@section('scripts')
@include('web.partials.datatable')
<script>
    $(document).ready(function() {
        $('body').on('click', '#accept-button', function(e) {
            e.preventDefault();
            let item_id = $(this).attr('itemId');

            swal({
                title: "{{ __('global.accept_invitation') }}",
                type: "success",
                confirmButtonClass: "btn-primary",
                showCancelButton: true,
                cancelButtonText: "{{ __('global.cancel') }}",
                confirmButtonText: "{{ __('global.ok') }}",
            }, function() {
                $('#accept-form-' + item_id).submit();
            });
        });

        $('body').on('click', '#reject-button', function(e) {
            e.preventDefault();
            let item_id = $(this).attr('itemId');
            swal({
                title: "{{ __('global.reject_invitation') }}",
                type: "warning",
                confirmButtonClass: "btn-primary",
                showCancelButton: true,
                cancelButtonText: "{{ __('global.cancel') }}",
                confirmButtonText: "{{ __('global.ok') }}",
            }, function() {
                $('#reject-form-' + item_id).submit();
            });
        });

        $('body').on('click', '#delete-button', function(e) {
            e.preventDefault();
            let item_id = $(this).attr('itemId');
            swal({
                title: "{{ __('global.delete_participant') }}",
                type: "error",
                confirmButtonClass: "btn-danger",
                showCancelButton: true,
                cancelButtonText: "{{ __('global.cancel') }}",
                confirmButtonText: "{{ __('global.ok') }}",
            }, function() {
                $('#delete-form-' + item_id ).submit();
            });
        });

        // Format dates
        document.querySelectorAll(".blog-date").forEach(item => {
            const dateTime = new Date(`${item.textContent}+00:00`);
            const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
            const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

            item.textContent = `${date}, ${time}`;
        })

        $('.table tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = datatable.row(tr);
            if (row.child.isShown()) {
                const dateTime = new Date(`${$(tr).next().find(".blog-date").text()}+00:00`);
                const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
                const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

                $(tr).next().find(".blog-date").text(`${date}, ${time}`)
            }
        });
    })
</script>
@endsection
