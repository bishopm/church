<x-mail::message>
# {{$subject}}

Dear {{$firstname}}

{{$body}}
 
<x-mail::button :url="$url">
Visit our website
</x-mail::button>
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>