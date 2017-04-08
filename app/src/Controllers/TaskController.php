<?php
namespace Acme\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

use Acme\Models\TaskModel;
use Acme\Models\Entity\Task;

use Acme\Models\TagModel;
use Acme\Models\Entity\Tag;

class TaskController implements ControllerProviderInterface {
    
    /**
    * Route Connector do Controller de Tasks
    * @param  Application $app [description]
    * @return [type]           [description]
    */
    public function connect(Application $app) {

        /**
         * @factory
         */
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
            $task = (new TaskModel())->findrow((int) $id);
            
            if ($task) {
                return $app->json($task, 200);
            } else {
                return $app->json(["msg" => "Task não encontrada" ], 404);
            }  
        });
        
        /**
        * Adiciona uma nova Task
        */
        $tasks->post('/', function (Request $request) use ($app) {
            $data = (object) $request->request->all(); 
            $task = new Task();
            $task->setDescription($data->titulo)
            ->setMessage($data->mensagem);
            
            $novaTask = (new TaskModel())->save($task);
            return $app->json($novaTask->getValues(), 201);
        });
        
        /**
        * Anexa uma Tag
        */
        $tasks->post('/anexar', function (Request $request) use ($app) {
            $data = (object) $request->request->all();

            if (! (int) $data->id) {
                throw new \Exception("id não informado", 400);
            }

            if (! (int) $data->tag) {
                throw new \Exception("id_tag não informado", 400);
            }

            $task = (new TaskModel())->findrow($data->id);

            if (!$task) {
                throw new \Exception("Task inválida", 404);
            }

            $tag = (new TagModel())->findrow($data->tag);

            if (!$tag) {
                throw new \Exception("Tag inválida", 404);
            }

            $anexo = (new TaskModel())->anexarTag((int) $data->id, (int) $data->tag);
  
        });
        
        return $tasks;
    }
}