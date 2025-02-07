<x-church::layouts.app pageName="Groups">
    <h4>{{$indiv->fullname}}</h4>
    These are the details we have for you on our system. Please let us know if they need to be updated
    <table class="table mt-3">
        <tr><th>Cellphone</th><td>{{$indiv->cellphone}}</td></tr>
        <tr><th>Email</th><td>{{$indiv->email}}</td></tr>
        <tr><th>Birthday</th>
            <td>
                @if ($indiv->birthdate)
                    {{date('j F',strtotime($indiv->birthdate))}}
                @endif
            </td>
        </tr>
        <tr><th>Home Address</th><td>{{$indiv->household->address1}}<br>{{$indiv->household->address2}}<br>{{$indiv->household->address3}}</td></tr>
    </table>
</x-church::layout>                
