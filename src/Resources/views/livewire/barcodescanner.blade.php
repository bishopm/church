<div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input wire:model="barcode" id="isbn" placeholder="Click to scan barcode ->" type="text" class="form-control">
                <div class="input-group-append">
                    <button id="startButton" onclick="scan();" class="btn btn-outline-secondary" type="button"><span class="bi bi-upc-scan"></span></button>
                </div>
            </div>
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
    <div id="camera" style="display:none">
        <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
        <div id="sourceSelectPanel" style="display:none">
            <label for="sourceSelect">Video source:</label>
            <select id="sourceSelect" style="max-width:400px">
            </select>
        </div>
    </div>
</div>
<script>
window.onload = (event) => {
    runApp();    
};

function runApp () {
    let selectedDeviceId;
    const codeReader = new ZXing.BrowserMultiFormatReader()
    console.log('ZXing code reader initialized')
    codeReader.listVideoInputDevices()
      .then((videoInputDevices) => {
        const sourceSelect = document.getElementById('sourceSelect')
        selectedDeviceId = videoInputDevices[0].deviceId
        if (videoInputDevices.length >= 1) {
          videoInputDevices.forEach((element) => {
            const sourceOption = document.createElement('option')
            sourceOption.text = element.label
            sourceOption.value = element.deviceId
            sourceSelect.appendChild(sourceOption)
          })

          sourceSelect.onchange = () => {
            selectedDeviceId = sourceSelect.value;
          };

          const sourceSelectPanel = document.getElementById('sourceSelectPanel')
          sourceSelectPanel.style.display = 'block'
        }

        codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
        if (result) {
            Livewire.dispatch('scanned', { isbn: result })
            document.getElementById('result').textContent = result.text
        }
        if (err && !(err instanceof ZXing.NotFoundException)) {
            console.error(err)
            document.getElementById('result').textContent = err
        }
        })
        console.log(`Started continous decode from camera with id ${selectedDeviceId}`)

      })
      .catch((err) => {
        console.error(err)
      })
};

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
<script src="{{asset('church/js/zxing.min.js')}}"></script>
@endassets