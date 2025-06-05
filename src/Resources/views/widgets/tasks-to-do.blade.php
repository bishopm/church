<x-filament-widgets::widget>
    <div x-data="{ tab: 'tab1' }">
        <x-filament::tabs label="Content tabs">
            <a href="{{route('filament.admin.admin.resources.tasks.taskboard')}}"><x-heroicon-o-adjustments-horizontal class="pt-2" width="18"/></a>
            <x-filament::tabs.item @click="tab = 'tab1'" :alpine-active="'tab === \'tab1\''">
                To do <span class="rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{$tcount}}</span>
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">
                Doing <span class="rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{$ucount}}</span>
            </x-filament::tabs.item>        
            <x-filament::tabs.item @click="tab = 'tab3'" :alpine-active="'tab === \'tab3\''">
                Some day <span class="rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{$scount}}</span>
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab4'" :alpine-active="'tab === \'tab4\''">
                Done <span class="rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{$dcount}}</span>
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab5'" :alpine-active="'tab === \'tab5\''">
                Projects <span class="rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{$pcount}}</span>
            </x-filament::tabs.item>
            <div class="mt-1">{{ $this->addAction }}</div>
            <x-filament-actions::modals />
        </x-filament::tabs>
        <div>
            <div x-show="tab === 'tab1'" class="mt-3">
                @forelse ($tasks as $key=>$task)
                    <div wire:key="{{ $task['id'] }}">
                        <input type="checkbox" class="form-control" wire:click="done({{$task['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$task['id'])}}">{{$task['description']}}</a> (Due: {{ \Carbon\Carbon::parse($task['duedate'])->diffForHumans() }} )<br>
                    </div>
                @empty
                    <div>No outstanding tasks</div>
                @endforelse
            </div>
            <div x-show="tab === 'tab2'" class="mt-3">
                @forelse ($doings as $key=>$doing)
                    <div wire:key="{{ $doing['id'] }}">
                        <input type="checkbox" class="form-control" wire:click="done({{$doing['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$doing['id'])}}">{{$doing['description']}}</a> (Due: {{ \Carbon\Carbon::parse($doing['duedate'])->diffForHumans() }} )<br>
                    </div>
                @empty
                    <div>No underway tasks</div>
                @endforelse
            </div>
            <div x-show="tab === 'tab3'" class="mt-3">
                @forelse ($somedays as $key=>$someday)
                    <div wire:key="{{ $someday['id'] }}">
                        <input type="checkbox" class="form-control" wire:click="done({{$someday['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$someday['id'])}}">{{$someday['description']}}</a> (Due: {{ \Carbon\Carbon::parse($someday['duedate'])->diffForHumans() }} )<br>
                    </div>
                @empty
                    <div>No some day tasks </div>
                @endforelse
            </div>
            <div x-show="tab === 'tab4'" class="mt-3">
                @forelse ($dones as $key=>$done)
                    <div wire:key="{{ $done['id'] }}">
                        <input type="checkbox" checked class="form-control" wire:click="undone({{$done['id']}})"> <a href="{{URL::route('filament.admin.admin.resources.tasks.edit',$done['id'])}}">{{$done['description']}}</a> (Completed: {{ \Carbon\Carbon::parse($done['updated_at'])->diffForHumans() }} )<br>
                    </div>
                @empty
                    <div>No completed tasks </div>
                @endforelse
            </div>
            <div x-show="tab === 'tab5'" class="mt-3">
                <h4>Projects with outstanding tasks</h4>
                @foreach ($projects as $project=>$tasks)
                    <a href="{{route('filament.admin.admin.resources.tasks.index')}}?tableSearch={{$project}}">{{ucfirst($project)}}</a> <span class="text-white rounded-md" style="background-color: black; margin-left:0.2rem; padding-left:0.5rem;padding-right:0.5rem;padding-top:0.25rem;padding-bottom:0.25rem;">{{count($tasks)}}</span>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
