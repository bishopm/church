<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($pastoraldata['notes']))
            <div class="text-lg font-bold">Recent pastoral contact</div>
            @foreach ($pastoraldata['notes'] as $note)
                @if ($note->pastoralnotable_type=='household')
                    <a href="{{URL::route('filament.admin.people.resources.households.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->addressee}}</a> ({{$note->pastor->individual->fullname}})<br>
                @elseif ($note->pastoralnotable_type=='individual')
                    <a href="{{URL::route('filament.admin.people.resources.households.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->firstname}} {{$note->pastoralnotable->surname}}</a> ({{$note->pastor->individual->fullname}})<br>    
                @endif
            @endforeach
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
