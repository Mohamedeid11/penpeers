@extends('web.layouts.dashboard')
@section('heads')
@include('web.partials.datatables-css')
<link rel="stylesheet" href="{{asset('css/web/buying-requests.css')}}" />
@endsection
@section('content')
    @php
        $route = route('web.dashboard.buying_requests');
    @endphp
    <div class="main-page">
        @include('web.partials.dashboard-header', ['title' => __('global.book_buying_requests'), 'sub_title' => __('global.buying_book_requests'), 'current' => '<li class="active"><a href="'.$route.'">'.__('global.book_buying_requests').'</a></li>'])
        <br><br>
        @include('web.partials.flashes')
        <section class="section">
            <header class="container-fluid">

                <div class="col-md-12 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title d-flex">
                                <h2 class="h5">{{ __('global.book_buying_requests') }}</h2>
                            </div>
                        </div>
                        <div class="panel-body p-20">
                            <div class="row">
                                <table class="table table-bordered datatable dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('global.book') }}</th>
                                            <th>{{ __('global.name') }}</th>
                                            <th>{{ __('global.email') }}</th>
                                            <th>{{ __('global.phone') }}</th>
                                            <th>{{ __('global.message') }}</th>
                                            <th>{{ __('global.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="line-height-35">
                                    @foreach($buying_requests->sortByDesc('created_at') as $buy_request)
                                        <tr>
                                            <td class="dt-control"></td>
                                            <td>
                                                <a class="text-underline text-secondary" href="{{route('web.dashboard.books.authors.index', ['book'=>$buy_request->book->id])}}">{{$buy_request->book->title}}</a>
                                            </td>
                                            <td>{{ $buy_request->name }}</td>
                                            <td>{{$buy_request->email}}</td>
                                            <td>{{$buy_request->phone}}</td>
                                            <td><span class="buy-message lines">{{ $buy_request->message }}</span>
                                                <button
                                                    class="toggle-text btn text-secondary p-0 px-2 ml-auto d-block mt-1 font-weight-bold"
                                                    onclick="toggleMoreText(this)">{{ __('global.read_more') }}</button>
                                            </td>

                                            <td>
                                                <span class="blog-date">
                                                    {{$buy_request->created_at}}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </section>
    </div>

@endsection
@section('scripts')
    @include('web.partials.datatable')
    <script>
        const toggleMoreBtn = () => {
            document.querySelectorAll(".buy-message").forEach(elm => {
                if(elm.nextElementSibling.classList.contains('d-block') && elm.clientHeight >= elm.scrollHeight) {
                    elm.nextElementSibling.classList.add('d-none');
                    elm.nextElementSibling.classList.remove('d-block');
                } else if(elm.nextElementSibling.classList.contains('d-none') && elm.clientHeight < elm.scrollHeight) {
                    elm.nextElementSibling.classList.remove('d-none');
                    elm.nextElementSibling.classList.add('d-block');
                }
            })
        }

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
                const elms = Array.from($(tr).next().find(".blog-date"));

                elms.forEach(elm => {
                    const dateTime = new Date(`${elm.innerHTML}+00:00`);
                    const date = dateTime.toLocaleDateString(lang, {day: "numeric", month: "short", year: "numeric" });
                    const time = dateTime.toLocaleTimeString(lang, { hour: "2-digit", minute: "2-digit" });

                    elm.innerHTML = `${date}, ${time}`;
                })
            }

            toggleMoreBtn();
        });

        window.addEventListener("load", toggleMoreBtn);
        window.addEventListener("resize", toggleMoreBtn);

        const toggleMoreText = (e) => {
            const pElm = e.previousElementSibling;

            if(e.innerHTML === "{{ __('global.read_more') }}") {
                e.innerHTML = "{{ __('global.read_less') }}";
                pElm.classList.remove("lines");
            } else {
                e.innerHTML = "{{ __('global.read_more') }}";
                pElm.classList.add("lines");
            }
        }
    </script>
@endsection
