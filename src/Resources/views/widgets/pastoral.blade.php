<x-filament-widgets::widget>
    <div x-data="{ tab: 'tab1' }">
        <x-filament::tabs label="Content tabs">
            <x-filament::tabs.item @click="tab = 'tab1'" :alpine-active="'tab === \'tab1\''">
                Birthdays
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">
                New members
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab3'" :alpine-active="'tab === \'tab3\''">
                Pastoral contact
            </x-filament::tabs.item>
        </x-filament::tabs>
        <div x-show="tab === 'tab1'">
            <x-filament::section>
                @if (count($pastoraldata['today']))
                    Today's birthdays: 
                    @foreach ($pastoraldata['today'] as $tb)
                        @if ($loop->last)
                            {{$tb->firstname}} {{$tb->surname}}.
                        @else
                            {{$tb->firstname}} {{$tb->surname}}, 
                        @endif
                    @endforeach
                    <br><br>
                @endif
                {!!$pastoraldata['msg']!!}
            </x-filament::section>
        </div>
        <div x-show="tab === 'tab2'">
            <x-filament::section>
                @if (count($pastoraldata['individuals']))
                    <table class="table table-condensed">
                        <tr><th>New members</th><th colspan="2" >Welcome message</th></tr>
                        @foreach ($pastoraldata['individuals'] as $indiv)
                            <tr><td><b><a href="{{URL::route('filament.admin.people.resources.individuals.edit',$indiv->id)}}">{{$indiv->firstname}} {{$indiv->surname}}</a></b></td><td>&nbsp;&nbsp;</td>
                            @if ($indiv->welcome_email==0)
                                @if ($indiv->email<>'')
                                    <td>Email</td>
                                @else 
                                    <td class="text-gray-500">Email</td>
                                @endif
                                @if ($indiv->cellphone<>'')
                                    <td><a target="_blank" href="https://web.whatsapp.com/send?phone=27{{substr($indiv->cellphone,1,9)}}&text=Hi {{$indiv->firstname}}, {{($pastoraldata['whatsapp'])}}">WhatsApp</a></td>
                                @else 
                                    <td class="text-gray-500">WhatsApp</td>
                                @endif
                            @else
                                <td></td><td></td>
                            @endif
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <b><a href="{{URL::route('reports.removenames')}}">Names to be removed</a></b><br>
                @endif
                @if (count($pastoraldata['duplicates']))
                    <br>
                    <b>Please check for potential duplicates</b><br>
                        @foreach($pastoraldata['duplicates'] as $duplicate)
                            {{$duplicate->firstname}} {{$duplicate->surname}}@if (!$loop->last), @else. @endif
                        @endforeach
                    </table>
                @endif
            </x-filament::section>
        </div>
        <div x-show="tab === 'tab3'">
            <x-filament::section>
                @forelse ($pastoraldata['notes'] as $note)
                    @if ($note->pastoralnotable_type=='household')
                        <a title="{{$note->details}}" href="{{URL::route('filament.admin.people.resources.households.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->addressee}}</a> ({{$note->pastor->individual->fullname}})<br>
                    @elseif ($note->pastoralnotable_type=='individual')
                        <a title="{{$note->details}}" href="{{URL::route('filament.admin.people.resources.individuals.edit',$note->pastoralnotable->id)}}">{{$note->pastoralnotable->firstname}} {{$note->pastoralnotable->surname}}</a> ({{$note->pastor->individual->fullname}})<br>    
                    @endif
                @empty
                    <div>No pastoral notes have been added</div>
                @endforelse
            </x-filament::section>
        </div>
    </div>
</x-filament-widgets::widget>
