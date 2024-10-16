<div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input wire:model="barcode" id="barcode" placeholder="Click to scan barcode ->" type="text" class="form-control">
                <div class="input-group-append">
                    <button id="startButton" class="btn btn-outline-secondary" type="button"><span class="bi bi-upc-scan"></span></button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="bookdetails" style="display:block;">
                <table class="table">
                    <tr><th>Title</th><td>{{$title}}</td></tr>
                    <tr><th>Authors</th><td>{{$authors}}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div id="scanbox" style="display:none;">
        <div>
            <video id="video" width="100%" height="320" style="border: 1px solid gray"></video>
        </div>
        <div id="sourceSelectPanel" style="display:none">
            <label>Video source</label>
            <select id="sourceSelect" class="form-control"></select>
        </div>
    </div>
</div>
@assets
<script src="{{asset('church/js/Zxing.min.js')}}"></script>
<script src="{{asset('church/js/barcodescanner.js')}}"></script>
@endassets
