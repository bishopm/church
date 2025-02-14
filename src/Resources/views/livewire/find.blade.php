<div>
    <div data-mdb-input-init class="form-outline mb-4">
        <input wire:model.live="name" class="form-control my-2" placeholder="Name" />
    </div>
    <table class="table table-condensed">
    @foreach ($names as $name)
        <tr wire:key="{{time().$name['id']}}">
            <td>{{$name['firstname']}} {{$name['surname']}}</td>
            <td><a href="tel:{{$name['cellphone']}}">{{substr($name['cellphone'],0,3)}} {{substr($name['cellphone'],4,3)}} {{substr($name['cellphone'],7)}}</a></td>
            <td>{{$name->household->address1}} {{$name->household->address2}}</td>
        </tr>
    @endforeach
    </table>
</div>