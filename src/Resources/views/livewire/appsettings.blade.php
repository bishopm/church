<div>
    <div data-mdb-input-init class="form-outline mb-4">
        @foreach ($settings as $key=>$setting)
            <p><input type="checkbox" wire:change="updateSettings" wire:model.live="settings.{{$key}}"/> {{$key}}</p>
        @endforeach
    </div>
</div>