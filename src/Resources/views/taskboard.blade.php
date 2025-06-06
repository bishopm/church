<x-filament-panels::page>
    <div x-data="{ tab: 'projects' }">
        <x-filament::tabs label="Task status" contained="false">
            @if (isset($tasks['projects']))
                <x-filament::tabs.item x-on:click="tab = 'projects'" :alpine-active="'tab === \'projects\''">
                    Projects
                </x-filament::tabs.item>
            @endif
            @if (isset($tasks['todo']))
                <x-filament::tabs.item x-on:click="tab = 'todo'" :alpine-active="'tab === \'todo\''">
                    To do
                </x-filament::tabs.item>
            @endif
            @if (isset($tasks['doing']))
                <x-filament::tabs.item x-on:click="tab = 'doing'" :alpine-active="'tab === \'doing\''">
                    Underway
                </x-filament::tabs.item>
            @endif
            @if (isset($tasks['someday']))
                <x-filament::tabs.item x-on:click="tab = 'someday'" :alpine-active="'tab === \'someday\''">
                    Some day
                </x-filament::tabs.item>
            @endif
            @if (isset($tasks['done']))
                <x-filament::tabs.item x-on:click="tab = 'done'" :alpine-active="'tab === \'done\''">
                    Completed
                </x-filament::tabs.item>
            @endif
        </x-filament::tabs>
        @foreach ($statuses as $key=>$status)
            <div x-show="tab === '{{$key}}'">
                <x-filament::section>
                    <table>
                    @if ($status=="Projects")
                        @foreach ($tasks['projects'] as $project=>$projectasks)
                            <tr style="background-color:black;color:white;"><th colspan="4"><b>{{strtoupper($project)}}</b></th></tr>
                            <tr style="background-color:grey;color:white;"><th>Description</th><th>Status</th><th>Assigned to</th><th></th></tr>
                            @foreach ($projectasks as $task)
                                <tr>
                                    <td class="px-2" wire:key="{{ $task->id }}">
                                        <input type="checkbox" class="form-control" wire:click="done({{$task->id}})"> {{$task->description}}
                                    </td>
                                    <td>{{$statuses[$task->status]}}</td>
                                    <td class="px-2">{{$task->individual->name}}</td>
                                    <td>{{  ($this->editAction)(['task' => $task->id]) }}</td>
                                </tr>
                            @endforeach    
                        @endforeach
                    @elseif (isset($tasks[$key]))
                        <tr style="background-color:grey;color:white;"><th>Description</th><th>Assigned to</th><th></th></tr>
                        @foreach ($tasks[$key] as $task)
                            <tr>
                                <td class="px-2" wire:key="{{ $task->id }}">
                                    <input type="checkbox" class="form-control" wire:click="done({{$task->id}})"> {{$task->description}}
                                </td>
                                <td>{{$task->individual->name}}</td>
                                <td>{{  ($this->editAction)(['task' => $task->id]) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </table>
                </x-filament::section>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
