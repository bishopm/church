<div>
    <h3 class="text-center">
        {{setting('general.church_abbreviation')}} Diary <a class="btn btn-secondary" wire:click="toggleStatus">{{$status}}</a>
    </h3>
    <div wire:init="loadEvents">
        <table class="table table-compact">
            @forelse ($events as $event)
                <tr wire:key="{{$event['id']}}">
                @if ($event['heading'])
                    <th colspan="3" class="text-center">{{date("F",strtotime($event['date']))}}</th></tr>
                @endif
                <td>{{date('D d M',strtotime($event['date']))}}</td><td>{{$event['time']}}</td><td>{{$event['name']}}</td></tr>
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
</div>