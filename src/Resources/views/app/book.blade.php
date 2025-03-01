<x-church::layouts.app pageName="Blog post">
    <h3 class="text-uppercase">{{$book->title}}</h3>
    <div class="row gy-2">
        <div class="col-md-6 col-sm-12">
            <div class="pb-3">
                {{$book->allauthors}}
                @foreach ($book->tags as $tag)
                    <span class="badge text-uppercase"><a href="{{url('/')}}/subject/{{$tag->slug}}" class="badge">{{$tag->name}}</a></span>
                @endforeach
                @if (count($member))
                    <small>{{$book->status}}</small>
                @endif
            </div>
            <div>
                <div>
                    <img style="float:left; height:200px; padding-right:15px;" src="{{$book->image}}">
                </div>
                {{$book->description}}<br>
                @if(isset($book->publisher))<b>Publisher:</b> {{$book->publisher}}<br>@endif
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <h4>Reviews</h4>
            @if (count($member))
                @livewire('bookreview',['book'=>$book,'key'=>$book->id,'member'=>$member['id']])
                @foreach ($book->comments as $comment)
                    <div class="mt-3">
                        {{$comment->comment}}
                        <div><a href="#">{{$comment->individual->firstname}} {{$comment->individual->surname}}</a> <small>{{$comment->created_at->diffForHumans()}}</small></div>
                    </div>
                @endforeach
            @else
                To rate or review books, please <a href="{{url('/')}}/login">login</a>
            @endif
        </div>
    </div>
</x-church::layout>                
