<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property string $rut
 * @property string $clave
 * @property string $nombre
 * @property string $apellidos
 * @property string $usuario
 * @property string $nombre_despliegue
 * @property string $email
 * @property string $imagen
 * @property int $estado
 * @property string $telefono
 * @property string $celular
 * @property string $direccion
 * @property bool $sexo
 * @property string $token
 * @property \Cake\I18n\FrozenDate $fecha_nacimiento
 * @property bool $activo
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $deleted_at
 */
class Usuario extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
/*    protected $_accessible = [
        'rut' => true,
        'clave' => true,
        'nombre' => true,
        'apellidos' => true,
        'usuario' => true,
        'nombre_despliegue' => true,
        'email' => true,
        'imagen' => true,
        'estado' => true,
        'telefono' => true,
        'celular' => true,
        'direccion' => true,
        'sexo' => true,
        'token' => true,
        'fecha_nacimiento' => true,
        'activo' => true,
        'created' => true,
        'modified' => true,
        'deleted_at' => true
    ];*/

    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
  /*  protected $_hidden = [
        'token'
    ];*/
}
