<?php

// namespace App\Controller\Api;

// use App\Controller\Api\AppController;
// namespace App\Controller;

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/*use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;*/

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\SitioUsuariosTable $SitioUsuarios
 *
 * @method \App\Model\Entity\SitioUsuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosController extends AppController
{

    public function initialize(){
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->autoRender = false;

        $this->Auth->allow(['add','token','existeUser','index','register']);
    }

    public function token()
    {

        if($this->request->is('POST')){
             pr($this->request->data);
        
            $band=false;
            $user = $this->Auth->identify();
            // pr($this->Auth);
            // pr($user);
            
            // exit('erisk');
            if (!$user) {
                // throw new UnauthorizedException('Invalid username or password');
                $mensaje='Sin autorizacion';
                // exit($mensaje);
            }

            // $user = $this->Auth->identify();
            // if (!$user) {
            //     throw new UnauthorizedException('Invalid username or password');
            // }

            // exit('A');
            

            $respuesta = (object) array(
                'success' => true,
                'message' => $mensaje,
                'data' => [
                    'token' => JWT::encode([
                        'sub' => $user['id'],
                        'exp' =>  time() + 604800
                    ],
                    Security::salt())
                ],
            );
            
            $this->sendResponse($respuesta);
        }
       

       /* $this->set([
            'success' => true,
            'data' => [
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                Security::salt())
            ],
            '_serialize' => ['success', 'data']
        ]);*/
    }


    //////////////////////////////////////////////////////////////////////////////

    public function add2()
    {
        // exit('AA');
        $this->Crud->on('afterSave', function(Event $event) {
            if ($event->subject->created) {
                // $respuesta = (object) array(
                //     'success' => true,
                //     'message' => $mensaje,
                //     'data' => [
                //         'token' => JWT::encode([
                //             'sub' => $user['id'],
                //             'exp' =>  time() + 604800
                //         ],
                //         Security::salt())
                //     ],
                // );

                // $this->sendResponse($respuesta);
                $this->set('data', [
                    'id' => $event->subject->entity->id,
                    'token' => JWT::encode(
                        [
                            'sub' => $event->subject->entity->id,
                            'exp' =>  time() + 604800
                        ],
                    Security::salt())
                ]);
                $this->Crud->action()->config('serialize.data', 'data');
            }
        });
        return $this->Crud->execute();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($userId=null)
    {
        
        $this->paginate = [
            'fields'=>['id','rut','nombre','apellidos','usuario','nombre_despliegue','email','imagen','estado','telefono','celular','direccion','sexo','token','fecha_nacimiento','activo','created',]
        ];
        
        $usuarios = $this->paginate($this->Usuarios);

        $respuesta = (object) array(
            'success' => true,
            'message' => 'success',
            'data' => $usuarios
        );
        
        $this->sendResponse($respuesta);
       
    }

    public function existeUser()
    {
         
        $band=false;
        if(!isset($this->request->data['usuario']) || empty($this->request->data['usuario'])){
            $mensaje ='Debe ingresar el usuario';
        }else{
            if($this->Usuarios->find()->where(['usuario'=>$this->request->data['usuario']])->first() != null){
                $band=true;
                $mensaje ='El nombre de usuario ya esta siendo usado';
            }else{
                $mensaje ='El nombre de usuario ya esta siendo usado';
            }
        }
        
        $usuarios = $this->paginate($this->Usuarios);

        $respuesta = (object) array(
            'success' => $band,
            'message' => $mensaje,
            'data' => ['existe'=>$band]
        );
        
        $this->sendResponse($respuesta);
       
    }



    public function add()
    {
        
        $ok = false;
        if(!isset($this->request->data['usuario']) || empty($this->request->data['usuario'])){
            $mensaje ='Debe ingresar el usuario';
        }else if(!isset($this->request->data['rut']) || empty($this->request->data['rut'])){
            $mensaje ='Debe ingresar el rut';
        }else if(!isset($this->request->data['nombre']) || empty($this->request->data['nombre'])){
            $mensaje ='Debe ingresar el nombre';
        }else if(!isset($this->request->data['apellidos']) || empty($this->request->data['apellidos'])){
            $mensaje ='Debe ingresar el apellido';
        }else if(!isset($this->request->data['sexo']) || empty($this->request->data['sexo'])){
            $mensaje ='Debe ingresar el sexo';
        }else if(!isset($this->request->data['email']) || empty($this->request->data['email'])){
            $mensaje ='Debe ingresar el email';
        }else if(!isset($this->request->data['clave']) || empty($this->request->data['clave'])){
            $mensaje ='Debe ingresar la contraseña';
        }else if(!isset($this->request->data['clave2']) || empty($this->request->data['clave2'])){
            $mensaje ='Debe ingresar la confirmación de la contraseña';
        }else if($this->request->data['clave'] !== $this->request->data['clave2']){
            $mensaje ='Las contraseñas deben ser iguales';
        }else{

            if($this->Usuarios->find()->where(['rut'=>$this->request->data['rut']])->orWhere(['usuario'=>$this->request->data['usuario']])->first() != null){
                $mensaje ='El rut o usuario ingresado ya se encuentran registrado';
            }else{

                $data=[
                        'token' => JWT::encode([
                            'sub' => $user['id'],
                            'exp' =>  time() + 604800
                        ],
                        Security::salt())
                    ];


                $this->request->data('nombre_despliegue',$this->request->data['nombre'].' '.$this->request->data['apellidos']);   
                $usuario = $this->Usuarios->newEntity();
                if ($this->request->is('post')) {
                    $usuario = $this->Usuarios->patchEntity($usuario, $this->request->data);
                    if ($this->Usuarios->save($usuario)) {
                        $ok = true;
                        $mensaje ='Registro exitoso';
                    }else{
                        $mensaje ='No se pudo realizar el registro';
                    }
                }
            }

        }

        $respuesta = (object) array(
            'success' => $ok,
            'message' => $mensaje,
            'data' => []
        );
        
        $this->sendResponse($respuesta);
       
    }

    /**
     * View method
     *
     * @param string|null $id Sitio Usuario id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id=null,$ver=null)
    {
        $usuario = $this->Usuarios->find('all')->where(['usuarios.id'=>$id])->toArray();
        $ok = false;
        $mensaje = 'No existe el usuario indicado';
        if($usuario){
            $ok = true;
            $mensaje = 'success';
        }

        $respuesta = (object) array(
            'succes' => $ok,
            'message' => $mensaje,
            'data' => $usuario
        );
        
        $this->sendResponse($respuesta);
    }


    /**
     * Edit method
     *
     * @param string|null $id Sitio Usuario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        /*pr($this->request->data);
        exit;*/

     /*   if(!isset($this->request->data['usuario']) || empty($this->request->data['usuario'])){
            $mensaje ='Debe ingresar el usuario';
        }else */
        
        $ok = false;
        if(!isset($this->request->data['rut']) || empty($this->request->data['rut'])){
            $mensaje ='Debe ingresar el rut';
        }else if(!isset($this->request->data['nombre']) || empty($this->request->data['nombre'])){
            $mensaje ='Debe ingresar el nombre';
        }else if(!isset($this->request->data['apellidos']) || empty($this->request->data['apellidos'])){
            $mensaje ='Debe ingresar el apellido';
        }else if(!isset($this->request->data['sexo']) || empty($this->request->data['sexo'])){
            $mensaje ='Debe ingresar el sexo';
        }else if(!isset($this->request->data['email']) || empty($this->request->data['email'])){ 
            $mensaje ='Debe ingresar el email';
        }else{
            $id = $this->request->data['id'];
            $this->request->data('nombre_despliegue',$this->request->data['nombre'].' '.$this->request->data['apellidos']);
            $usuario = $this->Usuarios->get($id, [
                'contain' => []
            ]);

            if ($this->request->is(['post','put'])) {
                $usuario = $this->Usuarios->patchEntity($usuario, $this->request->data);
                if ($this->Usuarios->save($usuario)) {
                    $ok = true;
                    $mensaje ='Registro actualizado';
                }else{
                    $mensaje ='No se pudo actualizar el registro';
                }
            }

        }

        $respuesta = (object) array(
            'success' => $ok,
            'message' => $mensaje,
            'data' => []
        );
        
        $this->sendResponse($respuesta);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sitio Usuario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $ok = false;
        $this->request->allowMethod(['post', 'delete']);
        if($this->Usuarios->exists($id)){

            $usuario = $this->Usuarios->get($id);
            if ($this->Usuarios->delete($usuario)) {
                $ok = true;
                $mensaje ='El registro ha sido eliminado';
            } else {
                $mensaje ='No se pudo eliminar el registro';
            }

        }else{
            $mensaje ='Dato inexistente';
        }

        $respuesta = (object) array(
            'success' => $ok,
            'message' => $mensaje,
            'data' => []
        );
        
        
        $this->sendResponse($respuesta);
    }
}
