<div>
    <div class="text-sm flex justify-between mt-2">
        <span>Average Rating: </span>
        <span class="text-lg font-extrabold rounded-md bg-blue-600 px-2">{{ $avgRating }}</span>
    </div>

    <div class="flex items-center mt-0">
        <div class="flex items-center ml-2">
            <span class="text-sm">Your rating:</span>
            @for ($i=1;$i<=5;$i++)
                @if ($i>$rating)
                    <i class="bi bi-star" wire:click="setRating({{$i}})"></i>
                @else
                    <i style="color:darkblue;" class="bi bi-star-fill" wire:click="setRating({{$i}})"></i>
                @endif
            @endfor
        </div>
    </div>
</div>