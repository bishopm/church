<div>
    <h3 class="text-center">
        {{setting('general.church_abbreviation')}} Diary
    </h3>
    <p class="text-center">
        <a class="btn btn-secondary" wire:click="toggleStatus">{{$status}}</a>
    </p>
    <div wire:init="loadEvents">
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
                    <tr wire:key="{{$dayevent['id']}}">
                        <td>{{date('D d M',strtotime($thisdate))}}</td><td>{{$dayevent['time']}}</td><td>{{$dayevent['name']}}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="3">
                    @if ($loaded)
                        No events have been added to the calendar yet.
                    @else
                        Loading events from Google Calendar
                    @endif
                    </td></tr>
            @endforelse
        </table>
</div>