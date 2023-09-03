<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Usuario
 * 
 * @property int $usuario_id
 * @property string|null $dui
 * @property string|null $nombres
 * @property string|null $apellidos
 * @property string|null $telefono
 * @property string|null $departamento
 * @property string|null $municipio
 * @property string|null $direccion
 * @property string|null $fecha_nacimiento
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Compra[] $compras
 * @property Collection|DetalleRole[] $detalle_roles
 * @property Collection|Producto[] $productos
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Usuario extends Authenticatable
{

	use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'usuarios';
	protected $primaryKey = 'usuario_id';

	protected $casts = [
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'dui',
		'nombres',
		'apellidos',
		'telefono',
		'departamento',
		'municipio',
		'direccion',
		'fecha_nacimiento',
		'email',
		'password',
		'remember_token',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function compras()
	{
		return $this->hasMany(Compra::class, 'vendedor_id');
	}

	public function detalle_roles()
	{
		return $this->hasMany(DetalleRole::class, 'usuario_id');
	}

	public function productos()
	{
		return $this->hasMany(Producto::class, 'proveedor_id');
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'vendedor_id');
	}
}
