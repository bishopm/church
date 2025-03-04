<ul class="list-unstyled">
    @foreach ($messages as $message)    
        <li>
            <b>{{$message['author']}}</b>
            <small>{{$message['message']}}
                <i>{{ \Carbon\Carbon::parse($message['time'])->diffForHumans() }}</i>
            </small>
        </li>
    @endforeach
</ul>