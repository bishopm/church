<x-filament-widgets::widget>
    <x-filament::section>
        <span class="text-lg font-bold">Tasks {{ $this->addAction }}</span>
        <x-filament-actions::modals />
        @forelse ($tasks as $key=>$task)
            <div wire:key="{{ $task['id'] }}">
                <input type="checkbox" class="form-control" wire:click="done({{$task['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$task['id'])}}">{{$task['description']}}</a> (Due: {{ \Carbon\Carbon::parse($task['duedate'])->diffForHumans() }} )<br>
            </div>
        @empty
            <div>All tasks have been completed</div>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>
