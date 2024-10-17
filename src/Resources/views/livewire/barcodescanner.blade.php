<div id="container">
    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input wire:model="barcode" id="isbn" placeholder="Click to scan barcode ->" type="text" class="form-control">
                <div class="input-group-append">
                    <button id="startButton" onclick="scan();" class="btn btn-outline-secondary" type="button"><span class="bi bi-upc-scan"></span></button>
                </div>
            </div>
            <div id="camera" class="overlay__content" style="display:none;"></div>
        </div>
        <div class="col-md-6">
            <div id="bookdetails" style="display:block;">
                <table class="table">
                    @if ($title)
                        <tr><td rowspan="3"><img src="{{$image}}"></td><th>Title</th><td>{{$title}}</td></tr>
                        <tr><th>Authors</th><td>{{$authors}}</td></tr>
                        <tr><td></td><td><button wire:click="saveBorrow({{$member['id']}})" type="button" class="btn btn-primary">Borrow this book for two weeks</button></td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function scan(){
        camera=document.getElementById('camera');
        if (camera.style.display==="block") {
            camera.style.display="none";
        } else {
            camera.style.display="block";
        }
    }
</script>
@assets
<script src="{{asset('church/js/quagga.js')}}"></script>
<script src="{{asset('church/js/barcodescanner.js')}}"></script>
@endassets