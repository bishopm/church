<h4>Next week's roster: {{$group}}</h4>

@if (!$message)
    @foreach ($rosters as $key=>$rosteritems)
        <h4>{{$key}}</h4>
        @foreach ($rosteritems as $ri)
            <p>
                {{$ri->rosterdate}} 
                @foreach ($ri->individuals as $indiv)
                    @if ($loop->last)
                        {{$indiv->firstname}} {{$indiv->surname}}.
                    @else 
                        {{$indiv->firstname}} {{$indiv->surname}}, 
                    @endif
                @endforeach
            </p>
        @endforeach
    @endforeach
@else 
    {{$message}}
@endif