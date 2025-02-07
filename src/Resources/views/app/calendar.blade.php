<x-church::layouts.app pageName="Groups">
    <h3>
        {{setting('general.church_abbreviation')}} Diary
        @if ($full=="")
            (Your events only)
        @endif
    </h3>
    @if ($full=="")
        <a href="{{route('app.calendar',['full'=>'yes'])}}" class="btn btn-secondary">View full church calendar</a>
    @else
        <a href="{{route('app.calendar')}}" class="btn btn-secondary">View personal church calendar</a>
    @endif
    <table class="table-compact">
    @forelse ($events as $thisdate=>$dayevents)
        <tr><th colspan="2">{{date('D, j M Y',strtotime($thisdate))}}</th></tr>
        @foreach ($dayevents as $dayevent)
            <tr>
            @if ($dayevent['me']=="yes")
                <th>{{$dayevent['time']}}</th><th>{{$dayevent['name']}}</th>
            @else
                <td>{{$dayevent['time']}}</td><td>{{$dayevent['name']}}</td>
            @endif
        @endforeach
    @empty
        <tr><td>No events have been added to the calendar yet.</td></tr>
    @endforelse
    </table>
</x-church::layout>                
