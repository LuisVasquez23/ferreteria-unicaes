<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleRole
 * 
 * @property int $detalle_id
 * @property int $role_id
 * @property int $usuario_id
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Role $role
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class DetalleRole extends Model
{

	use HasFactory;

	protected $table = 'detalle_roles';
	protected $primaryKey = 'detalle_id';

	protected $casts = [
		'role_id' => 'int',
		'usuario_id' => 'int',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'role_id',
		'usuario_id',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function role()
	{
		return $this->belongsTo(Role::class, 'role_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}
}
