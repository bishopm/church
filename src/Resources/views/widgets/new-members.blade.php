<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold">New members</div>
        @foreach ($memberdata['individuals'] as $indiv)
            <a href="{{URL::route('filament.admin.people.resources.individuals.edit',$indiv->id)}}">{{$indiv->firstname}} {{$indiv->surname}}</a><br>
        @endforeach
    </x-filament::section>
</x-filament-widgets::widget>
