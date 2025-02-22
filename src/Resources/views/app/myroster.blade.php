<x-church::layouts.app pageName="People">
    @if(count($servicegroups))
        You are a member of the following service teams:
        <ul>
            @foreach ($servicegroups as $sgroup)
                <li>{{$sgroup->groupname}}</li>
            @endforeach
        </ul>
        <h4>Upcoming roster dates</h4>
        @forelse ($roster as $kk=>$rosterdate)
            <div><b>{{$kk}}&nbsp;</b>{{implode(', ',$rosterdate)}}</div>
        @empty
            You have no upcoming roster duties
        @endforelse
    @else
        <a class="btn btn-secondary" href="{{url('/contact')}}">To join a service team, send us a message</a> 
    @endif
</x-church::layout>                
