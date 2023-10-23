@extends('layouts/dashboard')
@section('title', 'Ingresar Compra')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Ingresar Compra</h5>
        <div class="card-body">
            <form action="{{ route('compras.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Columna para el número de factura -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="numerosfactura" class="form-label">Número de Factura: *</label>
                            <input type="number" class="form-control @error('numerosfactura') is-invalid @enderror"
                                id="numerosfactura" name="numerosfactura" required value="{{ old('numerosfactura') }}">
                            @error('numerosfactura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="mensaje_errorF" style="color: red;"></div>
                        </div>
                    </div>

                    <!-- Columna para seleccionar el período -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="periodo_id" class="form-label">Período: *</label>
                            <select name="periodo_id" id="periodo_id"
                                class="form-select @error('periodo_id') is-invalid @enderror">
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
                            @error('periodo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    <!-- Columna para la fecha de vencimiento -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                            <div class="input-group">
                                <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                                    id="fecha_vencimiento" name="fecha_vencimiento" disabled>
                                <div class="input-group-text">
                                    <input type="checkbox" id="habilitar_fecha" class="form-check-input"
                                        aria-label="Habilitar Fecha">
                                    <label class="form-check-label" for="habilitar_fecha">Habilitar Fecha</label>
                                </div>
                            </div>
                            @error('fecha_vencimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="mensaje_errorFe" style="color: red;"></div>

                        </div>
                    </div>


                </div>

                <div class="row">
                    <!-- Columna para seleccionar el producto -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto: *</label>
                            <select class="form-select @error('producto_id') is-invalid @enderror" id="producto_id"
                                name="producto_id" required>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">
                                        {{ $producto->nombre }} - Proveedor: {{ $producto->usuario->nombres }}
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Columna para la cantidad de producto -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad: *</label>
                            <input type="number" class="form-control @error('cantidad') is-invalid @enderror"
                                id="cantidad" name="cantidad" required min="1">
                            @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="mensaje_errorC" style="color: red;"></div>

                        </div>
                    </div>

                    <!-- Columna para el precio unitario -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="precio_unitario" class="form-label">Precio Unitario: *</label>
                            <input type="number" class="form-control @error('precio_unitario') is-invalid @enderror"
                                id="precio_unitario" name="precio_unitario" step="0.01" required min="0.01">
                            @error('precio_unitario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="mensaje_errorP" style="color: red;"></div>

                        </div>
                    </div>


                </div>

                <div class="row">
                    <!-- Columna para el botón "Agregar Producto" -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <div class="mb-3 mt-2">
                            <button type="button" class="btn btn-success" id="agregar-producto">Agregar
                                Producto</button>
                            <a href="{{ route('compras') }}" class="btn btn-dark me-1 ms-2">Regresar</a>

                        </div>
                    </div>
                </div>

                <!-- Campo oculto para la lista de productos -->
                <input type="hidden" name="lista_productos" id="lista_productos_input" value="">
                <input type="hidden" name="monto_total" id="monto_total" value="">
                <input type="hidden" name="ivaTotal" id="ivaTotal" value="">
                <input type="hidden" name="totalFin" id="totalFin" value="">
            </form>

            <!-- Lista de productos seleccionados -->
            <div class="mt-4">
                <h5 class="mb-3">Productos Seleccionados:</h5>
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Fecha de Vencimiento</th>
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
                        <label for="monto_totalShow" class="form-label">Monto total:</label>
                        <input type="text" class="form-control" id="monto_totalShow" readonly disabled>
                    </div>
                </div>

                <!-- Columna para mostrar el IVA -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="ivaShow" class="form-label">IVA (13%):</label>
                        <input type="text" class="form-control" id="ivaShow" readonly disabled>
                    </div>
                </div>

                <!-- Columna para mostrar el total + IVA -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="totalFinShow" class="form-label">Total + IVA (13%):</label>
                        <input type="text" class="form-control" id="totalFinShow" readonly disabled>
                    </div>
                </div>

                <!-- Columna para el botón "Finalizar Compra" -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div class="mb-3 mt-2">
                        <button type="button" class="btn btn-primary" id="finalizar-compra" disabled>Finalizar
                            Compra</button>
                    </div>
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

            //desahbilitar finalizar compra:
            function habilitarDeshabilitarBotonFinalizar() {
                if (listaProductos.length > 0) {
                    $('#finalizar-compra').prop('disabled', false);
                } else {
                    $('#finalizar-compra').prop('disabled', true);
                }
            }
            // Función para habilitar o deshabilitar el campo de fecha de vencimiento
            function habilitarFechaVencimiento() {
                var checkbox = $('#habilitar_fecha');
                var fechaVencimientoInput = $('#fecha_vencimiento');
                fechaVencimientoInput.prop('disabled', !checkbox.is(':checked'));
            }

            // Evento para cambiar la habilitación del campo de fecha de vencimiento
            $('#habilitar_fecha').change(function() {
                habilitarFechaVencimiento();
            });
            // Función para agregar producto a la lista
            function agregarProducto() {
                var productoId = $('#producto_id').val();
                var productoNombre = $('#producto_id option:selected').text().split(' - Precio')[0];
                var cantidad = parseInt($('#cantidad').val());
                var precioUnitario = parseFloat($('#precio_unitario').val());
                var fechaVencimiento = $('#fecha_vencimiento').val();
                var numeroFactura = $('#numerosfactura').val();

                if (!numeroFactura || numeroFactura.trim() === "") {
                    // Mostrar alerta personalizada
                    AlertMessage("Por favor, ingrese un número de factura válido.", "error");
                    $('#numerosfactura').css('border', '1px solid red');
                    $('#mensaje_errorF').text('Este no es un valor valido para la factura.');

                    return;
                } else {
                    $('#numerosfactura').css('border', '1px solid #ccc');
                    $('#mensaje_errorF').text('');
                }
                if (isNaN(cantidad) || cantidad <= 0) {
                    // Mostrar alerta personalizada
                    AlertMessage('La cantidad debe ser un número mayor que cero', 'error');
                    $('#cantidad').css('border', '1px solid red');
                    $('#mensaje_errorC').text('Ingresa un valor valida para cantidad.');

                    return;
                } else {
                    $('#cantidad').css('border', '1px solid #ccc');
                    $('#mensaje_errorC').text('');
                }
                if (isNaN(precioUnitario) || precioUnitario <= 0) {
                    // Mostrar alerta personalizada
                    AlertMessage("El precio unitario debe ser un número mayor que cero.", "error");
                    $('#precio_unitario').css('border', '1px solid red');
                    $('#mensaje_errorP').text('Ingrese un valor valido para el precio unitario.');

                    return;
                } else {
                    $('#precio_unitario').css('border', '1px solid #ccc');
                    $('#mensaje_errorP').text('');
                }
                // Validar la fecha de vencimiento solo si el checkbox está seleccionado
                var fechaVencimientoCheckbox = $('#habilitar_fecha');

                if (fechaVencimientoCheckbox.is(':checked')) {
                    if (!fechaVencimiento || fechaVencimiento.trim() === "") {
                        // Mostrar alerta personalizada
                        AlertMessage("Por favor, ingrese una fecha de vencimiento válida.", "error");
                        $('#mensaje_errorFe').text('Ingrese una fecha');

                        return;
                    } else {
                        $('#mensaje_errorFe').text('');
                    }

                    // Validar que la fecha de vencimiento no sea menor que la fecha actual
                    var fechaActual = new Date();
                    var fechaVencimientoDate = new Date(fechaVencimiento);

                    if (fechaVencimientoDate < fechaActual) {
                        // Mostrar alerta personalizada
                        AlertMessage("La fecha de vencimiento no puede ser menor que la fecha actual.", "error");
                        $('#mensaje_errorFe').text('Ingrese una fecha mayor a hoy');

                        return;
                    } else {
                        $('#mensaje_errorFe').text('');
                    }
                }

                // Calcular la diferencia en milisegundos entre la fecha de vencimiento y la fecha actual
                var diferenciaFechas = fechaVencimientoDate - fechaActual;

                // Calcular el número de días enteros y el número de horas restantes
                var diasRestantes = Math.floor(diferenciaFechas / (1000 * 60 * 60 *
                    24)); // Convertir la diferencia a días y redondear hacia abajo
                var horasRestantes = Math.floor((diferenciaFechas % (1000 * 60 * 60 * 24)) / (1000 * 60 *
                    60)); // Convertir el residuo a horas y redondear hacia abajo

                // Combinar días y horas en el mensaje de advertencia
                var mensajeAdvertencia = "El producto se vence en " + diasRestantes + " día(s) y " +
                    horasRestantes + " hora(s). ¿Aún desea agregarlo?";

                if (diasRestantes <= 7) {
                    // Mostrar la alerta de SweetAlert para confirmación
                    Swal.fire({
                        title: 'Advertencia',
                        text: mensajeAdvertencia,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, agregar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Si el usuario confirma, agregar el producto a la lista
                            var subtotal = cantidad * precioUnitario;
                            var productoExistente = listaProductos.find(function(producto) {
                                return producto.productoId == productoId;
                            });
                            if (productoExistente) {
                                productoExistente.cantidad += cantidad;
                                productoExistente.subtotal += subtotal;
                                productoExistente.precioUnitario = precioUnitario;
                            } else {
                                listaProductos.push({
                                    productoId: productoId,
                                    proveedorId: $('#proveedor_id').val(),
                                    nombre: productoNombre,
                                    cantidad: cantidad,
                                    precioUnitario: precioUnitario,
                                    subtotal: subtotal,
                                    numeroFactura: numeroFactura,
                                    fechaVencimiento: fechaVencimiento
                                });
                            }
                            actualizarListaProductos();
                            calcularMontoTotal();
                            calcularIVA();
                            calcularTotalMasIVA();
                            $('#cantidad').val('');
                            $('#precio_unitario').val('');
                            $('#fecha_vencimiento').val('');
                            $('#cantidad').css('border', '1px solid #ccc');
                            $('#precio_unitario').css('border', '1px solid #ccc');
                            $('#cantidad').css('border', '1px solid #ccc');
                            $('#numerosfactura').css('border', '1px solid #ccc');
                            $('#mensaje_errorF').text('');
                            $('#mensaje_errorC').text('');
                            $('#mensaje_errorP').text('');
                            $('#mensaje_errorFe').text('');
                            habilitarDeshabilitarBotonFinalizar();


                        }
                    });
                } else {
                    // Agregar el producto a la lista sin mostrar alerta
                    var subtotal = cantidad * precioUnitario;
                    var productoExistente = listaProductos.find(function(producto) {
                        return producto.productoId == productoId;
                    });
                    if (productoExistente) {
                        productoExistente.cantidad += cantidad;
                        productoExistente.subtotal += subtotal;
                        productoExistente.precioUnitario = precioUnitario;
                    } else {
                        listaProductos.push({
                            productoId: productoId,
                            proveedorId: $('#proveedor_id').val(),
                            nombre: productoNombre,
                            cantidad: cantidad,
                            precioUnitario: precioUnitario,
                            subtotal: subtotal,
                            numeroFactura: numeroFactura,
                            fechaVencimiento: fechaVencimiento
                        });
                    }
                    actualizarListaProductos();
                    calcularMontoTotal();
                    calcularIVA();
                    calcularTotalMasIVA();
                    $('#cantidad').val('');
                    $('#precio_unitario').val('');
                    $('#fecha_vencimiento').val('');
                    $('#cantidad').css('border', '1px solid #ccc');
                    $('#precio_unitario').css('border', '1px solid #ccc');
                    $('#cantidad').css('border', '1px solid #ccc');
                    $('#numerosfactura').css('border', '1px solid #ccc');
                    $('#mensaje_errorF').text('');
                    $('#mensaje_errorC').text('');
                    $('#mensaje_errorP').text('');
                    $('#mensaje_errorFe').text('');





                }
            }


            // Función para actualizar la lista de productos en la vista
            function actualizarListaProductos() {
                var listaHtml = '';
                listaProductos.forEach(function(producto, index) {
                    listaHtml += '<tr>';
                    listaHtml += '<td>' + producto.nombre + '</td>';
                    listaHtml += '<td><input type="number" class="form-control cantidad-editable" value="' +
                        producto.cantidad + '"></td>';
                    listaHtml +=
                        '<td><input type="number" class="form-control precio-unitario-editable" step="0.01" value="' +
                        producto.precioUnitario.toFixed(2) + '"></td>';
                    listaHtml += '<td>' + producto.fechaVencimiento + '</td>';
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
                var ivaTotal = montoTotal * ivaPorcentaje;
                $('#ivaShow').val(ivaTotal.toFixed(2));
            }

            // Función para calcular el Total + IVA
            function calcularTotalMasIVA() {
                var ivaPorcentaje = 0.13; // Porcentaje de IVA (13% en este ejemplo)
                var totalMasIVA = montoTotal + (montoTotal * ivaPorcentaje);
                $('#totalFinShow').val(totalMasIVA.toFixed(2));
                $('#totalFin').val(totalMasIVA.toFixed(2));
            }

            // Evento click para el botón "Agregar Producto"
            $('#agregar-producto').click(function() {
                agregarProducto();
                habilitarDeshabilitarBotonFinalizar();
            });

            // Evento change para las cantidades de productos en la lista
            $('#lista-productos').on('change', '.cantidad-editable', function() {
                var index = $(this).closest('tr').index();
                var nuevaCantidad = parseInt($(this).val());

                if (!isNaN(nuevaCantidad) && nuevaCantidad > 0) {
                    listaProductos[index].cantidad = nuevaCantidad;
                    listaProductos[index].subtotal = nuevaCantidad * listaProductos[index].precioUnitario;
                    actualizarListaProductos();
                    calcularMontoTotal();
                    calcularIVA();
                    calcularTotalMasIVA();
                } else {
                    // Mostrar un mensaje de error o realizar alguna otra acción si la cantidad es inválida
                    // En este ejemplo, se mostrará una alerta
                    AlertMessage('La cantidad debe se un número mayor que cero', 'error');
                    // También puedes restablecer el valor a su estado anterior si es necesario
                    $(this).val(listaProductos[index].cantidad);
                }
            });

            // Evento change para los precios unitarios de productos en la lista
            $('#lista-productos').on('change', '.precio-unitario-editable', function() {
                var index = $(this).closest('tr').index();
                var nuevoPrecio = parseFloat($(this).val());

                if (!isNaN(nuevoPrecio) && nuevoPrecio > 0) {
                    listaProductos[index].precioUnitario = nuevoPrecio;
                    listaProductos[index].subtotal = listaProductos[index].cantidad * nuevoPrecio;
                    actualizarListaProductos();
                    calcularMontoTotal();
                    calcularTotalMasIVA();
                } else {
                    // En este ejemplo, se mostrará una alerta
                    AlertMessage("El precio unitario debe ser un número mayor que cero.", "error");
                    // También puedes restablecer el valor a su estado anterior si es necesario
                    $(this).val(listaProductos[index].precioUnitario.toFixed(2));
                }
            });


            // Evento click para eliminar un producto de la lista
            $('#lista-productos').on('click', '.eliminar-producto', function() {
                var index = $(this).data('index');
                listaProductos.splice(index, 1);
                actualizarListaProductos();
                calcularMontoTotal();
                calcularIVA();
                calcularTotalMasIVA();
                habilitarDeshabilitarBotonFinalizar(); // Verificar después de eliminar

            });

            // Evento click para finalizar la compra
            $('#finalizar-compra').click(function() {
                // Verificar que se haya ingresado un número de factura
                var numeroFactura = $('#numerosfactura').val().trim();
                if (!numeroFactura) {
                    // Mostrar alerta personalizada
                    AlertMessage("Por favor, ingrese un número de factura válido.", "error");
                    return;
                }

                // Mostrar cuadro de diálogo SweetAlert
                Swal.fire({
                    title: 'Finalizar Compra',
                    text: '¿Desea finalizar la compra?',
                    icon: 'warning',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Sí',

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar el formulario
                        // Antes de enviar el formulario, actualizar los campos ocultos con los valores
                        $('#monto_total').val(montoTotal.toFixed(2));
                        $('#ivaTotal').val(ivaTotal.toFixed(2));
                        $('#totalFin').val(totalMasIVA.toFixed(2));
                        // Convertir la lista de productos a JSON y actualizar el campo oculto
                        $('#lista_productos_input').val(JSON.stringify(listaProductos));
                        // Enviar el formulario
                        $('form').submit();
                    }
                });
            });





        });
    </script>
@endsection
