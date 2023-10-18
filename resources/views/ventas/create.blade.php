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
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="numero_factura" class="form-label">Número de Factura:</label>
                        <input type="number" class="form-control" id="numero_factura" name="numero_factura" required value="{{ old('numero_factura') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente:</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                        @foreach ($clientes as $clienteId => $clienteNombre)
                            <option value="{{ $clienteId }}">{{ $clienteNombre }} id: {{$clienteId}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
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


            </div>

            <div class="row">
                <!-- Columna para seleccionar el producto -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="producto_id" class="form-label">Producto:</label>
                        <select class="form-select" id="producto_id" name="producto_id" required>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->producto_id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }} - Proveedor: {{ $producto->usuario->nombres}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Columna para la cantidad de producto -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" step="1" required min="1">
                        <div class="invalid-feedback" id="error-cantidad"></div> <!-- Agregado para mostrar el mensaje de error -->
                    </div>
                </div>

                <!-- Columna para el precio unitario -->
                <div class="col-md-4">
                    <div class="mb-3 pt-4">
                        <button type="button" class="btn btn-success" id="agregar-producto">Agregar Producto</button>
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
            <div class="col-md-4">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="finalizar-venta" disabled >Finalizar Venta</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- HTML para el modal Bootstrap -->
<div class="modal fade" id="jsonModal" tabindex="-1" role="dialog" aria-labelledby="jsonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Agregando la clase modal-lg para hacerlo ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jsonModalLabel">Seleccion precio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                <label>Opciones:</label>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="cambiarPrecio" name="opcionPrecio" value="cambiar">
                    <label class="form-check-label" for="cambiarPrecio">Cambiar precio sugerido</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="mantenerPrecio" name="opcionPrecio" value="mantener">
                    <label class="form-check-label" for="mantenerPrecio">Mantener precio</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="venderMismoPrecio" name="opcionPrecio" value="vender">
                    <label class="form-check-label" for="venderMismoPrecio">Vender al mismo precio</label>
                </div>
            </div>

                <table id="jsonTable" class="table table-bordered">
                    <thead>     
                        <tr>
                            <th>Número de Lote</th>
                            <th>Cantidad Disponible</th>
                            <th>Cantidad Comprada</th>
                            <th>Precio de compra</th>
                            <th>Precio Sugerido</th> <!-- Nueva columna con inputs -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se llenará la tabla con datos desde JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="pb-5">
                <button type="button" id="agregar-lista" class="btn btn-primary">Agregar a la Lista</button>

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
                $('#finalizar-venta').prop('disabled', false);
            } else {
                $('#finalizar-venta').prop('disabled', true);
            }
        }
         // Habilitar o deshabilitar los campos de precio sugerido
        function habilitarPrecioSugerido(habilitar) {
            if (habilitar) {
                $('#precio_sugerido').prop('readonly', false);
            } else {
                $('#precio_sugerido').prop('readonly', true);
            }
        }
        //desahbilitar finalizar venta:
       
        //entrar en modal para precios
        function definirPrecio(){
            var productoId = $('#producto_id').val();
            var productoNombre = $('#producto_id option:selected').text().split(' - Precio')[0];
            var cantidad = parseInt($('#cantidad').val());
            var precioUnitario = parseFloat($('#precio_unitario').val());
            var numeroFactura = $('#numero_factura').val();
            var cantidadNueva = 0;
            

            // Validación de número de factura no vacío
            if (isNaN(cantidad) || cantidad <= 0) {
                // Mostrar alerta personalizada
                AlertMessage('La cantidad debe ser un número mayor que cero', 'error');
                return;
            }

            // Validación de cantidad no vacía
            if (isNaN(cantidad) || cantidad <= 0) {
                // Mostrar alerta personalizada
                AlertMessage('La cantidad debe ser un número mayor que cero', 'error');
                return;
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
                alert(cantidadTotal);
                    $.ajax({
                    type: 'POST',
                    url: '{{ route('verificar-cantidad') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        producto_id: productoId,
                        cantidad: cantidadTotal
                    },
                    success: function(response) {
                        if (response.suficiente) {

                            // Limpia la tabla antes de agregar nuevos datos
                                $('#jsonTable tbody').empty();

                                // Recorre los lotes disponibles en la respuesta JSON y crea filas de tabla
                                response.lotesDisponibles.forEach(function(lote) {
                                    var precioUnitario = parseFloat(lote.precio_unitario);
                                    var precioConGanancia = precioUnitario + (precioUnitario * 0.10); // Calcula el precio con un 10% de ganancia

                                    // Agrega input en lugar de texto
                                    var newRow = '<tr>' +
                                                    '<td class="numero-lote">' + lote.numero_lote + '</td>' +
                                                    '<td class="cantidad-disponible">' + lote.cantidad_disponible + '</td>' +
                                                    '<td class="cantidad-comprada">' + lote.cantidad_comprada + '</td>' +
                                                    '<td class="precio-unitario">' + precioUnitario.toFixed(2) + '</td>' +
                                                    '<td>' +
                                                        '<input type="number" class="precio-sugerido" value="' + precioConGanancia.toFixed(2) + '" readonly>' +
                                                    '</td>' +
                                                    '</tr>';


                                    $('#jsonTable tbody').append(newRow);
                                });

                                // Muestra el modal
                                $('#jsonModal').modal('show');
                        }else {
                                // Si no hay suficiente cantidad, muestra un mensaje al usuario
                                AlertMessage('No hay suficiente cantidad de este producto.', 'error');
                        }

                    }
                })
            }else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('verificar-cantidad') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        producto_id: productoId,
                        cantidad: cantidad
                    },
                    success: function(response) {
                        if (response.suficiente) {

                            // Limpia la tabla antes de agregar nuevos datos
                                $('#jsonTable tbody').empty();

                                // Recorre los lotes disponibles en la respuesta JSON y crea filas de tabla
                                response.lotesDisponibles.forEach(function(lote) {
                                    var precioUnitario = parseFloat(lote.precio_unitario);
                                    var precioConGanancia = precioUnitario + (precioUnitario * 0.10); // Calcula el precio con un 10% de ganancia

                                    // Agrega input en lugar de texto
                                    var newRow = '<tr>' +
                                                    '<td class="numero-lote">' + lote.numero_lote + '</td>' +
                                                    '<td class="cantidad-disponible">' + lote.cantidad_disponible + '</td>' +
                                                    '<td class="cantidad-comprada">' + lote.cantidad_comprada + '</td>' +
                                                    '<td class="precio-unitario">' + precioUnitario.toFixed(2) + '</td>' +
                                                    '<td>' +
                                                        '<input type="number" class="precio-sugerido" value="' + precioConGanancia.toFixed(2) + '" readonly>' +
                                                    '</td>' +
                                                    '</tr>';


                                    $('#jsonTable tbody').append(newRow);
                                });

                                // Muestra el modal
                                $('#jsonModal').modal('show');
                        }else {
                                // Si no hay suficiente cantidad, muestra un mensaje al usuario
                                AlertMessage('No hay suficiente cantidad de este producto.', 'error');
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
                alert(cantidadDipo);
                var precioUnitario = parseFloat(productoModal.precioUnitario);
                console.log('precioUnitario:', precioUnitario);

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

                // Calcular el subtotal
                var subtotal = cantidad * precioUnitario;

                // Buscar si el producto ya existe en la lista
                var productoExistente = listaProductos.find(function (producto) {
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
        }



        // Función para actualizar la lista de productos en la vista
        function actualizarListaProductos() {
            var listaHtml = '';
            listaProductos.forEach(function(producto, index) {
                listaHtml += '<tr>';
                listaHtml += '<td>' + producto.nombre + '</td>';
                listaHtml +='<td>'+ producto.numeroLote +'</td>';
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
            }else{
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
                calcularIVA();
                calcularTotalMasIVA();
            }else{
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
            habilitarDeshabilitarBotonFinalizar();

        });
        // Evento change para las opciones de precio
        $('input[type=radio][name=opcionPrecio]').change(function() {
                    var selectedOption = $(this).val();
                    if (selectedOption === 'cambiar') {
                        habilitarPrecioSugerido(true);
                    } else if(selectedOption ==='matener') {
                        habilitarPrecioSugerido(false);
                    } else if (selectedOption ==='vender'){
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
                    }
        });
        // Evento click para finalizar la venta
        $('#finalizar-venta').click(function() {
            // Antes de enviar el formulario, actualizar los campos ocultos con los valores
            $('#monto_total').val(montoTotal.toFixed(2));
            $('#ivaTotal').val(ivaTotal.toFixed(2));
            $('#totalMasIVA').val(totalMasIVA.toFixed(2));

            // Antes de enviar el formulario, actualizar el campo oculto con la lista de productos
            $('#lista_productos_input').val(JSON.stringify(listaProductos));

            $('form').submit();
        });
    });
</script>
@endsection
