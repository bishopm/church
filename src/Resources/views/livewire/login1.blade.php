<div>
    <h3>Member login</h3>
    @if($status<>"pinsent")
        <div data-mdb-input-init class="form-outline mb-4">
            @if ($status<>"phoneok")
                <form wire:submit="send">
                @csrf
                <label class="form-label">Enter your cellphone number</label>
            @else
                <h4>Phone: {{$phone}}</h4>
            @endif
            <input 
                @if($status=="phoneok")type="hidden"@endif
                wire:model="phone" 
                class="form-control my-2" 
                placeholder="Cellphone number" 
            />
            <x-honey />
            <span class="text-warning">{!!$error!!}</span><span class="text-success">{{$addmessage}}</span><span class="text-success">{{$message}}</span>
            @if($addmessage)
                <input wire:model="firstname" class="form-control my-2" placeholder="First name" />
                <input wire:model="surname" class="form-control my-2" placeholder="Surname" />
            @endif
            <div>{{$pin}}</div>
            @if (($status<>"toomany") and ($status<>"addind"))
                <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="submit">Send verification SMS to this number</button>
                </div>
                </form>
            @elseif ($status=="addind")
                <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="button" wire:click="addindiv">OK</button>
                </div>
            @endif
        </div>
    @endif
    @if($status=="pinsent")
        <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label">Enter PIN number</label>
            <input wire:model="userpin" class="form-control" placeholder="Please enter the code we sent you" />
            <div>{{$pin}}</div>
        </div>
        <div class="text-center pt-1 mb-5 pb-1">
            <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="button" wire:click="sendpin">
                Submit PIN
            </button>
        </div>
    @endif
</div>