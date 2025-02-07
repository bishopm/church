<x-church::layouts.app pageName="Song">
    @if (count($member))
        <h4>Songs</h4>
        <form method="get">
            <div class="input-group">
                <input class="form-control" name="search" placeholder="Search by title or author" value="{{$search}}"><div class="input-group-append">
                &nbsp;<button class="btn btn-secondary" icon="bi bi-search"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <table class="table-responsive table-sm mt-2 mb-2">
        @forelse ($songs as $song)
            <tr>
                @if ($song->author)
                    <td><a href="{{url('/')}}/songs/{{$song->id}}">{{$song->title}}</a></td>
                    <td><small>{{$song->author}}</small></td>
                @else 
                    <td colspan="2"><a href="{{url('/')}}/songs/{{$song->id}}">{{$song->title}}</a></td>
                @endif
            </tr>
        @empty
            No songs have been added yet
        @endforelse
        </table>
        <small>{{$songs->links()}}</small>
    @else
        Sorry! You need to be logged in to see this page
    @endif
</x-church::layout>                
