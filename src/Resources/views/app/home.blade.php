<x-church::website.applayout pageName="App home">
@forelse ($content as $item)
    @if (isset($item->published_at))
        <div class="lead pt-3">
            <a href="{{url('/blog') . '/' . date('Y',strtotime($item->published_at)) . '/' . date('m',strtotime($item->published_at)) . '/' . $item->slug . '/app'}}">{{$item->title}}</a>
        </div>
        <small class="text-muted">{{\Carbon\Carbon::parse($item['published_at'])->diffForHumans()}}</small> 
        <div><img src="{{url('/storage/' . $item->image)}}" alt="Image" class="img-fluid rounded"></div>
        <div>{!! nl2br($item->excerpt) !!}</div>
    @elseif (isset($item->readings))
        <div class="lead pt-3">{{$item->title}}</div>
        <small class="text-muted">{{\Carbon\Carbon::parse($item['servicedate'])->diffForHumans()}}</small>
        <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">
            <img class="card-img-top" src="{{url('/storage/' . $item->series->image)}}" alt="{{$item->series->series}}">
        </a>
        <div class="lead pt-3">{{$item->readings}}
            <small class="text-muted">{{$item->person->fullname}}</small>
        </div>
    @else 
        <img class="card-img-top" src="{{url('/storage/' . $item->image)}}" alt="{{$item->reading}}">
    @endif
@empty
    No items have been published in the last 40 days.
@endforelse
</x-church::website.applayout>