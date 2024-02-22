@php
    if (request()->get('book_id') && auth()->check()) {
        $participant = App\Models\BookParticipant::where(['book_id' => (int) request()->get('book_id'), 'user_id' => $author->id])->first();
    }
@endphp
<div
    class="
        author card position-relative
        @if(isset($participant) && $participant != null) invited @endif
        @if(request()->get('book_id') && auth()->check()) cursor-pointer @endif"
    data-email="{{$author->email}}"
>
    <div class="ribbon"><span>
        @if(isset($participant) && $participant != null)
            @php
                if($participant->status == 1){
                    echo $participant->book_role->display_name;
                } elseif($participant->status == 2){
                    echo __('global.rejected');
                }else{
                    echo __('global.pending');
                }
            @endphp
        @else
            {{__('global.selected')}}
        @endif
    </span></div>
    <div class="card-body text-center d-flex flex-column align-items-center bg-white">
        <img class="avatar rounded-circle" src="{{storage_asset($author->profile_pic)}}" alt="{{$author->name}}">
        <a class="author-name text-primary text-underline"
            href="{{route('web.author_books', ['author'=>$author->id, 'type'=> 'all_books'])}}"
            @if(request()->book_id) target="_blank" @endif>
            <h6 class="card-title">{{$author->name}}</h6>
        </a>
        @if(request()->get('book_id') && auth()->check() && $author->plan && $author->id != auth()->id())
            <small>{{ __('global.subscribtion_ends_at') }}
                <b>{{ $author->plan->end_date }}</b></small>
        @endif

        @if(request()->get('book_id') && auth()->check() && !isset($participant))
        <div class="d-flex justify-content-center flex-wrap w-100 mt-auto">
            <button class="btn btn-primary m-2 select-btn"><i class="fa-solid fa-check"></i> {{__('global.select')}}</button>
        </div>
        @endif
    </div>
</div>
