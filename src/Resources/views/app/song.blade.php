<x-church::website.applayout pageName="Song">
    @if (count($member))
        <h4>{{$song->title}}</h4>
        <small>
            @if ($song->author)
                {{$song->author}}
            @endif
            <div>Last sung in a service: {{$song->lastused}}  • <a href="{{route('app.songs')}}">Index</a></div>
        </small>
        @if ($song->video)
            <div class="ratio ratio-16x9">
                <iframe src='{{$song->video}}' frameborder='0' allowfullscreen></iframe>
            </div>
        @endif
        <div>{!! nl2br(preg_replace('/\[[^\]]*\]/', '', $song->lyrics)) !!}</div>
    @else
        Sorry! You need to be logged in to see this page
    @endif
</x-church::layout>                
