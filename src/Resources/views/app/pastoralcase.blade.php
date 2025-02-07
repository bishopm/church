<x-church::layouts.app pageName="Pastoral care">
    @if ($type=="individual")
        <h3>{{$case->fullname}}</h3>
    @else
        <h3>{{$case->addressee}}</h3>
    @endif
    <p>Most recent contact: {{ \Carbon\Carbon::parse($mostrecent->pastoraldate)->diffForHumans() }}</p>
    @if ($detail==1)
        <table>
            @foreach ($case->pastoralnotes->sortBy('pastoraldate') as $note)
                <tr><td>{{$note->pastoraldate}}</td><td>{{$note->details}}</td><td>{{$note->pastor->individual->firstname}}</td></tr>
            @endforeach
        </table>
    @else
        Pastoral notes: {{count($case->pastoralnotes)}}
    @endif
</x-church::layout>                
