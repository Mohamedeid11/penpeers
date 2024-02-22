@extends("web.layouts.master")
@section('heads')
<link href="{{ asset('plugins/bootstrap-sweetalert/sweet-alert.css') }}" rel="stylesheet"
    type="text/css">
<link rel="stylesheet" href="{{asset('css/web/authors.css')}}">
@endsection
@include('web.partials.page-title', ['background' => 'bg-image--6', 'title' => __('global.authors_in_house'), 'sub_title' => __('global.authors')])
@section('content')
@include('web.partials.flashes')
<main>
    <div class="authors-main-section">
        <!-- Search authors -->
        <header class="authors-main-serach">
            <h2 class="heading text-center text-secondary">{{ __('global.search_authors') }} </h2>
            <form method="get" action="{{ route('web.authors') }}" class="row mx-auto w-75">
                @php
                    if(request()->book_id){
                    $book_id = request()->book_id;
                    echo "<input type='hidden' name='book_id' value='$book_id' />";
                    echo "<input type='hidden' name='register_type' value='1' />";
                    }
                @endphp
                <div class="col-md-5 col-sm-6 col-12 mb-2 pl-md-0">
                    <select class="form-control" name="interests" id="interests">
                        <option value="">{{ __('global.search_authors_interest') }}</option>
                        @foreach($interests as $interest)
                            <option value="{{ $interest->id }}" @if(request()->get('interests') == $interest->id )
                                selected @endif>
                                {{ $interest->trans('name') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 col-sm-6 col-12 mb-2 pl-md-0">
                    <input class="form-control" name="search" type="text"
                        placeholder="{{ __('global.search_authors_name') }}"
                        value="{{ request()->get('search') }}">
                </div>
                <div class="col-md-2 col-sm-3 col-12 mb-2 p-md-0">
                    <button type="submit"
                        class="btn btn-primary w-100">{{ __('global.search') }}</button>
                </div>
            </form>
        </header>

        <!-- All authors -->
        <section class="container" id="authors">
            <div class="row">
                @foreach($authors as $author)
                    <section class="col-md-3 col-6">
                        @include('web.partials.author-card')
                    </section>
                @endforeach
            </div>
        </section>
        <div class="row justify-content-center my-4">
            {!! $authors->links() !!}
        </div>
    </div>

    @if(request()->get('book_id') && auth()->check())
        <form class="position-fixed w-100 bg-white p-3 d-flex z-10 justify-content-center"
            style="box-shadow: 0 -2px 10px rgb(0 0 0 / 25%); z-index: 100; bottom: 0" id="invite-author" method="POST"
            action="{{ route('web.dashboard.books.authors.store', ['book' => request()->get('book_id')]) }}">
            @csrf
            <input type="hidden" name="register_type" value="1" />
            <input type="hidden" name="email[]" value="[]" />
            <input type="hidden" name="book_role_id" value="{{ request()->get('book_role_id') }}" />
            <button class="btn btn-primary mt-auto px-4 py-2" style="font-size: large;"
                disabled>{{ __('global.invite_selected') }}
                <span>(0)<span></button>
        </form>
    @endif

</main>
@endsection
@section('scripts')
<script src="{{ asset('plugins/bootstrap-sweetalert/sweet-alert.min.js') }}"></script>
<script>
    let inviteesNum = 0;
    const inviteForm = document.querySelector("#invite-author");

    $(document).ready(function () {
        let url = new URL(location.href);
        if (url.searchParams.get('search') || url.searchParams.get('interests')) {
            $('html, body').animate({
                scrollTop: $('.authors-main-section').offset().top
            }, '2000');
        }
    })

    if (inviteForm) {
        const inviteBtn = inviteForm.querySelector("button");
        const inviteesNumElm = inviteForm.querySelector("button span");
        const inviteesEmailsElm = inviteForm.querySelector("input[name='email[]']");

        // Invite selected authors
        inviteForm.addEventListener("submit", (e) => {
            e.preventDefault();
            swal({
                title: "{{ __('global.confirm_authors_invitations') }}",
                type: "success",
                showCancelButton: !0,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "{{ __('global.confirm') }}",
                cancelButtonText: "{{ __('admin.cancel') }}",
                closeOnConfirm: false
            }, function (confirm) {
                if (confirm) {
                    inviteForm.submit();
                }
            });
        })

        // Select author
        document.querySelectorAll(".author.card:not(.invited)").forEach(card => {
            card.addEventListener("click", (e) => {
                const selectBtn = card.querySelector(".select-btn");
                const email = e.currentTarget.dataset.email;
                let val = JSON.parse(inviteesEmailsElm.value);

                if (card.classList.contains("invited")) return;
                else if (card.classList.contains("selected")) {
                    const index = val.findIndex(e => e === email);
                    val.splice(index, 1);
                    inviteesNum--;
                    if (inviteesNum === 0) inviteBtn.disabled = true;
                    selectBtn.innerHTML = "<i class='fa-solid fa-check'></i> {{__('global.select')}}";
                    selectBtn.classList.remove("btn-outline-primary");
                    selectBtn.classList.add("btn-primary");
                } else {
                    val.push(email);
                    inviteesNum++;
                    inviteBtn.disabled = false;
                    selectBtn.innerHTML = "<i class='fa-solid fa-times'></i> {{__('global.unselect')}}";
                    selectBtn.classList.add("btn-outline-primary");
                    selectBtn.classList.remove("btn-primary");
                };

                inviteesEmailsElm.value = JSON.stringify(val);
                inviteesNumElm.innerHTML = `(${inviteesNum})`;
                card.classList.toggle("selected");
            })
        })

        // Prevent select author when click on author name
        document.querySelectorAll(".author-name").forEach(link => {
            link.addEventListener("click", (e) => e.stopPropagation());
        })
    }

</script>
@endsection
