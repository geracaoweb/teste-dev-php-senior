<?php
namespace Acme\Models;

use Acme\System\Model;
use Acme\Models\Entity\Task;

use Acme\Models\TagModel;

class TaskModel extends Model {

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
   * Encontra uma task informada pelo ID
   * @param [type] $id
   * @return void
   */
  public function findrow($id) {
    $query = $this->newQuery();
    $query->select('*')
      ->from($this->table)
      ->where("id = {$id}")
      ->setMaxResults(1);

    $result = $this->execute($query);

    if (!$result) {
      return null;
    } else {
      $tags = (new TagModel())->findtags((int) $id);
      $result[0]['tags'] = $tags;
      return $result[0];
    }
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

  /**
   * Enexa uma Tag existente a a uma Task informada pelo id
   * @param [int] $id_task id da task
   * @param [int] $id_tag id da tag
   * @return bool
   */
  public function anexarTag($id_task, $id_tag) {

    $query = $this->newQuery();
    $query->insert('tasks_has_tags')
      ->values(
        [
        'id_tag' => $id_tag,
        'id_task' => $id_task
        ]
      );
    
    $result = $this->execute($query);
    
    if ($result) {
      return $this->findrow($id_task); 
    } else {
      return false;
    }
  }
}
