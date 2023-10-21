<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
    <h1>Prueba</h1>

    <div class="table-responsive">
        <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="border-bottom-0">
                        <b>N Factura</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Monto</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Proveedor</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Periodo compra</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Realizada por</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados1 as $resultado)
                <tr>
                    <td>{{ $resultado->numerosfactura }}</td>
                    <td>${{ $resultado->monto }}</td>
                    <td>{{ $resultado->nombres }}</td>
                    <td>{{ date('Y/m/d', strtotime($resultado->fecha_inicio)) }}</td>
                    <td>{{ $resultado->creado_por }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <br> <!-- Espacio en blanco entre las tablas -->

    <div class="table-responsive">
        <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="border-bottom-0">
                        <b>Nombre</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Cantidad</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Precio Unitario</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Total</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Iva</b>
                    </th>
                    <th class="border-bottom-0">
                        <b>Total con Iva</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados2 as $resultado)
                <tr>
                    <td>{{ $resultado->nombre }}</td>
                    <td>{{ $resultado->cantidad }}</td>
                    <td>${{ $resultado->precioUnitario }}</td>
                    <td>${{ $resultado->total }}</td>
                    <td>${{ $resultado->Iva }}</td>
                    <td>${{ $resultado->TotalConIva }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>
