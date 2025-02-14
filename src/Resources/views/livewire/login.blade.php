<div>
    <h3>Member login</h3>
    <div data-mdb-input-init class="form-outline mb-4">
        <form wire:submit="sendsms">
            @csrf
            <x-honey />
            <label class="form-label">Please enter your cellphone number</label>
            <input wire:model.live.debounce.20ms="phone" id="phone" name="phone" class="form-control my-2" placeholder="Cellphone number" />
            @error('phone') 
                <small class="muted text-danger">{{ $message }}</small> 
            @enderror
            @if ((!$block_submit) and (!$pin))
                <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="submit">Send verification SMS to this number</button><br>
            @endif 
            @if($feedback)
                {!!$feedback!!}<br>
            @endif
            @if ($showform) 
                <input wire:model.live="firstname" id="firstname" name="firstname" class="form-control my-2" placeholder="First name"/>
                <input wire:model.live="surname" id="surname" name="surname" class="form-control my-2" placeholder="Surname"/>
            @endif
            @if ($pin)
                <label class="form-label">Enter the PIN you received by SMS</label>
                <input wire:model="userpin" id="userpin" name="userpin" class="form-control my-2" placeholder="PIN"/>
                <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="button" wire:click="sendpin">Submit PIN</button>
            @endif
        </form>
    </div>
</div>