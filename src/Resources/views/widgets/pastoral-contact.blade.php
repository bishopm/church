<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold">Recent pastoral contact</div>
        @forelse ($pastoraldata['notes'] as $note)
            @if ($note->pastoralnotable_type=='household')
                <a title="{{$note->details}}" href="{{URL::route('filament.admin.people.resources.households.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->addressee}}</a> ({{$note->pastor->individual->fullname}})<br>
            @elseif ($note->pastoralnotable_type=='individual')
                <a title="{{$note->details}}" href="{{URL::route('filament.admin.people.resources.households.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->firstname}} {{$note->pastoralnotable->surname}}</a> ({{$note->pastor->individual->fullname}})<br>    
            @endif
        @empty
            <div>No pastoral notes have been added</div>
        @endforelse

    </x-filament::section>
</x-filament-widgets::widget>
