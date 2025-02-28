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
                <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-chat-tab" data-bs-toggle="pill" data-bs-target="#pills-chat" type="button" role="tab" aria-controls="pills-chat" aria-selected="false">
                            <i class="bi bi-chat-dots"> </i>Chat box
                        </button>
                    </li>                
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="false">
                            <i class="bi bi-people"> </i>Service details
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">
                        <textarea class="form-control" rows="12"></textarea>
                    </div>        
                    <div class="tab-pane fade show" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
                        <div class="card p-3">
                            <h5>Reading: <small><a href="http://biblegateway.com/passage/?search={{urlencode($service->reading)}}&version=GNT">{{$service->reading}}</a></small></h5>
                            <h5>Preacher: <small>{{$service->person->fullname}}</small></h5>
                            <h5>Songs</h5>
                            <ul class="list-unstyled">
                                @forelse ($service->setitems as $song)
                                    <li><a href="{{url('/')}}/songs/{{$song->setitemable_id}}">{{$song->setitemable->title}}</a></li>
                                @empty
                                    No songs have been added yet
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
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
        channel.bind('Bishopm\\Church\\Events\\NewLiveUser', function(data) {
            console.log(data['message']);
        })
    </script>
</x-church::layouts.app>