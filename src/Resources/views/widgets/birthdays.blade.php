<x-filament-widgets::widget>
    <x-filament::section>
        @if (count($memberdata['today']))
            Today's birthdays: 
            @foreach ($memberdata['today'] as $tb)
                @if ($loop->last)
                    {{$tb->firstname}} {{$tb->surname}}.
                @else
                    {{$tb->firstname}} {{$tb->surname}}, 
                @endif
            @endforeach
            <br><br>
        @endif
        {!!$memberdata['msg']!!}
    </x-filament::section>
</x-filament-widgets::widget>
