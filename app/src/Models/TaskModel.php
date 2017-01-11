<?php
namespace Acme\Models;

use Acme\System\Model;
use Acme\Models\Entity\Task;

class TaskModel extends Model {

  /**
   * Cria a tabela de Tasks caso não exista
   */
  public function __construct() {
    $queryCreate = "CREATE TABLE IF NOT EXISTS tasks (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      description TEXT,
      message TEXT);";

    $this->execute($queryCreate);
  }

  /**
   * Tabela principal do model
   * @var string
   */
  private $table = 'tasks';

  /**
   * Retorna todos os registros do Banco de dados
   * @return [array] [description]
   */
  public function getTodosDoBanco() {
    $query = $this->newQuery();
    $query->select('*')
    ->from($this->table);
    return $this->execute($query);
  }

  /**
   * Salva uma Task no banco de dados
   * @param  Task   $task [description]
   * @return [type]       [description]
   */
  public function save(Task $task) {
    $id = $this->DBPersist($this->table, $task);
    if ($id) {
      $task->setId($id);
      return $task;
    } else {
      throw new \Exception("Impossível inserir a task", 1);

    }
  }
}
