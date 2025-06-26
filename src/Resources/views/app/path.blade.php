<x-church::layouts.card pageName="Card">
    <div style="background-image: url('{{url('/storage/' . $card->image)}}'); height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;">
        Path begins here: {{$card->card}}
    </div>
</x-church::layouts.app>