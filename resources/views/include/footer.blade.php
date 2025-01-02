            </div>
        </div>
    </div>
</div>
{{-- @if(Auth::user()->role_id == 6)
    @include('include.footers.minor_purchase')
@endif

@if(Auth::user()->role_id == 9)
    @include('include.footers.tenders')
@endif

@if(Auth::user()->role_id == 10)
    @include('include.footers.exceptions')
@endif

@if(Auth::user()->role_id == 7)
    @include('include.footers.awards')
@endif --}}

{{-- DESPLIEGA ALERTAS PARA: 1-ADMINISTRADOR 8-CONTRATOS 26-DERIVAR CONTRATOS 30-UOC2 --}}

@if ( Auth::user()->role_id == 1 || Auth::user()->role_id == 8 || 
      Auth::user()->role_id == 26 || Auth::user()->role_id == 30      
    )
    @include('include.footers.contracts')
@endif

{{-- DESPLIEGA ALERTAS PARA: 2-DOSAPAS 3-FISCAL --}}
@if ( Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
    @include('include.footers.orders')
@endif