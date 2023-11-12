<!doctype html>
<html lang="en">

<head>
    <title>Descargar - PDF</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    @font-face {
        font-family: 'Roboto';
        src: url('/fonts/Roboto/Roboto-Regular.ttf') format('truetype');
    }

    .position_img{
        position: absolute;
        top: -25px;     
    }
    .factura-position{
        position: relative;
        top: -90px; 
        left: 420px
    }

    .mi-linea {
        background-color: #333;
    }

    .factura {
        padding: 10px;
        margin: 10px;
        border-radius: 5px;
        height: 120px;
    }
        
    .factura h2 {
        font-size: 24px;
        margin: 0;
    }

    .datos {
        margin: 10px 0;
    }

    .datos p {
        margin: 2;
    }
    .postPosition{
        position: relative;      
        left: 50px;
    }

    .table-header {
        background-color: #ebebeb; /* Color de fondo */
        color: #333; /* Color de texto */
        font-weight: bold; /* Hace que el texto sea negrita */
    }
    th, td{
        padding: 10px;
    }
    #miTabla {
        border-bottom: 1px solid #000 !important;
    }
    .texto-grande {
        font-size: 18px; /* Ajusta el tamaño del texto según tus preferencias */
    }
</style>

<body>
    <div class="row align-items-center" style="height: 65px">
        <div class="col text-start">
            @foreach ($resultados3 as $resultado3)           
                <h2>{{ $resultado3->valor }}</h2>                
            @endforeach
        </div>
    
        <div class="col text-end d-flex justify-content-center position_img">
            <img src="data:image/jpeg;base64, {{ $resultados4 }}" alt="Imagen_logo" class="mx-auto"
                style="max-width: 250px; max-height: 100px;">
        </div>
    </div>

    <hr class="mi-linea ">

    <div class="factura row" style="height: 85px">
        <h3>Reporte diario de transacciones: </h3>
        <div class="col-md-6 datos postPosition">
            <p>Fecha de Reporte: {{ $fechaInicio->format('d \d\e F \d\e Y', 'es') }}</p>
        </div>
   </div>

   <h2 class="mb-3">Compras: </h2>
   <div class="table-responsive">
    <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered text-center">
        <thead class="text-dark fs-4 table-header">
            <tr>
                <th class="border-bottom-0 text-uppercase font-weight-bold" style="width: 20%">
                    Producto
                </th>
                
                <th class="border-bottom-0 text-uppercase font-weight-bold">
                    Comprados
                </th>
                <th class="border-bottom-0 text-uppercase font-weight-bold">
                    P Unitario
                </th>
                <th class="border-bottom-0 text-uppercase font-weight-bold">
                   Total Compra
                </th>
                <th class="border-bottom-0 text-uppercase font-weight-bold">
                    Stock
                </th>
            </tr>
        </thead>
        <tbody >
            @foreach ($resultados as $resultado)
                <tr >
                    <td class="border-bottom-0">
                        {{ mb_strimwidth(ucfirst($resultado->Nombre_Producto), 0, 20, '...') }}
                    </td>
                    <td class="border-bottom-0">
                        {{ $resultado->Cantidad_Productos_Comprados }}
                    </td>
                    <td class="border-bottom-0">
                        ${{ $resultado->Precio_Unitario }}
                    </td>
                    <td class="border-bottom-0">
                        ${{ $resultado->Monto_Total_Compra }}
                    </td>
                    <td class="border-bottom-0">
                        {{ $resultado->stock }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-end p-2">
        <p class="m-0 texto-grande"><strong>Total en compra en el día:</strong> ${{ number_format($totalMonto, 2) }}</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
</body>

</html>
