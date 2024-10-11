<x-church::website.layout pageName="Blog post">
    <h3 class="text-uppercase">{{$book->title}}</h3>
    <div class="row gy-2">
        <div class="col-md-6 col-sm-12">
            <div class="pb-3">
                {{$book->allauthors}}
                @foreach ($book->tags as $tag)
                    <span class="badge text-uppercase"><a href="{{url('/')}}/subject/{{$tag->slug}}" class="">{{$tag->name}}</a></span>
                @endforeach
            </div>
            <div>
                <div>
                    <img style="float:left; height:200px; padding-right:15px;" src="{{$book->image}}">
                </div>
                {{$book->description}}<br>
                <b>Publisher:</b> {{$book->publisher}}<br>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <h4>Reviews</h4>
            @if (count($member))
                @livewire('bookreview')
            @else
                To rate or view books, please <a href="{{url('/')}}/login">login</a>
            @endif
        </div>
    </div>
</x-church::layout>                
