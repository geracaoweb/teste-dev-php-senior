<?php
namespace Acme\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

use Acme\Models\TagModel;
use Acme\Models\Entity\Tag;

/**
* Tags Controller - API
*/
class TagsController implements ControllerProviderInterface {
    
    /**
    * Route Connector do Controller de Tags
    * @param  Application $app [description]
    * @return [Object]         [Application]
    */
    public function connect(Application $app) {
        
        /**
        * @factory
        */
        $tags = $app['controllers_factory'];
        
        /**
        * Retorna uma lista de tags
        */
        $tags->get('/', function(Application $app) {
            $tags = (new TagModel())->findAll();
            return $app->json($tags, 200);
        });
        
        /**
        * Retorna uma Tag informada pelo id
        */
        $tags->get('/{id}', function(Application $app, $id) {
            return $app->json([$id]);
        });
        
        /**
        * Cria uma nova Tag
        */
        $tags->post('/', function(Request $request) use ($app) {
            $data = (object) $request->request->all();
            
            $tag = (new Tag())
            ->setColor($data->color)
            ->setTitle($data->title);
            
            $novaTag = (new TagModel())->save($tag);
            return $app->json($novaTag->getValues(), 201);
        });
        
        /**
        * Edita uma tag
        */
        $tags->put('/{id}', function(Request $request, $id) use ($app) {
            $data = $request->request->all();
            $model = new TagModel();

            $tag = $model->findrow((int) $id);

            if (!$tag) {
                return $app->json(['error' => 'tag não encontrada'], 404);
            }

            if ($model->update((int) $id, $data)) {
                return $app->json($model->findrow((int) $id));
            } else {
                return $app->json(["msg" => "Nada para atualizar"]);
            }
        });
        
        /**
         * Deleta uma Tag
         */
        $tags->delete('/{id}', function(Application $app, $id) {
           
            $model = new TagModel();

            if ($model->delete((int) $id)) {
                return $app->json([], 204);
            } else {
                return $app->json(['msg' => 'tag não encontrada'], 204);
            }

        });
        
        return $tags;
    }
    
    
}