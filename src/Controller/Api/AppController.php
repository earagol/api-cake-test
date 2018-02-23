<?php
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Http\Client\Request;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class AppController extends Controller
{
    use \Crud\Controller\ControllerTrait;

	/*
	* initialize
	* Inicialización del appController principal
	*/
	 public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*$this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' => 'Usuarios',
                    'fields' => [
                        'username' => 'id'
                    ],

                    'parameter' => 'token',

                    // Boolean indicating whether the "sub" claim of JWT payload
                    // should be used to query the Users model and get user info.
                    // If set to `false` JWT's payload is directly returned.
                    'queryDatasource' => true,
                ],
                'Form' => [
                    'finder' => 'auth',
                    'fields' => [
                        'username' => 'usuario',
                        'password' => 'clavee'
                    ],
                    'userModel' => 'Usuarios'
                ]
            ],

            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',

            // If you don't have a login action in your application set
            // 'loginAction' to false to prevent getting a MissingRouteException.
            'loginAction' => false
        ]);*/

         $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]);


       /* $this->loadComponent('Auth', [
            // 'storage' => 'Memory',
            'authorize' => ['Controller'],
            'loginAction' => [
                'prefix' => 'api',
                'controller' => 'Usuarios',
                'action' => 'token',
                'plugin' => false
            ],
            'authenticate' => [
                'Form' => [
                    'finder' => 'auth',
                    'userModel' => 'Usuarios',
                    // 'scope' => ['Usuarios.activo' => 1],
                    'fields' => [
                        'usuario' => 'username',
                        'clave' => 'password'
                    ],

                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Usuarios',
                    'scope' => ['Usuarios.activo' => 1],
                    'fields' => [
                        'username' => 'usuario',
                        'password' => 'clave'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);*/


         $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'finder' => 'auth',
                    'fields' => [
                        'username' => 'usuario',
                        'password' => 'clave'
                    ],
                    'userModel' => 'Usuarios'
                ]
            ],
            'loginAction' => [
                'controller' => 'Usuarios',
                'action' => 'login'
            ],
            'authError' => false,
            'loginRedirect' => [
                'controller' => 'Usuarios',
                'action' => 'home'
            ],
            'logoutRedirect' => [
                'controller' => 'Usuarios',
                'action' => 'login'
            ],
            // 'unauthorizedRedirect' => $this->referer()

        ]);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }



     public function beforeFilter(Event $event) 
    { 
        $this->request->data = $this->request->input ( 'json_decode', true) ;
    } 


    public function sendResponse($respuesta)
    {
        $this->response->header('Access-Control-Allow-Origin','*');
        $this->response->header('Access-Control-Allow-Methods','*');
        $this->response->header('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, Authorization');

        $respuesta = (object) array(
            'status' => isset($respuesta->status) ? $respuesta->status : true,
            'code' => isset($respuesta->code) ? $respuesta->code : 200,
            'message' => isset($respuesta->message) ? $respuesta->message : '',
            'messages' => isset($respuesta->messages) ? $respuesta->messages : [],
            'errors' => isset($respuesta->errors) ? $respuesta->errors : [],
            'success' => isset($respuesta->success) ? $respuesta->success : [],
            'sessionid' => isset($respuesta->sessionid) ? $respuesta->sessionid : null,
            'data' => isset($respuesta->data) ? $respuesta->data : []
        );

        $this->response->body(json_encode($respuesta));
        $this->response->send();
        $this->response->stop();
    }

}