<div>
    <h3>Member login</h3>
    @if(!$pinsent)
        <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label">Enter your cellphone number</label>
            <input wire:model="phone" class="form-control" placeholder="Cellphone number" />
            <span class="text-warning">{{$error}}</span><span class="text-success">{{$message}}</span>
            <div>{{$pin}}</div>
        </div>
        <div class="text-center pt-1 mb-5 pb-1">
            <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="button" wire:click="send">
                Send verification SMS to this number
            </button>
        </div>
    @endif
    @if($pinsent)
        <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label">Enter PIN number</label>
            <input wire:model="userpin" class="form-control" placeholder="Enter the code we sent you" />
            <div>{{$pin}}</div>
        </div>
        <div class="text-center pt-1 mb-5 pb-1">
            <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="button" wire:click="sendpin">
                Submit PIN
            </button>
        </div>
    @endif
</div>