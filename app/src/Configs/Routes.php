<?php

/**
 * Lista de rotas da aplicação. - Tasks
 */
$app->mount('/task', new Acme\Controllers\TaskController());
$app->mount('/tag', new Acme\Controllers\TagsController());
