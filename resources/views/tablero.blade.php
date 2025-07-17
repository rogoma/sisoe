@extends('layouts.app')

@push('styles')
<style type="text/css">

p.centrado {

}
</style>
@endpush

@section('content')
    <div class="container">
    <h2 class="mb-4 text-center">Conectividad Alcantarillado</h2>
        <div style="position: relative; padding-bottom: 75%; height: 0; overflow: hidden;">
            {{-- <iframe 
                src="https://docs.google.com/spreadsheets/d/14kAe0AQ693oNBuumkVBEUTFeUs9WhJtfhRKtpMh9hl8/preview?gid=0&single=true"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                allowfullscreen>
            </iframe> --}}
            <iframe
                id="looker"
                width="1000" 
                height="1500" 
                src="https://lookerstudio.google.com/embed/reporting/a390cf42-32c9-4097-89a3-b8aed4f4f6e1/page/U3qQF" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">                                                        </iframe>
        </div>
    </div>
@endsection

<script>
  setInterval(() => {
    document.getElementById('looker').src += '';
  }, 30000); // recarga cada 30 segundos
</script>