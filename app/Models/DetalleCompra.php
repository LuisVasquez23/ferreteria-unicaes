<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compras';
    protected $primaryKey = 'detalle_compra_id';

    protected $casts = [
        'cantidad' => 'int',
        'numero_lote' => 'int', // Agregado el campo 'numero_lote'
        'precio' => 'float',
        'compra_id' => 'int',
        'producto_id' => 'int',
        'fecha_vencimiento' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_bloqueo' => 'datetime',
        'created_at' => 'datetime', // Agregar esta línea
        'updated_at' => 'datetime'
    ];

    protected $fillable = [
        'cantidad',
        'numero_lote', // Agregado el campo 'numero_lote'
        'fecha_vencimiento',
        'precio',
        'compra_id',
        'producto_id',
        'creado_por',
        'fecha_creacion',
        'actualizado_por',
        'fecha_actualizacion',
        'bloqueado_por',
        'fecha_bloqueo',
        'created_at', // Agregar esta línea
        'updated_at'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'coprador_id');
    }
}
