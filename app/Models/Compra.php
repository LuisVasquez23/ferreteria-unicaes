<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'compra_id';

    protected $fillable = [
        'monto',
        'numerosfactura',
        'periodo_id',
        'comprador_id',
        'creado_por',
        'fecha_creacion',
        'actualizado_por',
        'fecha_actualizacion',
        'bloqueado_por',
        'fecha_bloqueo',
    ];

    protected $casts = [
        'monto' => 'float',
        'periodo_id' => 'int',
        'comprador_id' => 'int',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'fecha_bloqueo' => 'datetime',
    ];

    public function comprador()
    {
        return $this->belongsTo(Usuario::class, 'comprador_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function detalle_compras()
    {
        return $this->hasMany(DetalleCompra::class, 'compra_id');
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
