<div>
    <h3>Member login</h3>
    <div data-mdb-input-init class="form-outline mb-4">
        <label class="form-label">Enter your cellphone number</label>
        <input wire:model="phone" type="tel" id="phone" class="form-control" placeholder="Cellphone number" />    
    </div>
    <div class="text-center pt-1 mb-5 pb-1">
        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block fa-lg gradient-custom-2 mb-3" wire:click="save">
            Submit number and then look out for SMS
        </button>
    </div>
</div>