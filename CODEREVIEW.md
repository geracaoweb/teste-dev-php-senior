# Code Review - Matheus Fidelis

Opa Juninho, beleza? Recebi seu Pull Request. Realmente está muito legal, amigo!
Trouxe aqui umas sugestões legais pra melhor seu projeto. Dividi em partes.

## Ambiente - Docker

Notei que você conseguiu subir todo nosso ambiente com o Docker. Isso é legal!
Porém, o Docker é utilizado em muitos outros lugares além da máquina local, e uma das principais features do mesmo é poder fazer deploy da mesma stack de desenvolvimento para o Cluster de produção.

Sua Stack estava perfeita para desenvolvimento, mas para o nosso Deploy vamos precisar de um pouco mais de hard configs no PHP, trocar nosso Web Server para o Nginx e configurá-lo para diminuir cada vez mais latência e aguentar mais concorrência na sua API. Fora que nesse caso, teríamos uma divergência muito grande entre os ambientes de desenvolvimento e produção se utilizarmos Nginx lá no Cloud e Apache nas máquinas locais. Isso vai também contra uma das principais finalidades do Docker, não é mesmo?

Na Stack do PHP tomei a liberdade de utilizar uma arquitetura que gira em torno do PHP-FPM e utilizei o Nginx para gerenciar os requests até o mesmo de uma forma mais performática. Essa arquitetura nos ajuda em ambientes de alta demanda, pois nos permite separar e escalar separadamente as máquinas de PHP das de Web Servers. Quando isso for pra produção, temos que tomar um cuidado bem grande nesses pontos.

Para escalarmos tanto em produção quanto pra agilizar a produtividade do desenvolvedor, criei um container pra você que tem a única finalidade de satisfazer as dependências do Composer da nossa aplicação.

Por ultimo modularizei inicialmente o docker-compose e docker-compose-prod. Nele vamos trabalhar algumas otimizações do ambiente para o deploy da Stack.

Pronto, nesse formato a arquitetura do ambiente da sua API vai ficar sensacional!

## Arquitetura - Infra

Puts, Juninho! Nesse vamos ter que ter um trabalho um pouco maior, ein!
Seu código ficou pequeno e limpo. Mas temos sempre que pensar nossas soluções de uma forma organizada, sempre esperando que elas vão crescer 10 a 100 vezes mais do que é hoje. Nesse caso, vamos organizar melhor e deixar separadinho ai onde são nossos Controllers, nossos Models, Entidades, utilizar um recurso bem bacana chamada de Bootstrap para carregar nossas dependências iniciais e separar nossas configurações de rotas em um arquivo a parte.

Você poderia separar também um arquivo de rotas, que vai agrupar em um lugar unificado quais as rotas existentes no sistema quais serão os Controllers responsáveis por atender as mesmas.

* Organização das pastas e Namespaces

Achei sua organização um pouco bagunçada, acho que tem que melhorar bastante ai, meu jovem... Você optou por utilizar um padrão semelhante a App\Funcionalidade\Controller(Model) Ela até pode ser funcional, mas no seu lugar eu optaria por utilizar um padrão App\Controllers(Models, Views, Utils)\Classe.

* Bootstrap

Seria bacana você adicionar uma camada na arquitetura da API em que ela carreta todos os componentes dentro da instância de Application. Isso de da mais liberadade de adicionar e remover ferramentas e camadas no seu projeto conforme ele for crescendo ou escalando. 



* Controllers

Por que não utilizar todos os recursos do SilexPHP e boas práticas do próprio REST para gerenciar as rotas da API? Ao invés de usar o Método connect do Silex dentro da classe para instanciar a mesma classe e chamar um método, podemos realizar essa operação direto, sem precisar dessa solução estranha. Olha só:

Antes para chamar um método de acordo com a rota solicitada, tinhamos nosso método Connect quer recebia a requisição e passava para uma action da mesma classe. E no final ainda tivemos que utilizar um componente do Symfony, o JsonResponse para retornar o conteúdo solicitado. Isso funciona bem! Mas estamos utilizando o Micro Framework e podemos utilizar algumas soluções próprias do mesmo para não ficar dependendo de muita coisa desnecessária. No caso, poderiamos utilizar um método do Application chamado json, ficando $app->json;

Para manter coerência na API, vamos tratar nosso resource ```task``` uniforme, de uma forma que não seja acessível mais pela ```/``` e sim por um endpoint intuitivo, como ```/tasks```

Antes:

