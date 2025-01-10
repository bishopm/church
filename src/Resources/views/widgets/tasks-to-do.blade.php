<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($tasks))
            <span class="text-lg font-bold"><a href="#"><x-heroicon-m-plus-circle width="28" style="float:right;"/></a>Tasks</span>
            @foreach ($tasks as $key=>$task)
                <div wire:key="{{ $task['id'] }}">
                    <input type="checkbox" class="form-control" wire:click="done({{$task['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$task['id'])}}">{{$task['description']}}</a> (Due: {{ \Carbon\Carbon::parse($task['duedate'])->diffForHumans() }} )<br>
                </div>
            @endforeach
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
