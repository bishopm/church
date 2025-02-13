<x-church::layouts.app pageName="App home">
    @if (count($member))
        <div class="text-center">
            <button class="btn btn-secondary mb-2" id="installbutton" hidden>Install the {{setting('general.church_abbreviation')}} App</button>
        </div>
    @endif
    <div class="modal fade" id="versionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">An update is available</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please click OK to update your app to version {{setting('general.app_version')}}
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="refresh();" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @if ($service) 
        <div class="bg-black p-2 text-white">
            <h3>Upcoming service</h3>
                @if (($service->servicedate == date('Y-m-d')) and ($service->video))
                    <div class="ratio ratio-16x9">
                        <iframe src='https://youtube.com/embed/{{$service->video}}?autoplay=1' frameborder='0' allowfullscreen></iframe>
                    </div>
                @else
                    <div>Live stream starts in {{ $floor }} ({{date('j M Y',strtotime($service->servicedate))}} {{$service->servicetime}})
                @endif
            </div>
            <div class="py-2">
                <h5>Reading: <small><a href="http://biblegateway.com/passage/?search={{urlencode($service->reading)}}&version=GNT">{{$service->reading}}</a></small></h5>
                <h5>Preacher: <small>{{$preacher}}</small></h5>
                @if ($service->series)
                    <h5>Series: <small><a href="{{url('/')}}/sermons/{{date('Y',strtotime($service->series->startingdate))}}/{{$service->series->slug}}">{{$service->series->series}}</a></small></h5>
                @endif
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
    @endif
    @forelse ($content as $item)
        @if (isset($item->published_at))
            <div class="lead pt-3">
                <a href="{{url('/blog') . '/' . date('Y',strtotime($item->published_at)) . '/' . date('m',strtotime($item->published_at)) . '/' . $item->slug}}">{{$item->title}}</a>
            </div>
            <small class="text-muted">{{\Carbon\Carbon::parse($item['published_at'])->diffForHumans()}}</small> 
            @if ($item->image)
                <div><img src="{{url('/storage/' . $item->image)}}" alt="Image" class="img-fluid rounded"></div>
            @endif
            <div>{!! nl2br($item->excerpt) !!}</div>
        @elseif (isset($item->event))
            <div class="lead pt-3">
                Coming up: <a href="{{route('app.event',['id'=>$item->id])}}">{{$item->event}}</a>
                <p>{{$item->description}}</p>
                @if(!empty($item->image))
                    <a href="{{route('app.event',['id'=>$item->id])}}">
                        <img src="{{url('/storage/' . $item->image)}}" alt="Image" class="img-fluid rounded">
                    </a>
                @endif
            </div>
        @elseif (isset($service))
            <div class="lead pt-3">
                @if ($service->series)
                    <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">{{$item->title}}</a>
                @endif
            </div>
            <small class="text-muted">{{\Carbon\Carbon::parse($item['servicedate'])->diffForHumans()}}</small>
            @if ($service)
                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">
                    <img class="card-img-top" src="{{url('/storage/' . $item->series->image)}}" alt="{{$item->series->series}}">
                </a>
            @endif
            <div class="lead pt-3">{{$item->reading}}
                @if ($item->person)
                <small class="text-muted">{{$item->person->fullname}}</small>
                @endif
            </div>
        @elseif ($item->reading)
            @if ($item->series)
                <div class="lead pt-3">
                    <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">{{$item->title}}</a>
                </div>
                <small class="text-muted">{{\Carbon\Carbon::parse($item['servicedate'])->diffForHumans()}}</small>
                <a href="{{url('/')}}/sermons/{{date('Y',strtotime($item->series->startingdate))}}/{{$item->series->slug}}">
                    <img class="card-img-top" src="{{url('/storage/' . $item->series->image)}}" alt="{{$item->series->series}}">
                </a>
            @endif
            <div class="pt-3 text-center">
                <a title="Click to open Bible reading" target="_blank" href="http://biblegateway.com/passage/?search={{urlencode($item->reading)}}&version=GNT";">{{$item->reading}}</a><br>
                @if ($item->person)
                    <small class="text-muted">{{$item->sermon_title}} ({{$item->person->fullname}})</small>
                @endif
                <audio controls><source src="{{$item->audio}}" type="audio/mpeg">Your device or browser does not support the audio tag.</audio>
            </div>
        @elseif ($item->image)
            <a href="{{route('app.devotionals')}}">
                <img class="card-img-top" src="{{url('/storage/' . $item->image)}}" alt="{{$item->reading}}">
            </a>
        @endif
    @empty
        No items have been published in the last 40 days.
    @endforelse
    <script>
        function refresh() {
            console.log('refreshing');
            setCookie("wmc-version", "{{setting('general.app_version')}}", 365);
            window.location.reload();
        }

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        window.addEventListener('load', function() {
            let version = getCookie("wmc-version");
            newversion = "{{setting('general.app_version')}}";
            if (version !== newversion){
                var modal = new bootstrap.Modal(document.getElementById('versionModal'))
                modal.show();
            }
            console.log('Version: ' + version);
            
            let installPrompt = null;
            const installButton = document.querySelector("#installbutton");
            window.addEventListener("beforeinstallprompt", (event) => {
                event.preventDefault();
                installPrompt = event;
                installButton.removeAttribute("hidden");
            });

            installButton.addEventListener("click", async () => {
                if (!installPrompt) {
                    return;
                }
                const result = await installPrompt.prompt();
                console.log(`Install prompt was: ${result.outcome}`);
                disableInAppInstallPrompt();
            });

            window.addEventListener("appinstalled", () => {
                disableInAppInstallPrompt();
            });

            function getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                }
                return "";
            }

            function disableInAppInstallPrompt() {
                installPrompt = null;
                installButton.setAttribute("hidden", "");
            }
        })
    </script>
</x-church::layouts.app>