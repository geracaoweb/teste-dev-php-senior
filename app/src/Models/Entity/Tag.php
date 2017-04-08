<?php
namespace Acme\Models\Entity;

use Acme\Interfaces\EntidadePadrao;

/**
* Entidade Tag
*/
class Tag implements EntidadePadrao {
    
    /**
    * @var [int]
    */
    private $id;
    
    /**
    * @var [string]
    */
    private $title;
    
    /**
    * @var [string]
    */
    private $color;
    
    /**
     * Setter ID
     * @param [int] $id
     * @return void
     */
    public function setId($id) {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Getter ID
     * @return $id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Setter Title
     * @param [string] $title
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Getter ID
     * @return $title
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Setter Title
     * @param [string] $color
     * @return $this
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }
    
    /**
     * Getter Color
     * @return $color
     */
    public function getColor() {
        return $this->color;
    }
    
    /**
     * Get Values
     * @return array
     */
    public function getValues() {
        return get_object_vars($this);
    }
    
}