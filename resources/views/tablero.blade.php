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
            <iframe 
                src="https://docs.google.com/spreadsheets/d/14kAe0AQ693oNBuumkVBEUTFeUs9WhJtfhRKtpMh9hl8/preview?gid=0&single=true"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
@endsection
