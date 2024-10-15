<x-church::website.layout pageName="Books">
    <style>
        #camera video{
            width:100%;
            max-width: 640px;
        }
    </style>
    <h1>Books</h1>
    @if(count($member))
        <div id="camera" style="width:100%"></div>
    @endif
    @foreach ($books as $book)
        <div><a href="{{url('/')}}/books/{{$book->id}}">{{$book->title}}</a> {{$book->allauthors}} </div>
    @endforeach
    {{$books->links()}}
    <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
    <script>
        const quaggaConf = {
            inputStream: {
                target: document.querySelector("#camera"),
                type: "LiveStream",
                constraints: {
                    width: { min: 320 },
                    height: { min: 120 },
                    facingMode: "environment",
                    aspectRatio: { min: 1, max: 2 }
                },
                frequency: 1000, 
                locator: {
                    halfSample: false,
                    patchSize: "small", 
                },
            },
            decoder: {
                readers: ['ean_reader']
            },
        }
        Quagga.init(quaggaConf, function (err) {
            if (err) {
                return console.log(err);
            }
            Quagga.start();
        });
    
        Quagga.onDetected(function (result) {
            alert("Detected barcode: " + result.codeResult.code);
        });
    </script>
</x-church::layout>                
