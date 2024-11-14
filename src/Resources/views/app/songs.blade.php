<x-church::website.applayout pageName="Song">
    <h4>Songs</h4>
    @forelse ($songs as $song)
        <ul class="list-unstyled">
            <li>
                <a href="{{url('/')}}/app/songs/{{$song->id}}">{{$song->title}}</a> 
                @if ($song->author)
                    &nbsp;<small>({{$song->author}})</small>
                @endif
            </li>
        </ul>
    @empty
        No songs have been added yet
    @endforelse
</x-church::layout>                
