<?php
namespace App\Controller\Api;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
class AppController extends Controller
{
    use \Crud\Controller\ControllerTrait;
    public function initialize()
    {
   
        parent::initialize();
        $this->loadComponent('RequestHandler');
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
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'scope' => ['Users.active' => 1],
                    'fields' => [
                        'username' => 'username',
                    ]
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Users',
                    'scope' => ['Users.active' => 1],
                    'fields' => [
                        'username' => 'id'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);
    }

     public function beforeFilter(Event $event) 
    { 
        header('Access-Control-Allow-Origin: *'); 
        header('Access-Control-Allow-Methods: *'); 
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization'); 

        if(!Configure::read('postman')){
            $this->request->data = $this->request->input ( 'json_decode', true);
        }



    } 

}