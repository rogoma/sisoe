<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Órdenes de Ejecución</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 10px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header img {
            height: 80px;
            margin-bottom: 10px;
        }

        .header h2 {
            color: #333;
            margin-top: 0;
        }

        .dashboard-section {
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .dashboard-section h3 {
            text-align: center;
            color: #555;
            margin-bottom: 10px;
        }

        .chart-container {
            width: 100%;
            max-width: 700px;
            height: 300px;
            margin: 0 auto;
            position: relative;
        }

        .amount-card {
            text-align: center;
            background-color: #e9e9e9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .amount-card_2 {
            text-align: center;
            background-color: #e9e9e9;
            padding: 10px;
            border-radius: 8px;
            margin-top: 5px;
        }

        /* Estilo específico para reducir la fuente de la tabla en .amount-card_2 */
        .amount-card_2 .responsive-table table {
            font-size: 0.9em; /* Puedes ajustar este valor según lo necesites */
        }

        .amount-card p {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
            margin: 10px 0 0 0;
        }

        .amount-chart-container {
            width: 150px;
            margin: 0 auto 10px auto;
        }

        .responsive-table {
            width: 100%;
            overflow-x: auto; /* Permite el scroll horizontal en pantallas pequeñas */
        }

        .responsive-table table {
            width: 100%;
            min-width: 400px; /* Asegura que la tabla no se vea demasiado comprimida */
            border-collapse: collapse;
            text-align: center;
        }

        .responsive-table th,
        .responsive-table td {
            border: 1px solid #ccc;
            padding: 16px;
        }

        .responsive-table th {
            border: 2px solid #ccc;
        }

        /* Media query para pantallas de hasta 600px de ancho */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header img {
                height: 50px;
            }

            .header h2 {
                font-size: 1.2em;
            }

            .amount-card p {
                font-size: 1.5em;
            }

            .chart-container,
            .amount-chart-container {
                width: 100%;
                height: auto; /* Permite que la altura se ajuste al contenido */
            }

            canvas {
                width: 100% !important;
                height: auto !important;
            }

            .responsive-table table {
                font-size: 0.9em; /* Reduce el tamaño de la fuente en tablas */
            }

            .responsive-table th,
            .responsive-table td {
                padding: 10px; /* Reduce el padding en celdas de tabla */
            }
        }

        /* Opcional: Media query para pantallas un poco más grandes (ej: tablets) */
        @media (min-width: 601px) and (max-width: 900px) {
            .container {
                padding: 15px;
            }

            .header img {
                height: 60px;
            }

            .header h2 {
                font-size: 1.4em;
            }

            .chart-container {
                max-width: 100%; /* Los gráficos ocupan más espacio en tablets */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logoVI_2.png') }}" alt="Logo">
            <h2 style="text-align: center; color: red;">Tablero de Órdenes de Ejecución</h2>
            <p style="text-align: center; color: #666; font-size: 0.9em;"> {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="dashboard-section">
            <div class="amount-card">
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Contratistas</th>
                                <th>Órdenes</th>
                                <th>Fiscales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ number_format($summary2->total_contratistas, 0, ',', '.') }}</td>
                                <td>{{ number_format($summary2->total_ordenes, 0, ',', '.') }}</td>
                                <td>{{ number_format($summary2->total_fiscales, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="amount-card_2">
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Contratista</th>
                                <th>Cant. Órdenes</th>
                                <th>Monto Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary3 as $item)
                            <tr>
                                <td>{{ $item->contratista }}</td>
                                <td>{{ number_format($item->cant_ordenes, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->total_monto, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div class="chart-container">
                <canvas id="countsChart"></canvas>
            </div>
        </div>

        <div class="dashboard-section">
            <h2 style="text-align: center; color: red;">Monto Total de Órdenes Ejecutadas</h2>
            <div class="amount-chart-container">
                <canvas id="amountChart"></canvas>
            </div>
            <div class="amount-card">
                <p>Gs. {{ number_format($summary->total_orders_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Gráfico de cantidades
        const countsCtx = document.getElementById('countsChart').getContext('2d');
        const countsChart = new Chart(countsCtx, {
            type: 'bar',
            data: {
                labels: ['Departamentos', 'Distritos', 'Localidades'],
                datasets: [{
                    label: 'Cantidades',
                    data: [
                        {{ $summary->total_departments }},
                        {{ $summary->total_districts }},
                        {{ $summary->total_localities }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Cantidades'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: ''
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Alcance por Niveles Geográficos'
                    }
                }
            }
        });

        // Gráfico de monto total
        const amountCtx = document.getElementById('amountChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'doughnut',
            data: {
                labels: ['Monto Total'],
                datasets: [{
                    data: [{{ $summary->total_orders_amount }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                cutout: '80%',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
