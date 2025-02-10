<div>
    @if ($pastoralnotable_type=="individual")
        <h4>
            <a class="bi bi-arrow-left-square" href="{{route('app.pastoral')}}"></a>
            {{$case->fullname}}
        </h4>
        <p class="small">{{substr($case->cellphone,0,3)}} {{substr($case->cellphone,3,4)}} {{substr($case->cellphone,7,3)}}</p>
        @foreach ($case->pastors as $pastorable)
            <p>
            @if($pastorable->pivot->pastor_id==$pastor->id)
                <p>{{$pastorable->pivot->details}}. </p>
            @endif
            <small>Pastor: {{$pastorable->individual->fullname}}</small></p>
        @endforeach
    @else
        <h4>
            <a class="bi bi-arrow-left-square" href="{{route('app.pastoral')}}"></a>
            {{$case->addressee}}
        </h4>
        @foreach ($case->pastors as $pastorable)
            <p>
            @if($pastorable->pivot->pastor_id==$pastor->id)
                <p>{{$pastorable->pivot->details}} 
            @endif
            <small>Pastor: {{$pastorable->individual->fullname}}</small></p>
        @endforeach
    @endif
    @if ($mostrecent)
        <p><small>Most recent contact: {{ \Carbon\Carbon::parse($mostrecent->pastoraldate)->diffForHumans() }}</small></p>
    @endif
    <div class="text-center">
        @if (!$show)
            <button type="button" class="btn btn-primary my-3" wire:click="showform">
                Add a pastoral note
            </button>
        @endif
    </div>
    @if ($show)
        <form wire:submit="save">
            <div class="modal-body my-2">
                <input type="hidden" name="pastor_id" wire:model="pastor_id">
                <input type="hidden" name="pastoralnotable_id" wire:model="pastoralnotable_id">
                <input type="hidden" name="pastoralnotable_type" wire:model="pastoralnotable_type"">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Date</span>
                    </div>
                    <input type="text" class="form-control" wire:model="pastoraldate" name="pastoraldate">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Care</span>
                    </div>
                    <select class="form-control" wire:change="changeDetails($event.target.value)">
                        <option></option>
                        <option value="Sent flowers to">Flowers</option>
                        <option value="Sent a meal to">Meal</option>
                        <option value="Messaged">Messaged</option>
                        <option value="Phoned">Phoned</option>
                        <option value="Visited">Visited</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon2">Details</span>
                    </div>
                    <input type="text" class="form-control" wire:model="details">
                </div>
            </div>
            <div class="modal-footer my-2">
                <button type="button" class="btn btn-secondary mx-2" wire:click="showform">Close</button>
                <button type="button" class="btn btn-primary" wire:click="save">Add note</button>
            </div>
        </form>
    @endif
    @if ($detail==1)
        @if (count($case->pastoralnotes))
            <table class="table table-sm table-borderless">
                @php
                    $others=0;
                @endphp
                @foreach ($case->pastoralnotes->sortByDesc('pastoraldate') as $note)
                    @if ($pastor->id ==$note->pastor_id)
                        <tr wire:key="{{$note->id}}">
                            <td>{{$note->pastoraldate}}</td>
                            <td>{{$note->details}}</td>
                            <td>{{$note->pastor->individual->firstname}}</td>
                            <td><a href="#" class="bi bi-trash" wire:confirm="Are you sure you want to delete this note?" wire:click="delete({{$note->id}})"></a></td>
                        </tr>
                    @else
                        @php
                            $others=$others+1;
                        @endphp
                    @endif
                @endforeach
                @if ($others)
                    <small>Pastoral notes from other pastors: {{$others}}</small>
                @endif
            </table>
        @else
            <small>No pastoral notes have been added yet</small>
        @endif
    @endif
</div>