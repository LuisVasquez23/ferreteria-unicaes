@extends('layouts/dashboard')

@section('title', 'Ingresar Venta')

@section('contenido')
    <div class="card mt-3">
        <h5 class="card-header">Ingresar Venta</h5>
        <div class="card-body">
            <form action="{{ route('ventas.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Columna para el número de factura -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="numero_factura" class="form-label">Número de Factura:</label>
                            <input type="number" class="form-control" id="numero_factura" name="numero_factura" required
                                value="{{ old('numero_factura') }}">
                                <div id="mensaje_errorF" style="color: red;"></div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente:</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                @foreach ($clientes as $usuario_id => $nombreCompleto)
                                    <option value="{{ $usuario_id }}">{{ $nombreCompleto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  


                </div>

                <div class="row">
                    <!-- Columna para seleccionar el producto -->
                             <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="periodo_id" class="form-label">Período: *</label>
                                    <select name="periodo_id" id="periodo_id" class="form-select">
                                        @if ($periodos->isEmpty())
                                            <option value="" disabled selected>No se encontraron períodos</option>
                                        @else
                                            @foreach ($periodos as $periodo_id => $fecha_inicio)
                                                <option value="{{ $periodo_id }}">
                                                    {{ \Carbon\Carbon::parse($fecha_inicio)->format('Y/m/d') }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto:</label>
                            <select class="form-select" id="producto_id" name="producto_id" required>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                                        {{ $producto->nombre }} - Proveedor: {{ $producto->usuario->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Columna para la cantidad de producto -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" step="1"
                                required min="1">
                                <div id="mensaje_errorC" style="color: red;"></div>
                            <!-- Agregado para mostrar el mensaje de error -->
                        </div>
                    </div>

                </div>

                    <div class="row">
                        <!-- Columna para el botón "Agregar Producto" -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div class="mb-3 mt-2">

                            <button type="button" class="btn btn-success" id="agregar-producto">Agregar Producto</button>
                            <a href="{{ route('ventas') }}" class="btn btn-dark me-1 ms-2">Regresar</a>

                            </div>
                        </div>
                    </div>      

                <!-- Campo oculto para la lista de productos -->
                <input type="hidden" name="lista_productos" id="lista_productos_input" value="">
                <input type="hidden" name="monto_total" id="monto_total" value="">
                <input type="hidden" name="ivaTotal" id="ivaTotal" value="">
                <input type="hidden" name="totalMasIVA" id="totalMasIVA" value="">
            </form>

            <!-- Lista de productos seleccionados -->
            <div class="mt-4">
                <h5>Productos Seleccionados:</h5>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre Producto</th>
                                <th>Número de Lote</th> <!-- Nueva columna para el número de lote -->
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Precio Total</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="lista-productos">
                            <!-- Aquí se mostrarán los productos seleccionados -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <!-- Columna para mostrar el monto total -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="monto_totalShow" class="form-label">Monto Total:</label>
                        <input type="text" class="form-control" id="monto_totalShow" readonly>
                    </div>
                </div>

                <!-- Columna para mostrar el IVA -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="ivaShow" class="form-label">IVA (13%):</label>
                        <input type="text" class="form-control" id="ivaShow" readonly>
                    </div>
                </div>

                <!-- Columna para mostrar el total + IVA -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="totalMasIVA" class="form-label">Total + IVA (13%):</label>
                        <input type="text" class="form-control" id="totalMasIVAShow" readonly>
                    </div>
                </div>

                <!-- Columna para el botón "Finalizar Venta" -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div class="mb-3 mt-2">
                        <button type="button" class="btn btn-primary" id="finalizar-venta" disabled>Finalizar
                            Venta</button>
                        </div>
                </div>


            </div>
        </div>
    </div>
    <!-- HTML para el modal Bootstrap -->
    <div class="modal fade" id="jsonModal" tabindex="-1" role="dialog" aria-labelledby="jsonModalLabel"
        aria-hidden="true" data-bs-backdrop="static" >
        <div class="modal-dialog modal-lg" role="document"> <!-- Agregando la clase modal-lg para hacerlo ancho -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jsonModalLabel">Seleccion precio</h5>
        
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-2">Opciones:</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="cambiarManual" name="opcionPrecio"
                                value="cambiarManual">
                            <label class="form-check-label" for="cambiarManual">Cambiar precio sugerido</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="mantenerFijo" name="opcionPrecio"
                                value="mantenerFijo" checked>
                            <label class="form-check-label" for="mantenerFijo">Mantener precio</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="mismoPrecio" name="opcionPrecio"
                                value="mismoPrecio">
                            <label class="form-check-label" for="mismoPrecio">Vender al mismo precio</label>
                        </div>
                        <div class="form-group mt-3 mb-2">
                            <label for="porcentajeGanancia">Porcentaje de Ganancia:</label>
                            <select class="form-control mt-1" id="porcentajeGanancia" name="porcentajeGanancia" disabled>
                                <option value="0.10">10%</option>
                                <option value="0.20">20%</option>
                                <option value="0.30">30%</option>
                                <option value="0.40">40%</option>
                                <option value="0.50">50%</option>
                                <option value="0.60">60%</option>
                                <option value="0.70">70%</option>
                                <option value="0.80">80%</option>
                                <option value="0.90">90%</option>
                                <option value="1.0">100%</option>
                            </select>
                        </div>
                    </div>


                    <table id="jsonTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Número de Lote</th>
                                <th>Cantidad disponible</th>
                                <th>Cantidad a vender</th>
                                <th>Precio de compra</th>
                                <th>Precio de venta sugerido</th> <!-- Nueva columna con inputs -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenará la tabla con datos desde JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="pb-5 text-center">
                    <button type="button" id="agregar-lista" class="btn btn-primary me-2">Agregar a la lista</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                </div>
            </div>
        </div>
    </div>





@endsection

@section('AfterScript')
    <script>
        $(document).ready(function() {
            // Variables para almacenar la lista de productos y el monto total
            var listaProductos = [];
            var montoTotal = 0;
            var ivaTotal = 0;
            var totalMasIVA = 0;

            $('#jsonModal').on('hidden.bs.modal', function () {
                // Vaciar la tabla
                $('#jsonTable tbody').empty();
                
                // Seleccionar la opción por defecto en el grupo de radio buttons
                $('#mantenerFijo').prop('checked', true);
                
                // Restablecer el valor del select a la primera opción
                $('#porcentajeGanancia').val('0.10');

            });

            //desahbilitar finalizar compra:
            function habilitarDeshabilitarBotonFinalizar() {
                if (listaProductos.length > 0) {
                    $('#finalizar-venta').prop('disabled', false);
                } else {
                    $('#finalizar-venta').prop('disabled', true);
                }
            }
            //desahbilitar finalizar venta:

            //entrar en modal para precios
            function definirPrecio() {
                var productoId = $('#producto_id').val();
                var productoNombre = $('#producto_id option:selected').text().split(' - Precio')[0];
                var cantidad = parseInt($('#cantidad').val());
                var precioUnitario = parseFloat($('#precio_unitario').val());
                var numeroFactura = $('#numero_factura').val();
                var cantidadNueva = 0;

            

                // Validación de cantidad no vacía
                if (isNaN(cantidad) || cantidad <= 0) {
                    // Mostrar alerta personalizada
                    AlertMessage('La cantidad debe ser un número mayor que cero', 'error');
                    $('#cantidad').css('border', '1px solid red');
                    $('#mensaje_errorC').text('Ingresa un valor valida para cantidad.');


                    return;
                }else{
                    $('#cantidad').css('border', '1px solid #ccc');
                    $('#mensaje_errorC').text('');
                }
                if(isNaN(numeroFactura) || numeroFactura<=0){
                        AlertMessage("El numero de factura debe ser valido es decir no vacio o mayor que cero", "error");                    $('.precio-sugerido').css('opacity', 0.2);
                        $('#numero_factura').css('border', '1px solid red');
                        $('#mensaje_errorF').text('Este no es un valor valido para la factura.');

                        return; // mostrar alerta
                }else{
                    $('#numero_factura').css('border', '1px solid #ccc');
                    $('#mensaje_errorF').text('');
                }


                // Buscar si el producto ya existe en la lista
                var productoExistenteIndex = -1; // Inicializa el índice como -1 (no encontrado)
                for (var i = 0; i < listaProductos.length; i++) {
                    if (listaProductos[i].productoId == productoId) {
                        productoExistenteIndex = 1;

                        cantidadNueva += parseInt(listaProductos[i].cantidad);
                    }
                }

                if (productoExistenteIndex !== -1) {
                    var cantidadTotal = cantidadNueva + cantidad;
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('verificar-cantidad') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            producto_id: productoId,
                            cantidad: cantidadTotal
                        },
                        success: function(response) {

                            console.log(response);

                            if (response.suficiente) {

                                // Limpia la tabla antes de agregar nuevos datos
                                $('#jsonTable tbody').empty();

                                // Recorre los lotes disponibles en la respuesta JSON y crea filas de tabla
                                response.lotesDisponibles.forEach(function(lote) {
                                    var precioUnitario = parseFloat(lote.precio_unitario);
                                    var precioConGanancia = precioUnitario + (precioUnitario *
                                        0.10); // Calcula el precio con un 10% de ganancia

                                    // Agrega input en lugar de texto
                                    var newRow = '<tr>' +
                                        '<td class="numero-lote">' + lote.numero_lote +
                                        '</td>' +
                                        '<td class="cantidad-disponible">' + lote
                                        .cantidad_disponible + '</td>' +
                                        '<td class="cantidad-comprada">' + lote
                                        .cantidad_comprada + '</td>' +
                                        '<td class="precio-unitario">' + precioUnitario.toFixed(
                                            2) + '</td>' +
                                        '<td>' +
                                        '<input type="number" class="precio-sugerido" value="' +
                                        precioConGanancia.toFixed(2) + '" readonly>' +
                                        '</td>' +
                                        '</tr>';


                                    $('#jsonTable tbody').append(newRow);
                                });

                                // Muestra el modal
                                $('#jsonModal').modal('show');
                            } else {
                                // Si no hay suficiente cantidad, muestra un mensaje al usuario
                                AlertMessage(response.lotesDisponibles, 'error');
                            }

                        }
                    })
                } else {

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('verificar-cantidad') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            producto_id: productoId,
                            cantidad: cantidad
                        },
                        success: function(response) {

                            console.log(response);

                            if (response.suficiente) {

                                // Limpia la tabla antes de agregar nuevos datos
                                $('#jsonTable tbody').empty();

                                // Recorre los lotes disponibles en la respuesta JSON y crea filas de tabla
                                response.lotesDisponibles.forEach(function(lote) {
                                    var precioUnitario = parseFloat(lote.precio_unitario);
                                    var precioConGanancia = precioUnitario + (precioUnitario *
                                        0.10); // Calcula el precio con un 10% de ganancia

                                    // Agrega input en lugar de texto
                                    var newRow = '<tr>' +
                                        '<td class="numero-lote">' + lote.numero_lote +
                                        '</td>' +
                                        '<td class="cantidad-disponible">' + lote
                                        .cantidad_disponible + '</td>' +
                                        '<td class="cantidad-comprada">' + lote
                                        .cantidad_comprada + '</td>' +
                                        '<td class="precio-unitario">' + precioUnitario.toFixed(
                                            2) + '</td>' +
                                        '<td>' +
                                        '<input type="number" class="precio-sugerido" value="' +
                                        precioConGanancia.toFixed(2) + '" readonly>' +
                                        '</td>' +
                                        '</tr>';


                                    $('#jsonTable tbody').append(newRow);
                                });

                                // Muestra el modal
                                $('#jsonModal').modal('show');
                            } else {
                                // Si no hay suficiente cantidad, muestra un mensaje al usuario
                                AlertMessage('No hay suficiente stock, la cantidad disponible es de: ' +
                                    response.lotesDisponibles, 'error');
                            }

                        }
                    })
                }



            }
            // Función para agregar productos a la lista
            function agregarProductos(productosModal) {
                for (var i = 0; i < productosModal.length; i++) {

                    var productoModal = productosModal[i];
                    var numeroLote = productoModal.numeroLote;
                    var cantidad = parseInt(productoModal.cantidad);
                    var cantidadDipo = parseInt(productoModal.cantidadDisponible);
                    var precioUnitario = parseFloat(productoModal.precioUnitario);

                    // Obtener otros valores de los campos normales
                    var productoId = $('#producto_id').val();
                    var productoNombre = $('#producto_id option:selected').text().split(' - Proveedor')[0];

                    var numeroFactura = $('#numero_factura').val();
                    // Validar los valores
                    if (isNaN(cantidad) || cantidad <= 0) {
                        AlertMessage('La cantidad debe ser un número mayor que cero', 'error');
                        continue; // Salta a la siguiente iteración del bucle
                    }

                    if (isNaN(precioUnitario) || precioUnitario <= 0) {
                        AlertMessage("El precio unitario debe ser un número mayor que cero.", "error");
                        continue; // Salta a la siguiente iteración del bucle
                    }
                    if(isNaN(numeroFactura) || numeroFactura <=0){
                        AlertMessage("El numero de factura debe ser valido es decir no vacio o mayor que cero", "error");
                        continue; // Salta a la siguiente iteración del bucle
                    }
                    // Calcular el subtotal
                    var subtotal = cantidad * precioUnitario;

                    // Buscar si el producto ya existe en la lista
                    var productoExistente = listaProductos.find(function(producto) {
                        return producto.productoId == productoId && producto.numeroLote == numeroLote;;
                    });

                    // Actualizar o agregar el producto en la lista
                    if (productoExistente) {
                        productoExistente.cantidad = cantidad;
                        productoExistente.subtotal = subtotal;
                        productoExistente.precioUnitario = precioUnitario;
                    } else {
                        listaProductos.push({
                            productoId: productoId,
                            nombre: productoNombre,
                            numeroLote: numeroLote,
                            cantidad: cantidad,
                            precioUnitario: precioUnitario,
                            subtotal: subtotal,
                            numeroFactura: numeroFactura,
                            cantidadDiponible: cantidadDipo,
                        });

                    }
                }

                // Actualizar la lista de productos en la vista
                actualizarListaProductos();

                // Calcular el monto total, IVA y Total + IVA
                calcularMontoTotal();
                calcularIVA();
                calcularTotalMasIVA();

                // Limpiar los campos de cantidad y precio unitario
                $('#cantidad').val('');
                $('#precio_unitario').val('');
                $('#numero_factura').css('border', '1px solid #ccc');
                $('#cantidad').css('border', '1px solid #ccc');
                $('#mensaje_errorF').text('');
                $('#mensaje_errorC').text('');



            }



            // Función para actualizar la lista de productos en la vista
            function actualizarListaProductos() {
                var listaHtml = '';
                listaProductos.forEach(function(producto, index) {
                    listaHtml += '<tr>';
                    listaHtml += '<td>' + producto.nombre + '</td>';
                    listaHtml += '<td>' + producto.numeroLote + '</td>';
                    listaHtml += '<td><input type="number" class="form-control cantidad-editable" value="' +
                        producto.cantidad + '"readonly></td>';
                    listaHtml +=
                        '<td><input type="number" class="form-control precio-unitario-editable" step="0.01" value="' +
                        producto.precioUnitario.toFixed(2) + '"></td>';
                    listaHtml += '<td>' + producto.subtotal.toFixed(2) + '</td>';
                    listaHtml +=
                        '<td><button type="button" class="btn btn-danger eliminar-producto" data-index="' +
                        index + '">Eliminar</button></td>';
                    listaHtml += '</tr>';
                });
                $('#lista-productos').html(listaHtml);
            }

            // Función para calcular el monto total
            function calcularMontoTotal() {
                montoTotal = 0;
                listaProductos.forEach(function(producto) {
                    montoTotal += producto.subtotal;
                });
                $('#monto_totalShow').val(montoTotal.toFixed(2));
            }

            // Función para calcular el IVA
            function calcularIVA() {
                var ivaPorcentaje = 0.13; // Porcentaje de IVA (13% en este ejemplo)
                ivaTotal = montoTotal * ivaPorcentaje;
                $('#ivaShow').val(ivaTotal.toFixed(2));
            }

            // Función para calcular el Total + IVA
            function calcularTotalMasIVA() {
                totalMasIVA = montoTotal + ivaTotal;
                $('#totalMasIVAShow ').val(totalMasIVA.toFixed(2));
                $('#totalMasIVA').val(totalMasIVA.toFixed(2));
            }

            // Evento click para el botón "Agregar Producto"
            $('#agregar-producto').click(function() {

                definirPrecio();

            });

            // Evento click para el botón "Agregar a la Lista" en el modal
            $('#agregar-lista').click(function() {
                // Crear un arreglo para almacenar los productos del modal
                var productosModal = [];

                // Recorrer las filas de la tabla en el modal y obtener los datos
                $('#jsonTable tbody tr').each(function() {
                    var numeroLote = $(this).find('.numero-lote').text();
                    var cantidad = $(this).find('.cantidad-comprada').text();
                    var precioUnitario = ($(this).find('.precio-sugerido').val());
                    var cantidadDispo = ($(this).find('.cantidad-disponible').text());
                    // Agregar el producto actual al arreglo de productosModal
                    productosModal.push({
                        numeroLote: numeroLote,
                        cantidad: cantidad,
                        precioUnitario: precioUnitario,
                        cantidadDisponible: cantidadDispo

                    });
                });
                agregarProductos(productosModal);
                // Cierra el modal si es necesario
                $('#jsonModal').modal('hide');
                habilitarDeshabilitarBotonFinalizar();

            });


            // Evento change para los precios unitarios de productos en la lista
            $('#lista-productos').on('change', '.precio-unitario-editable', function() {
                var index = $(this).closest('tr').index();
                var nuevoPrecio = parseFloat($(this).val());
                var precioOriginal = listaProductos[index].precioUnitario; // Precio original del producto

                if (!isNaN(nuevoPrecio) && nuevoPrecio >= 0) {
                    if (nuevoPrecio < precioOriginal) {
                        // Si el nuevo precio es menor al precio original, mostrar un mensaje de error
                        AlertMessage("El precio unitario no puede ser menor al precio original (" +
                            precioOriginal.toFixed(2) + ").", "error");
                        // Restablecer el valor al precio original
                        $(this).val(precioOriginal.toFixed(2));
                    } else {
                        listaProductos[index].precioUnitario = nuevoPrecio;
                        listaProductos[index].subtotal = listaProductos[index].cantidad * nuevoPrecio;
                        actualizarListaProductos();
                        calcularMontoTotal();
                        calcularIVA();
                        calcularTotalMasIVA();
                    }
                } else {
                    AlertMessage("El precio unitario debe ser un número mayor o igual a cero.", "error");
                    // Restablecer el valor al precio original
                    $(this).val(precioOriginal.toFixed(2));
                }
            });


            // Evento click para eliminar un producto de la lista
            $('#lista-productos').on('click', '.eliminar-producto', function() {
                var index = $(this).data('index');
                var producto = listaProductos[index]; // Obtener el producto a eliminar

                // Mostrar cuadro de diálogo SweetAlert
                Swal.fire({
                    title: 'Eliminar Producto',
                    text: '¿Desea eliminar el producto "' + producto.nombre + '" de la lista?',
                    icon: 'warning',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, eliminar el producto
                        listaProductos.splice(index, 1);
                        actualizarListaProductos();
                        calcularMontoTotal();
                        calcularIVA();
                        calcularTotalMasIVA();
                        habilitarDeshabilitarBotonFinalizar();
                    }
                });
            });






            // Evento change para las opciones de precio
            $('input[type=radio][name=opcionPrecio]').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'cambiarManual') {
                    $('#porcentajeGanancia').prop('disabled', false)
                    $('.precio-sugerido').prop('readonly', false);
                    $('.precio-sugerido').css('opacity', 1);


                } else if (selectedOption === 'mantenerFijo') {
                    $('#porcentajeGanancia').prop('disabled', true)
                    $('.precio-sugerido').prop('readonly', true);
                    $('.precio-sugerido').css('opacity', 0.2);
                    // Si la opción es "mantenerFijo," establecer los precios sugeridos en el 10%
                    var porcentajeSeleccionado = 0.10; // 10%
                    $('.precio-sugerido').each(function() {
                        var precioUnitario = parseFloat($(this).closest('tr').find(
                            '.precio-unitario').text());
                        var nuevoPrecioSugerido = precioUnitario + (precioUnitario *
                            porcentajeSeleccionado);
                        $(this).val(nuevoPrecioSugerido.toFixed(2));
                    });

                } else if (selectedOption === 'mismoPrecio') {

                    // Obtener el precio sugerido más alto de la tabla
                    var precioSugeridoMasAlto = 0;
                    $('#jsonTable tbody tr').each(function() {
                        var precioSugerido = parseFloat($(this).find('td:last input').val());
                        if (precioSugerido > precioSugeridoMasAlto) {
                            precioSugeridoMasAlto = precioSugerido;
                        }
                    });

                    // Establecer el precio sugerido más alto para todos los productos
                    $('#jsonTable tbody tr').each(function() {
                        $(this).find('td:last input').val(precioSugeridoMasAlto);
                    });
                    $('#porcentajeGanancia').prop('disabled', true)
                    $('.precio-sugerido').prop('readonly', true);
                    $('.precio-sugerido').css('opacity', 0.2);
                }
            });

            // Evento change para el select de porcentaje de ganancia
            $('#porcentajeGanancia').change(function() {
                var selectedPorcentaje = parseFloat($(this).val());

                // Actualizar el precio sugerido por fila
                $('#jsonTable tbody tr').each(function() {
                    var precioUnitario = parseFloat($(this).find('.precio-unitario').text());
                    var nuevoPrecioSugerido = precioUnitario + (precioUnitario *
                        selectedPorcentaje);
                    $(this).find('.precio-sugerido').val(nuevoPrecioSugerido.toFixed(2));
                });
            });

         
                // Agregar un evento change a los elementos input con clase "precio-sugerido"
          // Agregar un evento change a los elementos input con clase "precio-sugerido"
            $('#jsonModal').on('change', 'input.precio-sugerido', function() {
                var $input = $(this);
                var precioSugerido = parseFloat($input.val());
                var precioCompra = parseFloat($input.closest('tr').find('.precio-unitario').text());

                if (precioSugerido < 0) {
                    Swal.fire({
                        title: 'Precio inválido',
                        text: 'El precio no puede ser menor que cero. Por favor, ingresa un valor válido.',
                        icon: 'error',
                    });
                    $input.val(precioCompra.toFixed(2)); // Restablecer al precio de compra
                } else if (precioSugerido < precioCompra) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'El precio sugerido es menor que el precio de compra. ¿Deseas continuar?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'No, corregir',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // El usuario confirmó, actualizar el valor del input
                            var nuevoPrecioSugerido = parseFloat(Swal.getInput().value);
                            $input.val(nuevoPrecioSugerido.toFixed(2));
                        } else {
                            // El usuario canceló, restablece el precio sugerido al precio de compra
                            $input.val(precioCompra.toFixed(2));
                        }
                    });
                }
            });

              // Agregar un evento change a los elementos input con clase "precio-sugerido"
              $('#jsonModal').on('focus', 'input.precio-sugerido', function() {
                var $input = $(this);
                var precioSugerido = parseFloat($input.val());
                var precioCompra = parseFloat($input.closest('tr').find('.precio-unitario').text());

                if (precioSugerido < 0) {
                    Swal.fire({
                        title: 'Precio inválido',
                        html: 'El precio no puede ser menor que cero. El precio de compra es ' + precioCompra.toFixed(2) + '.<br>Por favor, ingresa un valor válido.',
                        icon: 'error',
                    });
                    $input.val(precioCompra.toFixed(2)); // Restablecer al precio de compra
                } else if (precioSugerido < precioCompra) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'El precio sugerido es menor que el precio de compra. ¿Deseas continuar?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'No, corregir',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // El usuario confirmó, actualizar el valor del input
                            var nuevoPrecioSugerido = parseFloat(Swal.getInput().value);
                            $input.val(nuevoPrecioSugerido.toFixed(2));
                        } else {
                            // El usuario canceló, restablece el precio sugerido al precio de compra
                            $input.val(precioCompra.toFixed(2));
                        }
                    });
                }
            });

          






            $('#finalizar-venta').click(function() {
                // Mostrar cuadro de diálogo SweetAlert
                Swal.fire({
                    title: 'Finalizar Venta',
                    text: '¿Desea finalizar la venta?',
                    icon: 'warning',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Sí'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, continuar con el proceso
                        // Antes de enviar el formulario, actualizar los campos ocultos con los valores
                        $('#monto_total').val(montoTotal.toFixed(2));
                        $('#ivaTotal').val(ivaTotal.toFixed(2));
                        $('#totalMasIVA').val(totalMasIVA.toFixed(2));

                        // Antes de enviar el formulario, actualizar el campo oculto con la lista de productos
                        $('#lista_productos_input').val(JSON.stringify(listaProductos));

                        // Enviar el formulario
                        $('form').submit();
                    }
                });
            });

        });
    </script>
@endsection
