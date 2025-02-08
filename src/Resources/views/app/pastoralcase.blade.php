<x-church::layouts.app pageName="Pastoral care">
    @if ($type=="individual")
        <h3>{{$case->fullname}}</h3>
        <p class="small">{{substr($case->cellphone,0,3)}} {{substr($case->cellphone,3,4)}} {{substr($case->cellphone,7,3)}}</p>
        @foreach ($case->pastors as $pastorable)
            @if($pastorable->pivot->pastor_id==$pastor_id)
                <p>{{$pastorable->pivot->details}}</p>
            @endif
        @endforeach
    @else
        <h3>{{$case->addressee}}</h3>
        @foreach ($case->pastors as $pastorable)
            @if($pastorable->pivot->pastor_id==$pastor_id)
                <p>{{$pastorable->pivot->details}}</p>
            @endif
        @endforeach
    @endif
    @if ($mostrecent)
        <p><small>Most recent contact: {{ \Carbon\Carbon::parse($mostrecent->pastoraldate)->diffForHumans() }}</small></p>
    @endif
    @livewire('pastoralnote',['pastoralnotable_id'=>$case->id,'pastoralnotable_type'=>$type, 'pastor_id'=>$pastor_id])
    @if ($detail==1)
        @if (count($case->pastoralnotes))
            <table class="table table-sm table-borderless">
                @foreach ($case->pastoralnotes->sortBy('pastoraldate') as $note)
                    <tr><td>{{$note->pastoraldate}}</td><td>{{$note->details}}</td><td>{{$note->pastor->individual->firstname}}</td></tr>
                @endforeach
            </table>
        @else
            No pastoral notes have been added yet
        @endif
    @else
        Pastoral notes: {{count($case->pastoralnotes)}}
    @endif
    <script>
        function addDetails(e) {
            document.getElementById("details").value = e.target.value + ' ' + '{{$name}}'
        }
    </script>
</x-church::layout>                
