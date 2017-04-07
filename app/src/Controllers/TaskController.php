<?php
namespace Acme\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

use Acme\Models\TaskModel;
use Acme\Models\Entity\Task;

class TaskController implements ControllerProviderInterface {
    
    /**
    * Route Connector do Controller de Tasks
    * @param  Application $app [description]
    * @return [type]           [description]
    */
    public function connect(Application $app) {
        $tasks = $app['controllers_factory'];
        
        /**
        * Retorna todas as tarefas
        * @var [type]
        */
        $tasks->get('/', function(Application $app) {
            $response = (new TaskModel())->getTodosDoBanco();
            return $app->json($response);
        });
        
        
        /**
        * Retorna todas as tarefas
        * @var [type]
        */
        $tasks->get('/{id}', function(Application $app, $id) {
            $response = (new TaskModel())->getTodosDoBanco();
            return $app->json($response);
        });
        
        /**
        * Adiciona um registro
        */
        $tasks->post('/', function (Request $request) use ($app) {
            $data = (object) $request->request->all(); // Pegando já os dados tratados com o uso do Request
            
            $task = new Task();
            $task->setDescription($data->titulo)
            ->setMessage($data->mensagem);
            
            $novaTask = (new TaskModel())->save($task);
            return $app->json($novaTask->getValues());
        });
        
        return $tasks;
    }
}