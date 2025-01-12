<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($memberdata['individuals']))
            <div class="text-lg font-bold">New members</div>
            <table class="table table-condensed">
                @foreach ($memberdata['individuals'] as $indiv)
                    <tr><td><b><a href="{{URL::route('filament.admin.people.resources.individuals.edit',$indiv->id)}}">{{$indiv->firstname}} {{$indiv->surname}}</a></b></td><td>&nbsp;&nbsp;</td></td><td>Welcome email / WhatsApp</td></tr>
                @endforeach
            </table>
            <br>
            <b><a href="{{URL::route('reports.removenames')}}">Names to be removed</a></b>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
