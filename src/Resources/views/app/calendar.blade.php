<x-church::layouts.app pageName="Groups">
    <h3 class="text-center">
        {{setting('general.church_abbreviation')}} Diary
        @if ($full=="")
            (Your events only)
        @endif
    </h3>
    <p class="text-center">
        @if ($full=="")
            <a href="{{route('app.calendar',['full'=>'yes'])}}" class="btn btn-secondary">View full church calendar</a>
        @else
            <a href="{{route('app.calendar')}}" class="btn btn-secondary">View personal church calendar</a>
        @endif
    </p>
    <table class="table table-compact">
    @php
        $mth=date('F');
    @endphp
    <tr><th colspan="3" class="text-center">{{$mth}}</th></tr>
    @forelse ($events as $thisdate=>$dayevents)
        @if (date('F',strtotime($thisdate))<>$mth)
            @php
                $mth=date('F',strtotime($thisdate));
            @endphp
            <tr><th colspan="3" class="text-center">{{$mth}}</th></tr>
        @endif
        @foreach ($dayevents as $dayevent)
            <tr>
                <td>{{date('D d M',strtotime($thisdate))}}</td><td>{{$dayevent['time']}}</td><td>{{$dayevent['name']}}</td>
            </tr>
        @endforeach
    @empty
        <tr><td colspan="3">No events have been added to the calendar yet.</td></tr>
    @endforelse
    </table>
</x-church::layout>                
