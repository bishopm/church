<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-lg font-bold">Upcoming services (as per preaching plan)</div>
        @if (count($widgetdata['plans']))
            <table class="table">
                <tr><th>Date</th><th>Time</th><th>Preacher</th></tr>
                @foreach ($widgetdata['plans'] as $plan)
                    <tr><td>{{$plan->servicedate}}</td><td style="padding-left:5px; padding-right:5px;">{{$widgetdata['services'][$plan->service_id]}}</td><td><b>{{$plan->person->title ?? '' }} {{$plan->person->firstname ?? '' }} {{$plan->person->surname ?? '' }}</b></td></tr>
                @endforeach
            </table>
        @else
            No upcoming services for this society
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
