@extends('layouts.app')

@push('styles')
<style type="text/css">

p.centrado {

}
</style>
@endpush

@section('content')
    <div class="container-fluid p-0"> 
        <h2 class="mb-4 text-center">Intervenciones BID 3601</h2>
        <div style="position: relative; width: 100%; height: 90vh; overflow: hidden;">
            <iframe 
                id="maps"
                src="https://www.google.com/maps/d/embed?mid=1lcronzjFJU26PJOfzgqpTfnwbniDjws&ehbc=2E312F"
                style="border:0; width:100%; height:100%;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
@endsection

<script>
//   setInterval(() => {
//     document.getElementById('looker').src += '';
//   }, 50000); // recarga cada 30 segundos
</script>



{{-- @section('content')
    <div class="container">
    <h2 class="mb-4 text-center">Intervenciones BID 3601</h2>
        <div style="position: relative; padding-bottom: 75%; height: 0; overflow: hidden;">            
            
            <iframe 
                id="looker"
                width="1500" 
                height="2000"             
                src="https://www.google.com/maps/d/embed?mid=1lcronzjFJU26PJOfzgqpTfnwbniDjws&ehbc=2E312F" width="800" height="1680"></iframe>            
            
                
        </div>
    </div>
@endsection --}}