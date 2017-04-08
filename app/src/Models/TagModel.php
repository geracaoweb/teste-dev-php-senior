<?php
namespace Acme\Models;

use Acme\System\Model;
use Acme\Models\Entity\Tag;

class TagModel extends Model {
    
    /**
    * Tabela principal do model
    * @var string
    */
    private $table = 'tags';
    
    /**
    * Todas as tags da task informada pelo ID
    * @param [type] $id
    * @return void
    */
    public function findtags($id_task) {
        $query = $this->newQuery();
        $query->select('tag.*')
        ->from('tasks_has_tags', 'tt')
        ->innerJoin('tt', 'tags', 'tag', 'tt.id_tag = tag.id')
        ->where("id_task = {$id_task}");
        
        return $this->execute($query);
    }
    
    /**
    * Retorna todos os registros do Banco de dados
    * @return [array] [description]
    */
    public function findAll() {
        $query = $this->newQuery();
        $query->select('*')
        ->from($this->table);
        return $this->execute($query);
    }
    
    /**
    * Atualiza uma tag
    * @param [type] $id
    * @param array $data
    * @return void
    */
    public function update($id, array $data) {
        $where = array(
        'id' => (int) $id
        );
        return $this->DBUpdate($this->table, $data, $where);
    }
    
    /**
     * Deleta uma tag
     * @param [type] $id
     * @return void
     */
    public function delete($id) {
      $where = array(
        'id' => (int) 'id'
      );
      return $this->DBDelete($this->table, $where);
    }
    
    
    /**
    * Encontra uma tag informada pelo ID
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
            return $result[0];
        }
    }
    
    /**
    * Salva uma Tag no banco de dados
    * @param  Tag   $tag [description]
    * @return [type]       [description]
    */
    public function save(Tag $tag) {
        $id = $this->DBPersist($this->table, $tag);
        if ($id) {
            $tag->setId($id);
            return $tag;
        } else {
            throw new \Exception("Impossível inserir a tag", 1);
            
        }
    }
    
}