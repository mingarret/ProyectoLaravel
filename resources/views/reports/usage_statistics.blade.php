<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Uso</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="custom-card p-4 mb-4">
            <h1 class="text-center mb-4">Estadísticas de Uso</h1>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-primary shadow-sm">
                        <div class="card-body text-center">
                            <h4 class="card-title text-primary">Total de Archivos</h4>
                            <p class="display-5">{{ $stats['total_files'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-success shadow-sm">
                        <div class="card-body text-center">
                            <h4 class="card-title text-success">Archivos Compartidos</h4>
                            <p class="display-5">{{ $stats['total_shared'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-info shadow-sm">
                        <div class="card-body text-center">
                            <h4 class="card-title text-info">Visualizaciones</h4>
                            <p class="display-5">{{ $stats['total_views'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-warning shadow-sm">
                        <div class="card-body text-center">
                            <h4 class="card-title text-warning">Descargas</h4>
                            <p class="display-5">{{ $stats['total_downloads'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
