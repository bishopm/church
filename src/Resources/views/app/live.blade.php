<x-church::layouts.app pageName="Live service">
    @if ($service)
        <div class="bg-black p-2 text-white">
            <h3>Upcoming service</h3>
                @if (($service->servicedate >= date('Y-m-d')) and ($service->video))
                    <div class="ratio ratio-16x9">
                        <iframe src='https://youtube.com/embed/{{$service->video}}?autoplay=1' frameborder='0'></iframe>
                    </div>
                @else
                    <div>Live stream starts in {{ $floor }} ({{date('j M Y',strtotime($service->servicedate))}} {{$service->servicetime}})
                @endif
            </div>
            <div class="py-2">
                <h3 class="text-center">{{setting('general.church_abbreviation')}} Live</h3>
                @livewire('live', [
                    'id' => $member['id'],
                    'service'=> $service
                ])
            </div>
        </div>
    @endif
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher("{{setting('services.pusher_key')}}", {
        cluster: "{{setting('services.pusher_app_cluster')}}"
        });

        var channel = pusher.subscribe('church-messages');
        channel.bind('Bishopm\\Church\\Events\\NewLiveMessage', function() {
            Livewire.dispatchTo('live','updateMessages');
        })
    </script>
</x-church::layouts.app>