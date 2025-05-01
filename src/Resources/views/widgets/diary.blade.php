<x-filament-widgets::widget>
    <div x-data="{ tab: 'tab1' }">
        <x-filament::tabs label="Content tabs">
            <x-filament::tabs.item @click="tab = 'tab1'" :alpine-active="'tab === \'tab1\''">
                Upcoming events
            </x-filament::tabs.item>
            <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">
                Upcoming services
            </x-filament::tabs.item>
        </x-filament::tabs>
        <div x-show="tab === 'tab1'">
            <x-filament::section>
                <table>
                    <tr><td><b>Date</b></td><td style="padding-left:10px; padding-right:10px;"><b>Time</b></td><td><b>Event</b></td></tr>
                    @foreach ($widgetdata['events'] as $event)
                        <tr><td>{{$event['date']}}</td><td style="padding-left:10px; padding-right:10px;">{{$event['time']}}</td><td>{{$event['name']}}</td></tr>
                    @endforeach
                </table>
            </x-filament::section>
        </div>
        <div x-show="tab === 'tab2'">
            <x-filament::section>
                @if (count($widgetdata['plans']))
                    <table class="table">
                        <tr><td><b>Date</b></td><td><b>Time</b></td><td><b>Preacher</b></td></tr>
                        @foreach ($widgetdata['plans'] as $plan)
                            <tr><td>{{$plan->servicedate}}</td><td style="padding-left:5px; padding-right:5px;">{{$widgetdata['services'][$plan->service_id]}}</td><td><b>{{$plan->person->firstname ?? '' }} {{$plan->person->surname ?? '' }}</b></td></tr>
                        @endforeach
                    </table>
                @else
                    No upcoming services for this society
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-widgets::widget>
