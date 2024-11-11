<x-mail::message>
# {{$subject}}

Dear {{$firstname}}

{{$body}}
  
Thanks,<br>
{{ config('app.name') }}

<x-mail::button :url="$url">
www.westvillemethodist.co.za
</x-mail::button>
</x-mail::message>