<!DOCTYPE html>
<html>
<head>
    <title>ORDEN DE EJECUCIÓN</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    table{
        font-family: arial,sans-serif;
        border-collapse:collapse;
        width:100%;
        font-size:8px;
    }
    td, th{
        border:1px solid #dddddd;
        text-align: left;
        padding:4px;
        font-size:10px;
    }
    thead tr{
        background-color:#dddddd;
        padding:2px;
        font-size:8px;
    }

    h2{
        text-align: center;
        font-size:12px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:12px;
        margin-bottom:15px;
    }
    body{
        /* background:#f2f2f2; */
    }

    #orders_items, #orders_items tr, #orders_items tr td {
        border: none;
    }
    #orders_items tr td {
        font-weight: bolder;
        padding: 4px;
    }

    .section{
        margin-top:30px;
        padding:50px;
        background:#fff;
        font-size:8px;
    }
    .pdf-btn{
        margin-top:30px;
    }
    .columna1 { width: 1%; text-align: center;}
    .columna2 { width: 25%; text-align: left;}
    .columna3 { width: 7%; text-align: left;}
    .columna4 { width: 16%; text-align: left;}
    .columna5 { width: 2%; text-align: center;}
    .columna6 { width: 4%; text-align: center;}
    .columna7 { width: 4%; text-align: center;}
    .columna8 { width: 4%; text-align: center;}
    .columna9 { width: 5%; text-align: left;}
    .columna10 { width: 9%; text-align: center;}
    .columna11 { width: 2%; text-align: center;}

    p.centrado {
        text-align: center;
}

</style>
<body>
<p class="centrado"> <img src="img/logoVI_2.png"> </p>    
    {{-- <h6>F:\Proyecto\sisoe\resources\views\reports\contracts_items10.blade.php</h6>     --}}
    <h2>ORDEN DE EJECUCIÓN N° {{ $contracts1[0]->orders_number }} - FISCALIZACIÓN</h2>    
    <br>    
    <table id="orders_items">
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <td> Contrato: {{ $contracts1[$i]->contracts_description }}  -  N°Contrato: {{ $contracts1[$i]->contracts_number_year }}</td>
            <th> Empresa Contratista: {{ $contracts1[$i]->providers_description }}</th>
            <th> Dependencia Responsable: {{ $contracts1[$i]->dependencies_description }}</th>
            <th> Componente de la obra: {{ $contracts1[$i]->components_description }}</th>            
            <th> Monto de la Orden: {{ number_format($contracts1[$i]->orders_total_amount,'0', ',','.') }} </th>            
        </tr>
        @endfor        
        {{-- @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Empresa Contratista: {{ $contracts1[$i]->providers_description }}</th>
        </tr>
        @endfor
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Dependencia Responsable: {{ $contracts1[$i]->dependencies_description }}</th>
        </tr>
        @endfor
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Componente de la obra: {{ $contracts1[$i]->components_description }}</th>
        </tr>
        @endfor --}}
    </table>
    {{-- <br> --}}
    <h2>DETALLE DE RUBROS</h2>
    <table>
        <tr>
            <th>N° item</th>
            <th>Cod_rubro</th>
            <th>Rubro</th>
            <th>Cant.</th>
            <th>Unid.</th>
            <th>Precio UNIT. MO</th>
            <th>Precio UNIT. MAT</th>
            <th>Precio TOT. MO</th>
            <th>Precio TOT. MAT</th>
        </tr>

        @php
            $tot_price_mo = 0; 
            $tot_price_mat = 0;    
        @endphp

        @for ($i = 0; $i < count($contracts2); $i++)
            <tr>                
                @if($contracts2[$i]->rubros_code == '9999')
                    <td> </td>
                    <td> </td>
                    <td><b> {{ $contracts2[$i]->sub_items_oi_description }} </b></td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>                    
                @else
                    <td> {{ $contracts2[$i]->items_orders_item_number }}</td>
                    <td> {{ $contracts2[$i]->rubros_code }}</td>                
                    <td> {{ $contracts2[$i]->rubros_description }}</td>
                    <td> {{ $contracts2[$i]->items_orders_quantity }}</td>
                    <td> {{ $contracts2[$i]->order_presentations_description }}</td>
                    <td> {{ number_format($contracts2[$i]->items_orders_unit_price_mo,'0', ',','.') }} </td>
                    <td> {{ number_format($contracts2[$i]->items_orders_unit_price_mat,'0', ',','.') }} </td>
                    <td> {{ number_format($contracts2[$i]->items_orders_tot_price_mo,'0', ',','.') }} </td>
                    <td> {{ number_format($contracts2[$i]->items_orders_tot_price_mat,'0', ',','.') }} </td>

                    @php
                        $tot_price_mo += $contracts2[$i]->items_orders_tot_price_mo;
                        $tot_price_mat += $contracts2[$i]->items_orders_tot_price_mat;
                    @endphp
                @endif
            </tr>
        @endfor
        <tfoot>
            <tr>
                <td colspan="6"></td>
                <td style="font-size: 10px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                <td style="font-size: 10px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mo, '0', ',', '.') }}</td>
                <td style="font-size: 10px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mat, '0', ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
