<x-filament-panels::page>
    <div x-data="{ tab: 'projects' }">
        <x-filament::tabs label="Task status">
            @foreach ($statuses as $key=>$status)
                @if (isset($tasks[$key]))
                    <x-filament::tabs.item @click="tab = '{{$key}}'" :alpine-active="'tab === \'{{$key}}\''">
                        {{strtoupper($status)}}
                    </x-filament::tabs.item>
                @endif
            @endforeach
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
