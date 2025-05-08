<div>
    <div data-mdb-input-init class="form-outline mb-4">
        @foreach ($settings as $category=>$fields)
            @if ($category <> 'Admin')
                <h3>{{$category}}</h3>
                @foreach ($fields as $key=>$setting)
                    <p><input type="checkbox" wire:change="updateSettings" wire:model.live="settings.{{$category}}.{{$key}}"/> {{$key}}</p>
                @endforeach
            @endif
        @endforeach
    </div>
</div>