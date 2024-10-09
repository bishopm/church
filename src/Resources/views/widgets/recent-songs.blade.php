<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold">Recently added songs</div>
        @foreach ($widgetdata['songs'] as $song)
            <a href="{{URL::route('filament.admin.worship.resources.songs.edit',$song->id)}}">{{$song->title}}</a><br>
        @endforeach
    </x-filament::section>
</x-filament-widgets::widget>
