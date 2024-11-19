<x-church::website.applayout pageName="App home">
    @if ($service) 
        <div class="bg-black p-2 text-white">
            <h3>Upcoming service</h3>
                @if ($service->servicedate == date('Y-m-d'))
                    <div class="ratio ratio-16x9">
                        <iframe src='https://www.youtube.com/embed/live_stream?autoplay=1&channel={{setting('website.youtube_channel_id')}}' frameborder='0' allowfullscreen></iframe>
                    </div>
                @else
                    <div>Live stream starts in {{ $floor }} ({{date('j M Y',strtotime($service->servicedate))}} {{$service->servicetime}})
                @endif
            </div>
            <div class="py-2">
                <h5>Reading: <small><a href="http://biblegateway.com/passage/?search={{urlencode($service->reading)}}&version=GNT">{{$service->reading}}</a></small></h5>
                <h5>Preacher: <small>{{$preacher}}</small></h5>
                <h5>Series: <small><a href="{{url('/')}}/sermons/{{date('Y',strtotime($service->series->startingdate))}}/{{$service->series->slug}}">{{$service->series->series}}</a></small></h5>
                <h5>Songs</h5>
                <ul class="list-unstyled">
                    @forelse ($service->setitems as $song)
                        <li><a href="{{url('/')}}/songs/{{$song->setitemable_id}}">{{$song->setitemable->title}}</a></li>
                    @empty
                        No songs have been added yet
                    @endforelse
                </ul>
            </div>
        </div>
    @endif
    @forelse ($content as $item)
        @if (isset($item->published_at))
            <div class="lead pt-3">
                <a href="{{url('/blog') . '/' . date('Y',strtotime($item->published_at)) . '/' . date('m',strtotime($item->published_at)) . '/' . $item->slug}}">{{$item->title}}</a>
            </div>
            <small class="text-muted">{{\Carbon\Carbon::parse($item['published_at'])->diffForHumans()}}</small> 
            @if ($item->image)
                <div><img src="{{url('/storage/' . $item->image)}}" alt="Image" class="img-fluid rounded"></div>
            @endif
            <div>{!! nl2br($item->excerpt) !!}</div>
        @elseif (isset($item->readings))
            <div class="lead pt-3">
                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">{{$item->title}}</a>
            </div>
            <small class="text-muted">{{\Carbon\Carbon::parse($item['servicedate'])->diffForHumans()}}</small>
            <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">
                <img class="card-img-top" src="{{url('/storage/' . $item->series->image)}}" alt="{{$item->series->series}}">
            </a>
            <div class="lead pt-3">{{$item->readings}}
                <small class="text-muted">{{$item->person->fullname}}</small>
            </div>
        @elseif ($item->image)
            <a href="{{route('app.devotionals')}}">
                <img class="card-img-top" src="{{url('/storage/' . $item->image)}}" alt="{{$item->reading}}">
            </a>
        @endif
    @empty
        No items have been published in the last 40 days.
    @endforelse
</x-church::website.applayout>