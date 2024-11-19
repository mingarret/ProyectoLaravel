<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación de Dos Factores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="custom-card p-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h5>Verificación de Dos Factores</h5>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de Autenticación:</label>
                                <input type="text" name="code" class="form-control" placeholder="Ingrese el código de 2FA" autofocus required />
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Verificar</button>
                        </form>

                        <!-- Alternativa de recuperación con código -->
                        <hr>
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <input type="hidden" name="recovery_code" value="1">
                            <div class="mb-3">
                                <label for="recovery_code" class="form-label">Código de Recuperación:</label>
                                <input type="text" name="recovery_code" class="form-control" placeholder="Ingresa el código de recuperación" />
                            </div>
                            <button type="submit" class="btn btn-secondary w-100">Verificar con Código de Recuperación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
