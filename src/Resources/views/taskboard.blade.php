<x-filament-panels::page>
    <table class="table">
        @foreach ($tasks as $status=>$items)
            <tr><th colspan="*">{{strtoupper($status)}}</th></tr>
            @foreach ($items as $task)
                <tr><td>{{$task->description}}</td><td>{{$task->individual->name}}</td></tr>
            @endforeach
        @endforeach
    </table>
</x-filament-panels::page>
