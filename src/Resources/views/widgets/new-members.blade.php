<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($memberdata['individuals']))
            <table class="table table-condensed">
                <tr><th>New members</th><th colspan="2" >Welcome message</th></tr>
                @foreach ($memberdata['individuals'] as $indiv)
                    <tr><td><b><a href="{{URL::route('filament.admin.people.resources.individuals.edit',$indiv->id)}}">{{$indiv->firstname}} {{$indiv->surname}}</a></b></td><td>&nbsp;&nbsp;</td>
                    @if ($indiv->welcome_email==0)
                        @if ($indiv->email<>'')
                            <td>Email</td>
                        @else 
                            <td class="text-gray-500">Email</td>
                        @endif
                        @if ($indiv->cellphone<>'')
                            <td><a target="_blank" href="https://web.whatsapp.com/send?phone=27{{substr($indiv->cellphone,1,9)}}&text=Hi {{$indiv->firstname}}, {{($memberdata['whatsapp'])}}">WhatsApp</a></td>
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
            <b><a href="{{URL::route('reports.removenames')}}">Names to be removed</a></b>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
