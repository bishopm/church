<x-church::layouts.app pageName="App settings">
    <div>
        <h3>Settings</h3>
        <form>
            <h4>Devotionals</h4>
            <p><input type="checkbox" onclick="cookme(this,'dev_biby')" name="dev_biby"> Bible in a year</p>
            <p><input type="checkbox" onclick="cookme(this,'dev_ffdl')" name="dev_ffdl"> Faith for daily living</p>
            <p><input type="checkbox" onclick="cookme(this,'dev_meth')" name="dev_meth"> Methodist prayer</p>
            <p><input type="checkbox" onclick="cookme(this,'dev_qmom')" name="dev_qmom"> Quiet moments</p>
        </form>
        <script>
            function cookme(checkbox,field){
                document.cookie = "wmc-" + field + "=" + checkbox.checked;
            }
        </script>
    </div>
</x-church::layout>                
