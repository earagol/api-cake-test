<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

// use App\Model\Entity\Usuario;

// use Cake\ORM\Query;
// use Cake\ORM\RulesChecker;
// use Cake\ORM\Table;
// use Cake\Validation\Validator;
// use SoftDelete\Model\Table\SoftDeleteTrait;


/**
 * Usuarios Model
 *
 * @method \App\Model\Entity\Usuario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usuario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usuario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usuario|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsuariosTable extends Table
{

    use SoftDeleteTrait;

    protected $softDeleteField = 'deleted_at';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('usuarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }


  /*  public function beforeSave(\Cake\Event\Event $event)
    {
        $entity = $event->data['entity'];

        if (isset($entity->password) && $entity->password) {
            $hasher = new \Cake\Auth\DefaultPasswordHasher();
            $entity->password = $hasher->hash($entity->password);
        }

        return true;
    }*/


     public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        // pr($query->toArray());
        // exit;
        $query->select(['id', 'email', 'clave','rut','nombre','apellidos','usuario','activo']);

        return $query;
    }

 /*    public function beforeSave(\Cake\Event\Event $event)
    {
        $entity = $event->data['entity'];

        if (isset($entity->clave) && $entity->clave) {
            $hasher = new \Cake\Auth\DefaultPasswordHasher();
            $entity->clave = $hasher->hash($entity->clave);
        }

        return true;
    }*/

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('rut')
            ->maxLength('rut', 13)
            ->allowEmpty('rut');


        $validator->allowEmpty('clave');

        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->notEmpty('nombre');

        $validator
            ->scalar('apellidos')
            ->maxLength('apellidos', 100)
            ->allowEmpty('apellidos');

        $validator
            ->scalar('usuario')
            ->maxLength('usuario', 50)
            ->requirePresence('usuario', 'create')
            ->notEmpty('usuario');

        $validator
            ->scalar('nombre_despliegue')
            ->maxLength('nombre_despliegue', 40)
            ->requirePresence('nombre_despliegue', 'create')
            ->notEmpty('nombre_despliegue');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('imagen')
            ->maxLength('imagen', 200)
            ->allowEmpty('imagen');

        $validator
            ->integer('estado')
            ->allowEmpty('estado');

        $validator
            ->scalar('telefono')
            ->maxLength('telefono', 15)
            ->allowEmpty('telefono');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 15)
            ->allowEmpty('celular');

        $validator
            ->scalar('direccion')
            ->maxLength('direccion', 150)
            ->allowEmpty('direccion');

        $validator
            ->integer('sexo')
            ->allowEmpty('sexo', 'create');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmpty('token');

        $validator
            ->date('fecha_nacimiento')
            ->allowEmpty('fecha_nacimiento');

        $validator
            ->boolean('activo')
            ->allowEmpty('activo');

        $validator
            ->dateTime('deleted_at')
            ->allowEmpty('deleted_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email'],'Grupos', 'El grupo ingresado ya fue registrado.'));

        return $rules;
    }
}
