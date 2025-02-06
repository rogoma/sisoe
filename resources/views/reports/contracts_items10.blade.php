<!DOCTYPE html>
<html>

<head>
    <title>ORDEN DE EJECUCIÓN</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    @page {
        size: legal portrait;
        /* Define tamaño oficio (legal) en orientación vertical */
        margin: 100px 25px 100px 80px;
        /* Márgenes: superior, derecho, inferior, izquierdo */
    }

    body {
        margin-top: 3px;
        margin-bottom: 20px;
    }

    header {
        position: fixed;
        top: -80px;
        left: 0;
        right: 0;
        height: 100px;
        text-align: center;
        line-height: 1.5;
        font-size: 12px;
        border-bottom: 1px solid #ddd;
    }

    footer {
        position: fixed;
        bottom: -30px;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        font-size: 10px;
        color: #777;
        border-top: 1px solid solid #ddd;
    }

    table {
        font-family: arial, sans-serif;
        border: 1px solid black;
        width: 100%;
        font-size: 8px;
    }

    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 4px;
        font-size: 10px;
    }

    thead tr {
        background-color: white(0, 0, 0);
        padding: 2px;
        font-size: 8px;
    }

    h2 {
        text-align: center;
        font-size: 12px;
        margin-bottom: 5px;
    }

    h3 {
        text-align: left;
        font-size: 12px;
        margin-bottom: 15px;
    }
</style>

<body>
    <header>
        <img src="img/logoVI_2.png" alt="Logo" style="height: 70px;"><br>
        <h2>ORDEN DE EJECUCIÓN N° {{ $contracts1[0]->orders_number }} - FISCALIZACIÓN</h2>
    </header>

    <footer>
        Página <span class="page"></span> de <span class="topage"></span>
    </footer>

    <div>
        <br><br>
        <table id="orders_items">
            @for ($i = 0; $i < count($contracts1); $i = $i + 3)
                <tr>
                    <td> Contrato: {{ $contracts1[$i]->contracts_description }} </td>
                    <td> N°Contrato: {{ $contracts1[$i]->contracts_number_year }} <br><br> ID N°: <br>
                        {{ number_format($contracts1[$i]->contracts_iddncp, '0', ',', '.') }}</td>
                    <th> Empresa Contratista: <br><br> {{ $contracts1[$i]->providers_description }}</th>
                    <th> Dependencia Responsable: <br><br> {{ $contracts1[$i]->dependencies_description }}</th>
                    <th> Componente de la obra: {{ $contracts1[$i]->components_description }}</th>
                    <th> Modalidad: {{ $contracts1[$i]->modalities_description }}</th>
                    <th> Localidad(es): {{ $contracts1[$i]->orders_locality }}</th>
                    <th> Monto de la Orden: {{ number_format($contracts1[$i]->orders_total_amount, '0', ',', '.') }}
                    </th>
                </tr>
            @endfor
        </table>
        <h2>DETALLE DE RUBROS</h2>
        <table>
            <tr>
                <th style="text-align: center">N° item</th>
                <th style="text-align: center">Cod.</th>
                <th style="text-align: center; width: 250px;">Rubro</th>
                <th style="text-align: center">Cant.</th>
                <th style="text-align: center">Unid.</th>
                <th style="text-align: center; width: 60px;">Precio UNIT. MO</th>
                <th style="text-align: center; width: 60px;">Precio UNIT. MAT</th>
                <th style="text-align: center; width: 70px;">Precio TOT. MO</th>
                <th style="text-align: center; width: 70px;">Precio TOT. MAT</th>
            </tr>

            @php
                $tot_price_mo = 0;
                $tot_price_mat = 0;
            @endphp

            @for ($i = 0; $i < count($contracts2); $i++)
                <tr>
                    @if ($contracts2[$i]->rubros_code == '9999')
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
                        <td style="text-align: center"> {{ $contracts2[$i]->items_orders_item_number }}</td>
                        <td style="text-align: center"> {{ $contracts2[$i]->rubros_code }}</td>
                        <td> {{ $contracts2[$i]->rubros_description }}</td>
                        <td style="text-align: center"> {{ $contracts2[$i]->items_orders_quantity }}</td>
                        <td style="text-align: center"> {{ $contracts2[$i]->order_presentations_description }}</td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_unit_price_mo, '0', ',', '.') }} </td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_unit_price_mat, '0', ',', '.') }} </td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_tot_price_mo, '0', ',', '.') }} </td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_tot_price_mat, '0', ',', '.') }} </td>

                        @php
                            $tot_price_mo += $contracts2[$i]->items_orders_tot_price_mo;
                            $tot_price_mat += $contracts2[$i]->items_orders_tot_price_mat;
                        @endphp
                    @endif
                </tr>
            @endfor
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" style="font-size: 10px; text-align: right; padding-right: 5px;">SUB-TOTAL GS.:
                    </td>
                    <td style="font-size: 10px; text-align: center;"> {{ number_format($tot_price_mo, '0', ',', '.') }}
                    </td>
                    <td style="font-size: 10px; text-align: center;">
                        {{ number_format($tot_price_mat, '0', ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" style="font-size: 10px; text-align: right; padding-right: 5px;">TOTAL GS. CON
                        IVA:</td>
                    <td style="font-size: 10px; text-align: center;"> </td>
                    <td style="font-size: 10px; text-align: center;">
                        {{ number_format($contracts1[0]->orders_total_amount, '0', ',', '.') }} </td>
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td colspan="12"
                        style="font-size: 12px; font-weight: bold; text-align: left; padding: 15px; position: relative; left: -80px; width: calc(100% + 160px);">
                        OBSERVACIÓN: {{ $contracts1[0]->orders_comments }}
                        <span style="background-color: yellow; padding: 2px 4px;"> Se establece un PLAZO DE EJECUCIÓN DE
                            {{ $contracts1[0]->orders_plazo }} días.</span>
                    </td>
                </tr>

                <tr>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Fecha Emisión: {{ \Carbon\Carbon::parse($contracts1[0]->orders_date)->format('d/m/Y') }}
                    </td>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Firma Fiscalización:
                    </td>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Aclaración Firma:
                    </td>
                    <td colspan="3"></td> <!-- Espacio para balancear si es necesario -->
                </tr>
                <tr>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Fecha Recepción:
                    </td>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Firma Contratista:
                    </td>
                    <td colspan="3"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Aclaración Firma:
                    </td>
                    <td colspan="3"></td> <!-- Espacio para balancear si es necesario -->
                </tr>
            </tfoot>
        </table>
        </table>
    </div>
</body>

</html>
