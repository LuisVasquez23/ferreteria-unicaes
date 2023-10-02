@extends('layouts/dashboard')
@section('title', 'Crear Compra')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Crear Compra</h5>
    <div class="card-body">
        <form action="{{ route('compras.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Columna para el número de factura -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="numero_factura" class="form-label">Número de Factura:</label>
                        <input type="number" class="form-control" id="numero_factura" name="numero_factura" required value="{{ old('numero_factura') }}">
                    </div>
                </div>

                <!-- Columna para seleccionar el período -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="periodo_id" class="form-label">Período: *</label>
                        <select name="periodo_id" id="periodo_id" class="form-select">
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
                    </div>
                </div>

                <!-- Columna para seleccionar el proveedor -->
            </div>

            <div class="row">
                <!-- Columna para seleccionar el producto -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="producto_id" class="form-label">Producto:</label>
                        <select class="form-select" id="producto_id" name="producto_id" required>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }} - Proveedor: {{  $producto->usuario->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Columna para la cantidad de producto -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1">
                    </div>
                </div>

                <!-- Columna para el precio unitario -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario:</label>
                        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" required min="0.01">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Columna para el botón "Agregar Producto" -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <button type="button" class="btn btn-success" id="agregar-producto">Agregar Producto</button>
                    </div>
                </div>
            </div>

            <!-- Campo oculto para la lista de productos -->
            <input type="hidden" name="lista_productos" id="lista_productos_input" value="">
            <input type="hidden" name="monto_total" id="monto_total" value="">
            <input type="hidden" name="ivaToatl" id="ivaTotal" value="">
            <input type="hidden" name="totalFin" id="totalFin" value="">
        </form>

        <!-- Lista de productos seleccionados -->
        <div class="mt-4">
            <h5>Productos Seleccionados:</h5>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre Producto</th>
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
                    <label for="totalFinShow" class="form-label">Total + IVA (13%):</label>
                    <input type="text" class="form-control" id="totalFinShow" readonly>
                </div>
            </div>

            <!-- Columna para el botón "Finalizar Compra" -->
            <div class="col-md-4">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="finalizar-compra">Finalizar Compra</button>
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
            var numeroFactura = $('#numero_factura').val();

            // Validaciones adicionales
            if (cantidad <= 0 || precioUnitario <= 0) {
                alert('La cantidad y el precio unitario deben ser mayores que cero.');
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
                    numeroFactura: numeroFactura
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
        }

        // Función para actualizar la lista de productos en la vista
        function actualizarListaProductos() {
            var listaHtml = '';
            listaProductos.forEach(function(producto, index) {
                listaHtml += '<tr>';
                listaHtml += '<td>' + producto.nombre + '</td>';
                listaHtml += '<td><input type="number" class="form-control cantidad-editable" value="' + producto.cantidad + '"></td>';
                listaHtml += '<td><input type="number" class="form-control precio-unitario-editable" step="0.01" value="' + producto.precioUnitario.toFixed(2) + '"></td>';
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
             // Antes de enviar el formulario, actualizar los campos ocultos con los valores
            $('#monto_total').val(montoTotal.toFixed(2));
            $('#iva').val(ivaTotal.toFixed(2));
            calcularTotalMasIVA()

            // Antes de enviar el formulario, actualizar el campo oculto con la lista de productos
            $('#lista_productos_input').val(JSON.stringify(listaProductos));
            
            $('form').submit();

        });
    });
</script>
@endsection
