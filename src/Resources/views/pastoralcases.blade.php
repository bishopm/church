<x-filament-panels::page>
    <table>
        <tr>
            <td></td><td><b>Name</b></td></td><td><b>Pastor</b></td><td><b>Last contact</b></td><td><b>Notes</b></td>
        </tr>
        @foreach ($data as $cases)
            @foreach ($cases as $id=>$case)
                <tr>
                    <td>
                    @if ($case['type']=="individual")
                        <a href="{{route('filament.admin.people.resources.individuals.edit',['record'=>$id])}}"><x-heroicon-o-user width="18"/></a>
                    @else
                        <a href="{{route('filament.admin.people.resources.individuals.edit',['record'=>$id])}}"><x-heroicon-o-user-group  width="18"/></a>
                    @endif
                    </td>
                    <td><a href="{{route('filament.admin.people.resources.individuals.edit',['record'=>$id])}}">{{$case['name']}}</a></td>
                    <td>{{$case['pastor']}}</td>
                    <td>{{$case['contact']}}</td>
                    <td>
                        @if ($case['notes']>0)
                            {{$case['notes']}}
                        @endif    
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>
</x-filament-panels::page>
