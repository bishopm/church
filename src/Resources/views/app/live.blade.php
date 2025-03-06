<x-church::layouts.blank pageName="Live service">
    @if ($service)
        <div class="bg-black p-2 text-white">
            <div class="py-2">
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