```php
 public function connect(Application $app) {
     $factory = $app['controllers_factory'];
     $factory->get('/','Acme\Task\Controller\TaskController::listAction');
     $factory->post('/','Acme\Task\Controller\TaskController::createAction');
     return $factory;
}
```


```php
public function listAction()
{
    $conn = Database::getConnection();
    $results = $conn->query('SELECT * FROM tasks');
    $response = array(
        'tasks' => [],
    );

    foreach ($results as $t) {
        $response['tasks'][] = array(
            'id' => $t['id'],
            'title' => $t['description'],
        );
    }

    return new JsonResponse($response);
}
```

Depois:

```php
public function connect(Application $app) {
    $tasks = $app['controllers_factory'];

    $tasks->get('/',function (Application $app) {
        $conn = Database::getConnection();
        $results = $conn->query('SELECT * FROM tasks');
        $response = array(
            'tasks' => [],
        );

        foreach ($results as $t) {
            $response['tasks'][] = array(
                'id' => $t['id'],
                'title' => $t['description'],
            );
        }

        return new $app->json($response);
    });

    return $tasks;
}
```

Temos que alterar também nosso arquivo de rotas. Tomei a liberadade de separar ele em um arquivo especialista em ```src/Configs/Routes.php```

```php
<?php

/**
 * Lista de rotas da aplicação. - Tasks
 */
$app->mount('/task', new \Acme\Controllers\TaskController());

```
* Content-Type/application/json

Notei que sua API foi construida para receber somente requisições no formato ```multipart/form-data``` ou ```application/x-www-form-urlencoded```. 
Seria bacana adicionar um middleware para negociações de conteúdo baseadas em ```application/json``` lá no nosso Bootstrap.php

```php
/**
 * Middleware = Content-Type: application/json
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
```

* Doctrine - Bancos de dados - Model

Você aprendeu a usar bem o PDO, legal!! O PDO é fantástico mesmo, eu gosto muito e acho muito seguro!
Mas tem ferramentas ainda mais poderosas capazes de abstrair o PDO. Vamos utilizar o Doctrine!
O Doctrine já possui um Provider nativo do Doctrine. Dentro do Silex existe uma função register(), que
tem a função de guardar em 'containers' as configurações dos nosso providers. Pra ficar BEM legal mesmo, por que você não troca aquela classe Database.php por um register do provider do Doctrine? Você leva de brinde um monte de métodos poderosos! Pra manter a linha de organização das depedencias das nossa ferramentas, vamos manter tudo lá no Bootstrap.php

Poderiamos trocar também nosso banco para MySQL. Já te ajudei colocando isso no Docker também pra usar no ambiente de produção e até podemos fazer um deploy dele em produção. 

```php
/**
 * Serviço do Doctrine - MySQL
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
    'driver'    => "pdo_mysql",
    'host'      => "mysql",
    'port'      => "3306",
    'dbname'    => "tasks",
    'user'      => "root",
    'password'  => "root"
  ),
));
```

Outra coisa que eu acho que você poderia melhorar é a forma como o banco é criado. Nesse caso podermos utilizar um esquema de migrations, que nos permite instalar, gerar seeds, versionar e até mesmo dar rollback caso algo dê errado. 
Integrei o console do Symfony pra ajudar a gente com isso junto ao Doctrine. Para instalar o banco, basta executar 

```
    $ php console.php migrations:migrate
```

* Alguns problemas de encapsulamento.

Juninho, seu código ficou bem simples, mas existem alguns Anti-Patterns bem nítidos alí.
Praticamente todos os seus controllers executam Querys e tem muito conhecimento do banco de dados. Essa função deve ficar de responsabilidade somente do Model da Aplicação.

A função do Model é fornecer uma interface simples e intuitiva para os dados da aplicação de forma unificada, não importando quem chama ou de onde vem a solicitação. Devemos nos preocupar em fazer nossos módels serem reaproveitados tanto pela nossa interface web quanto uma CLI por exemplo, ambas usando a mesma interface de acesso de modo que ele todo seja reaproveitado. Nosso controllers não devem executar Querys, pois isso foge totalmente desse principio. Te aconselho a criar classes de Model para realizar essas tarefas.

Crie uma classe abstrata que vai receber o objeto de conexão PDO do Doctrine e guarde algumas funções principais genéricas como Insert, Select, Delete e Update. Assim caso você precise adicionar algum Endpoint na sua API, você já vai ter toda essa infra pronta.  

* Entidades

Notei que no Add você tentou utilizar uma entidade da Task. Aquilo não fez sentido nenhum. Você nem precisaria utilizar um entidade aí. Sempre que quiser brincar com Entidades, use elas pra valer, e vá até o fim com a consistência delas. Usar pela metade é perda de tempo.

