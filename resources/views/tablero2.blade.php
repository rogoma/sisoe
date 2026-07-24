@extends('layouts.app')

@push('styles')
<style>
    .looker-wrapper {
        position: relative;
        width: 100%;
        height: 90vh;
        overflow: hidden;
    }

    .looker-wrapper iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    @media (max-width: 768px) {
        .looker-wrapper {
            height: 100dvh; /* mejor que vh en móviles */
        }

        h2 {
            font-size: 1.2rem;
            margin: 0.75rem 0;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <h2 class="mb-3 text-center fw-semibold">
        Conectividad Alcantarillado
    </h2>

    <div class="looker-wrapper">
        <iframe
            id="looker"
            src="https://lookerstudio.google.com/embed/reporting/aeb0f3a9-03aa-499a-8186-caa5b7bd2658/page/1jdnF"
            loading="lazy"
            allowfullscreen
            sandbox="allow-storage-access-by-user-activation
                     allow-scripts
                     allow-same-origin
                     allow-popups
                     allow-popups-to-escape-sandbox"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Recarga real del iframe cada 50 segundos
    setInterval(() => {
        const iframe = document.getElementById('looker');
        iframe.src = iframe.src;
    }, 50000);
</script>
@endpush

{{-- @extends('layouts.app')

@push('styles')
<style type="text/css">

p.centrado {

}
</style>
@endpush

@section('content')
    <div class="container-fluid p-0"> <!-- Usamos container-fluid para ocupar todo el ancho -->
        <h2 class="mb-4 text-center">Conectividad Alcantarillado</h2>
        <div style="position: relative; width: 100%; height: 90vh; overflow: hidden;">
            <iframe 
                id="looker"                
                src="https://lookerstudio.google.com/embed/reporting/aeb0f3a9-03aa-499a-8186-caa5b7bd2658/page/1jdnF" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                style="border:0; width:100%; height:100%;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>    
@endsection

<script>
  setInterval(() => {
    document.getElementById('looker').src += '';
  }, 50000); // recarga cada 30 segundos
</script> --}}