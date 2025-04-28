<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Órdenes de Ejecución</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <style>
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
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header img {
            height: 80px; /* Slightly larger logo */
            margin-bottom: 10px;
        }
        .header h2 {
            color: #333;
            margin-top: 0;
        }
        .dashboard-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .dashboard-section h3 {
            text-align: center;
            color: #555;
            margin-bottom: 20px;
        }
        .chart-container {
            width: 100%; /* Make containers responsive within the main container */
            max-width: 700px; /* Max width for charts */
            margin: 0 auto;
        }
        .amount-card {
            text-align: center;
            background-color: #e9e9e9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px; /* Space above the card */
        }
        .amount-card p {
            font-size: 2em; /* Larger font size for the total amount */
            font-weight: bold;
            color: #007bff; /* Highlight color */
            margin: 10px 0 0 0; /* Adjust margin */
        }
        /* Style for the doughnut chart container if needed for alignment */
        .amount-chart-container {
             width: 150px; /* Smaller container for the visual doughnut */
             margin: 0 auto 10px auto; /* Center and add space below */
        }
    </style> --}}
</head>
<body>
    <!-- Logo -->
    <div style="text-align: center; margin-top: 20px;">
        <img src="{{ asset('img/logoVI_2.png') }}" alt="Logo" style="height: 70px;">
        <br>
    </div>

    <!-- Título -->
    <h2 style="text-align:center;">Tablero de Órdenes de Ejecución</h2>

    <!-- Gráfico de cantidades -->
    <div style="width: 600px; margin: 30px auto;">
        <canvas id="countsChart"></canvas>
    </div>

    <!-- Gráfico o tarjeta de monto total -->
    <div style="width: 400px; margin: 30px auto; text-align:center;">
        <h3>Monto Total de Órdenes</h3>
        <canvas id="amountChart" width="300" height="300"></canvas>
        <p style="font-size: 24px; font-weight: bold; margin-top: 10px;">
            Gs. {{ number_format($summary->total_orders_amount, 0, ',', '.') }}
        </p>
    </div>

    <script>
        // Gráfico de cantidades (Departamentos, Distritos, Localidades)
        const countsCtx = document.getElementById('countsChart').getContext('2d');
        const countsChart = new Chart(countsCtx, {
            type: 'bar',
            data: {
                labels: ['Departamentos', 'Distritos', 'Localidades'],
                datasets: [{
                    label: 'Cantidad',
                    data: [
                        {{ $summary->total_departments }},
                        {{ $summary->total_districts }},
                        {{ $summary->total_localities }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
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
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Gráfico de monto total (solo visual, opcionalmente Doughnut)
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


{{-- <!DOCTYPE html>
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
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header img {
            height: 80px; /* Slightly larger logo */
            margin-bottom: 10px;
        }
        .header h2 {
            color: #333;
            margin-top: 0;
        }
        .dashboard-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .dashboard-section h3 {
            text-align: center;
            color: #555;
            margin-bottom: 20px;
        }
        .chart-container {
            width: 100%; /* Make containers responsive within the main container */
            max-width: 700px; /* Max width for charts */
            margin: 0 auto;
        }
        .amount-card {
            text-align: center;
            background-color: #e9e9e9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px; /* Space above the card */
        }
        .amount-card p {
            font-size: 2em; /* Larger font size for the total amount */
            font-weight: bold;
            color: #007bff; /* Highlight color */
            margin: 10px 0 0 0; /* Adjust margin */
        }
        /* Style for the doughnut chart container if needed for alignment */
        .amount-chart-container {
             width: 150px; /* Smaller container for the visual doughnut */
             margin: 0 auto 10px auto; /* Center and add space below */
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logoVI_2.png') }}" alt="Logo">
            <h2>Tablero de Resumen de Órdenes de Ejecución</h2>
        </div>

        <div class="dashboard-section">
            <h3>Cantidad de Órdenes por Ubicación Geográfica</h3>
            <div class="chart-container">
                <canvas id="countsChart"></canvas>
            </div>
        </div>

        <div class="dashboard-section">
            <h3>Monto Total de Órdenes Ejecutadas</h3>
             <div class="amount-chart-container">
                 <canvas id="amountChart"></canvas>
             </div>
            <div class="amount-card">
                 <p>
                    Gs. {{ number_format($summary->total_orders_amount, 2, ',', '.') }}
                 </p>
            </div>
        </div>
    </div>

    <script>
        // Gráfico de cantidades (Departamentos, Distritos, Localidades)
        const countsCtx = document.getElementById('countsChart').getContext('2d');
        const countsChart = new Chart(countsCtx, {
            type: 'bar',
            data: {
                labels: ['Departamentos', 'Distritos', 'Localidades'],
                datasets: [{
                    label: 'Cantidad de Órdenes', // More descriptive label
                    data: [
                        {{ $summary->total_departments }},
                        {{ $summary->total_districts }},
                        {{ $summary->total_localities }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)', // Slightly more opaque
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
                responsive: true, // Make chart responsive
                maintainAspectRatio: false, // Allow height to be controlled
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Ensure steps of 1 for counts
                        },
                        title: { // Add axis title
                            display: true,
                            text: 'Número de Órdenes'
                        }
                    },
                     x: { // Add axis title
                        title: {
                            display: true,
                            text: 'Tipo de Ubicación'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide legend if only one dataset
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Órdenes por Nivel Geográfico' // Chart title
                    }
                }
            }
        });

        // Gráfico de monto total (visual doughnut)
        const amountCtx = document.getElementById('amountChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'doughnut',
            data: {
                labels: ['Monto Total'], // Keep label for potential tooltip
                datasets: [{
                    data: [{{ $summary->total_orders_amount }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)' // Use a color often associated with money/success
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, // Make chart responsive
                maintainAspectRatio: false, // Allow height to be controlled
                cutout: '70%', // Slightly smaller cutout for a thicker ring
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false // Disable tooltip for this visual element
                    }
                }
            }
        });
    </script>
</body>
</html> --}}