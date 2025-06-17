<div>
    <h3 class="text-center">
        {{setting('general.church_abbreviation')}} Diary <a class="btn btn-secondary" wire:click="toggleStatus">{{$headings[$status]}}</a>
    </h3>
    <div>
        <table class="table table-compact">
            @forelse ($events[$status] as $event)
                <tr wire:key="{{$event['id']}}">
                    <td>
                        {{date('D d M',strtotime($event->diarydatetime))}}
                    </td>
                    <td>
                        {{date('H:i',strtotime($event->diarydatetime))}}
                    </td>
                    <td>
                        @if (isset($event->diarisable->tenant))
                            {{$event->details}}
                        @elseif (isset($event->diarisable->event))
                            {{$event->diarisable->event}}
                        @elseif (isset($event->diarisable->course))
                            {{$event->diarisable->course}}
                        @elseif (isset($event->diarisable->groupname))
                            {{$event->diarisable->groupname}}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        @if ($loaded)
                            No events have been added to the calendar yet.
                        @endif
                    </td>
                </tr>
            @endforelse
        </table>
    </div>
</div>