<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($widgetdata['tasks']))
            <div class="text-lg font-bold">Tasks</div>
            @foreach ($widgetdata['tasks'] as $task)
                <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$task->id)}}">{{$task->description}}</a><br>
            @endforeach
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
