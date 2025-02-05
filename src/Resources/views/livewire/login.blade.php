<div>
    <h3>Member login</h3>
    <div data-mdb-input-init class="form-outline mb-4">
        <form wire:submit="send">
            @csrf
            <label class="form-label">Enter your cellphone number</label>
            <input wire:model="phone" class="form-control my-2" placeholder="Cellphone number" />
            {{$phone}}
            <x-honey />
            <button class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" type="submit">Send verification SMS to this number</button>
        </form>
        Message: {{$message}}
    </div>
</div>