```php
$title = isset($data['title']) ? $data['title'] : NULL;

$task = new Task();
$task->setDescription($title);

$sql = "INSERT INTO tasks (description) VALUES (:title)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':title', $title);
$stmt->execute();
```

Você pode inclusive fazer as validações de algumas regras de negócio simples diretamente nas entidades, como no caso dos três caracteres do description

Antes:

```php
public function setDescription($description){

  if (strlen($description) < 3) {
    throw new \Exception("The title field must have 3 or more characters", 422);
  }

  $this->description = $description;
  return $this;
}
```

Depois:

```php
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
```

* Outras dicas

Os erros da API do Silex são os erros padrão das bibliotecas de Request e Response do Symfony.
O ideal é retornar todos os tipos de erros via JSON também, assim nosso clientes podem utilizar estratégias a partir deles também compartilhando das nossas Exceptions e status.

No bootstrap você poderia adicionar uma configuração de erro do Application();
Assim em todos os Exceptions a devolução vai ser um {msg: 'mensagem definida', status: 'codigo HTTP'}

```php
/**
 * [$error Vai customizar a devolução de erros das Exceptions em formato JSON]
 */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    $error = array("msg" => $e->getMessage(), 'status' => $code);
    return $app->json($error, $code);
});
```

* Requests

### Listar todas as tasks

```
GET /task HTTP/1.1
Host: localhost
Cache-Control: no-cache
```

```bash
$ curl -X GET localhost/task/

HTTP/1.1 200 OK
Server: nginx/1.6.2
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 13:00:44 GMT

[
  {
    "id": "1",
    "description": "titulo de teste",
    "message": "mensagem de teste"
  },
  {
    "id": "2",
    "description": "titulo de teste",
    "message": "mensagem de teste"
  },
  {
    "id": "3",
    "description": "titulo de teste",
    "message": "mensagem de teste"
  }
]
```

### Visualizar uma Task

```
GET /task/{id} HTTP/1.1
Host: localhost
Cache-Control: no-cache
```

```bash
$ curl -X GET localhost/task/{id} -i

HTTP/1.1 200 OK
Server: nginx/1.6.2
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 12:59:21 GMT

{
  "id": "1",
  "description": "titulo de teste",
  "message": "mensagem de teste",
  "tags": [
    {
      "id": "4",
      "title": "tagatualizada",
      "color": "#FFFFF"
    },
    {
      "id": "5",
      "title": "urgente",
      "color": "#000000"
    }
  ]
}
```

Criar uma task 

```bash
$ curl -X POST -H "Content-Type:application/json" -d '{"titulo":"titulo de teste", "mensagem":"mensagem de teste"}' localhost/task/ -i

HTTP/1.1 201 Created
Server: nginx/1.6.2
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 12:21:47 GMT

{"id":"1","message":"mensagem de teste","description":"titulo de teste"}
```

### Criar uma tag 

```json
POST /tag/ HTTP/1.1
Host: localhost
Content-Type: application/json
Cache-Control: no-cache

{
	"color":"#000000",
	"title":"urgente"
}
```

```bash
$ curl -X POST -H "Content-Type:application/json" -d '{"title":"urgente", "color":"#000000"}' localhost/tag/ -i

HTTP/1.1 201 Created
Server: nginx/1.6.2
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 12:18:07 GMT

{"id":4,"title":"urgente","color":"#000000"}
```

### Edita uma tag

```json
PUT /tag/{id) HTTP/1.1
Host: localhost
Content-Type: application/json
Cache-Control: no-cache

{
	"color":"#FFFFF",
	"title":"tagatualizada"
}
```

```bash
$ curl -X PUT -H "Content-Type:application/json" -d '{"title":"tagatualizada", "color":"#FFFFF"}' localhost/tag/{id_tag}

HTTP/1.1 200 OK
Server: nginx/1.6.2
Content-Type: application/json
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 12:19:42 GMT

{"id":"4","title":"tagatualizada","color":"#FFFFF"}
```

### Deletar uma Tag

```json
DELETE /tag/{id} HTTP/1.1
Host: localhost
Content-Type: application/json
Cache-Control: no-cache
```

```bash
curl -X DELETE -H "Content-Type:application/json" localhost/tag/{id} -i

HTTP/1.1 204 No Content
Server: nginx/1.6.2
Content-Type: text/html; charset=UTF-8
Connection: keep-alive
X-Powered-By: PHP/7.0.17
Cache-Control: no-cache, private
Date: Sat, 08 Apr 2017 12:16:34 GMT
```


* Testes
