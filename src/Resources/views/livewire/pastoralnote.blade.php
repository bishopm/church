<div>
    @if ($pastoralnotable_type=="individual")
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
    <div class="text-center">
        <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#noteModal">
            Add a pastoral note
        </button>
    </div>
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add a pastoral note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="pastor_id" wire:model="pastor_id">
                        <input type="hidden" name="pastoralnotable_id" wire:model="pastoralnotable_id">
                        <input type="hidden" name="pastoralnotable_type" wire:model="pastoralnotable_type"">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Date</span>
                            </div>
                            <input type="text" class="form-control" id="pastoraldate" name="pastoraldate" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Care</span>
                            </div>
                            <select class="form-control" id="care" name="care"  onchange="addDetails(event)">
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
                                <span class="input-group-text" id="basic-addon1">Details</span>
                            </div>
                            <input type="text" class="form-control" id="details" wire:model="details">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="save">Add note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
</div>