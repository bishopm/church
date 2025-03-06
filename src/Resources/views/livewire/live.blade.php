<div>
    @if ($status == "mounted")
        @if ($member['id']==1218)
            <button class="form-control form-control-sm text-center bg-danger text-white mb-2" wire:click="login">Click to join our live service</button>
        @endif
        <div class="card p-3">
            <h5>Reading: <small><a href="http://biblegateway.com/passage/?search={{urlencode($service->reading)}}&version=GNT">{{$service->reading}}</a></small></h5>
            <h5>Preacher: <small>{{$service->person->fullname}}</small></h5>
            <h5>Songs</h5>
            <ul class="list-unstyled">
                @forelse ($service->setitems as $song)
                    @if (isset($song->setitemable->title))
                        <li><a href="{{url('/')}}/songs/{{$song->setitemable_id}}">{{$song->setitemable->title}}</a></li>
                    @endif
                @empty
                    No songs have been added yet
                @endforelse
            </ul>
        </div>
    @elseif ($status == "online")
        <ul style="display: flex; justify-content: center;" class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-chat-tab" data-bs-toggle="pill" data-bs-target="#pills-chat" type="button" role="tab" aria-controls="pills-chat" aria-selected="false">
                    <i class="bi bi-chat-dots"> </i>Chat
                </button>
            </li>                
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-congregation-tab" data-bs-toggle="pill" data-bs-target="#pills-congregation" type="button" role="tab" aria-controls="pills-congregation" aria-selected="false">
                    <i class="bi bi-people"> </i>Who's here? @if (count($members)) <span class="badge bg-dark ml-1 px-1">{{count($members)}}</span> @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="false">
                    <i class="bi bi-mic"> </i>Service details
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">
                @if (count($messages))
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4">
                            <div class="card" id="chat1" style="border-radius: 15px;">
                                <div class="card-body">
                                    @foreach ($messages as $message)    
                                        @if (is_null($message['individual_id']))
                                            <div class="d-flex flex-row justify-content-start mb-4" wire:key="{{$message['id']}}">
                                                <img src="https://ui-avatars.com/api/?rounded=true&name={{setting('general.church_abbreviation')}}" alt="avatar 1" style="width: 45px; height: 100%;">
                                                <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                                                    <p class="small mb-0">{{$message['message']}} <small class="text-secondary">{{setting('general.church_abbreviation')}} {{date('H:i',strtotime($message['messagetime']))}}</small></p>
                                                </div>
                                            </div>    
                                        @else
                                            <div class="d-flex flex-row justify-content-end mb-4" wire:key="{{$message['id']}}">
                                                <div class="p-3 me-3 border bg-body-tertiary" style="border-radius: 15px;">
                                                    <p class="small mb-0">{{$message['message']}} <small class="text-secondary">{{$message['individual']['firstname']}} {{$message['individual']['surname']}} {{date('H:i',strtotime($message['messagetime']))}}</small></p>
                                                </div>
                                                <img src="https://ui-avatars.com/api/?rounded=true&name={{$message['individual']['firstname']}}+{{$message['individual']['surname']}}" alt="avatar 1" style="width: 45px; height: 100%;">
                                            </div>                               
                                        @endif
                                    @endforeach
                                    <div data-mdb-input-init class="form-outline">
                                        <div class="input-group my-2">
                                            <textarea wire:model.lazy="usermessage" class="form-control" placeholder="Send a message" rows="3">{{$usermessage}}</textarea>
                                            <button class="btn btn-dark bi bi-send" wire:click="sendMessage"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="form-control text-center bg-dark text-white mb-2" wire:click="logout">Logout</button>
                    </div>
                @else
                    Setting up chat box ...
                @endif
            </div>
            <div class="tab-pane fade show" id="pills-congregation" role="tabpanel" aria-labelledby="pills-congregation-tab">
                @foreach ($members as $member)
                    <span wire:key="{{$member['id']}}">
                        <small class="badge">{{$member['firstname']}} {{$member['surname']}}</small>
                    </span>
                @endforeach
            </div>
            <div class="tab-pane fade show" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
                <div class="card p-3">
                    <h5>Reading: <small><a href="http://biblegateway.com/passage/?search={{urlencode($service->reading)}}&version=GNT">{{$service->reading}}</a></small></h5>
                    <h5>Preacher: <small>{{$service->person->fullname}}</small></h5>
                    <h5>Songs</h5>
                    <ul class="list-unstyled">
                        @forelse ($service->setitems as $song)
                            @if (isset($song->setitemable->title))
                                <li><a href="{{url('/')}}/songs/{{$song->setitemable_id}}">{{$song->setitemable->title}}</a></li>
                            @endif
                        @empty
                            No songs have been added yet
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>