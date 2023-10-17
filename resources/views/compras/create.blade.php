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
                        <input type="number" class="form-control @error('numerosfactura') is-invalid @enderror" id="numerosfactura" name="numerosfactura" required value="{{ old('numerosfactura') }}">
                        @error('numerosfactura')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Columna para seleccionar el período -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="periodo_id" class="form-label">Período: *</label>
                        <select name="periodo_id" id="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror">
                            @if($periodos->isEmpty())
                                <option value="" disabled selected>No se encontraron períodos</option>
                            @else
                                @foreach($periodos as $periodo_id => $fecha_inicio)
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
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento: *</label>
                        <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror" id="fecha_vencimiento" name="fecha_vencimiento" required>
                        @error('fecha_vencimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="row">
                <!-- Columna para seleccionar el producto -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="producto_id" class="form-label">Producto: *</label>
                        <select class="form-select @error('producto_id') is-invalid @enderror" id="producto_id" name="producto_id" required>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }} - Proveedor: {{  $producto->usuario->nombres }}</option>
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
                        <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" required min="1">
                        @error('cantidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Columna para el precio unitario -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario: *</label>
                        <input type="number" class="form-control @error('precio_unitario') is-invalid @enderror" id="precio_unitario" name="precio_unitario" step="0.01" required min="0.01">
                        @error('precio_unitario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                
            </div>

            <div class="row">
                <!-- Columna para el botón "Agregar Producto" -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div class="mb-3 mt-2">
                        <button type="button" class="btn btn-success" id="agregar-producto">Agregar Producto</button>
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
                    <label for="totalFinShow" class="form-label">Total + IVA (13%):</label>
                    <input type="text" class="form-control" id="totalFinShow" readonly>
                </div>
            </div>

            <!-- Columna para el botón "Finalizar Compra" -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <div class="mb-3 mt-2">
                    <button type="button" class="btn btn-primary" id="finalizar-compra" disabled>Finalizar Compra</button>
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
        var totalMasIVA  = 0;

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
                    return;
                }
                if (isNaN(cantidad) || cantidad <= 0) {
                    // Mostrar alerta personalizada
                    AlertMessage('La cantidad debe se un número mayor que cero', 'error');
                    return;
                }

                if (isNaN(precioUnitario) || precioUnitario <= 0) {
                    // Mostrar alerta personalizada
                    AlertMessage("El precio unitario debe ser un número mayor que cero.", "error");
                    return;
                }
                
                if (!fechaVencimiento || fechaVencimiento.trim() === "") {
                    // Mostrar alerta personalizada
                    AlertMessage("Por favor, ingrese un fecha de vencimiento  válida.", "error");
                    return;
                }

            // Calcular el subtotal del producto
            var subtotal = cantidad * precioUnitario;

            // Verificar si el producto ya está en la lista y actualizar su cantidad
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
                    fechaVencimiento: fechaVencimiento // Agregar la fecha de vencimiento
                });
            }

            // Actualizar la lista de productos en la vista
            actualizarListaProductos();

            // Calcular el monto total
            calcularMontoTotal();

            // Calcular el IVA
            calcularIVA();

            // Calcular el Total + IVA
            calcularTotalMasIVA();

            // Limpiar los campos de cantidad y precio unitario
            $('#cantidad').val('');
            $('#precio_unitario').val('');
            $('#fecha_vencimiento').val('');

            $('#finalizar-compra').prop('disabled', false);

        }

        // Función para actualizar la lista de productos en la vista
        function actualizarListaProductos() {
            var listaHtml = '';
            listaProductos.forEach(function(producto, index) {
                listaHtml += '<tr>';
                listaHtml += '<td>' + producto.nombre + '</td>';
                listaHtml += '<td><input type="number" class="form-control cantidad-editable" value="' + producto.cantidad + '"></td>';
                listaHtml += '<td><input type="number" class="form-control precio-unitario-editable" step="0.01" value="' + producto.precioUnitario.toFixed(2) + '"></td>';
                listaHtml += '<td>' + producto.fechaVencimiento + '</td>';
                listaHtml += '<td>' + producto.subtotal.toFixed(2) + '</td>';
                listaHtml += '<td><button type="button" class="btn btn-danger eliminar-producto" data-index="' + index + '">Eliminar</button></td>';
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
        });

        // Evento change para las cantidades de productos en la lista
        $('#lista-productos').on('change', '.cantidad-editable', function() {
            var index = $(this).closest('tr').index();
            var nuevaCantidad = parseInt($(this).val());
            if (!isNaN(nuevaCantidad)) {
                listaProductos[index].cantidad = nuevaCantidad;
                listaProductos[index].subtotal = nuevaCantidad * listaProductos[index].precioUnitario;
                actualizarListaProductos();
                calcularMontoTotal();
                calcularIVA();
                calcularTotalMasIVA();
            }
        });

        // Evento change para los precios unitarios de productos en la lista
        $('#lista-productos').on('change', '.precio-unitario-editable', function() {
            var index = $(this).closest('tr').index();
            var nuevoPrecio = parseFloat($(this).val());
            if (!isNaN(nuevoPrecio)) {
                listaProductos[index].precioUnitario = nuevoPrecio;
                listaProductos[index].subtotal = listaProductos[index].cantidad * nuevoPrecio;
                actualizarListaProductos();
                calcularMontoTotal();
                calcularTotalMasIVA();
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

        // Antes de enviar el formulario, actualizar los campos ocultos con los valores
        $('#monto_total').val(montoTotal.toFixed(2));
        $('#ivaTotal').val(ivaTotal.toFixed(2));
        $('#totalFin').val(totalMasIVA.toFixed(2));

        // Convertir la lista de productos a JSON y actualizar el campo oculto
        $('#lista_productos_input').val(JSON.stringify(listaProductos));

        // Enviar el formulario
        $('form').submit();
    });



    });
</script>
@endsection
