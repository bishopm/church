<x-church::website.applayout pageName="Pastoral care">
    <h3>Pastoral care</h3>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="mycases-tab" data-bs-toggle="tab" data-bs-target="#mycases" type="button" role="tab">{{$pastor->firstname}}'s pastoral cases</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="othercases-tab" data-bs-toggle="tab" data-bs-target="#othercases" type="button" role="tab">All {{setting('general.church_abbreviation')}} pastoral cases</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="mycases" role="tabpanel">
            <b>Individuals</b>
            @forelse ($my_cases->individuals->sortBy('firstname') as $indiv)
                <p><a href="{{route('app.pastoralcase',['id'=>$indiv->id, 'type'=>'individual'])}}">{{$indiv->fullname}}</a></p>
            @empty
                Empty
            @endforelse
            <b>Households</b>
            @forelse ($my_cases->households->sortBy('sortsurname') as $fam)
                <p><a href="{{route('app.pastoralcase',['id'=>$fam->id, 'type'=>'household'])}}">{{$fam->addressee}}</a></p>
            @empty
                Empty
            @endforelse
        </div>
        <div class="tab-pane" id="othercases" role="tabpanel">
        <b>Individuals</b>
            @forelse ($all_cases['individuals'] as $indiv)
                @if ($indiv->pivot->active)
                    <p><a href="{{route('app.pastoralcase',['id'=>$indiv->id, 'type'=>'individual'])}}">{{$indiv->fullname}}</p>
                @endif
            @empty
                Empty
            @endforelse
            <b>Households</b>
            @forelse ($all_cases['households'] as $fam)
                <p><a href="{{route('app.pastoralcase',['id'=>$fam->id, 'type'=>'household'])}}">{{$fam->addressee}}</a></p>
            @empty
                Empty
            @endforelse
        </div>
    </div>
</x-church::layout>                
