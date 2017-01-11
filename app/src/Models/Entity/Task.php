<?php
namespace Acme\Models\Entity;

use Acme\Interfaces\EntidadePadrao;

class Task implements EntidadePadrao {

  /**
  * [$id description]
  * @var [type]
  */
  private $id;

  /**
  * [$message description]
  * @var [type]
  */
  private $message;

  /**
  * [$description description]
  * @var [type]
  */
  private $description;


  public function getId() {
    return $this->id;
  }

  public function setId($id){
    $this->id = $id;
    return $this;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setMessage($message) {
    $this->message = $message;
    return $this;
  }

  public function getDescription(){
    return $this->description;
  }

  public function setDescription($description){

    if (strlen($description) < 3) {
      throw new \Exception("The title field must have 3 or more characters", 422);
    }
    
    $this->description = $description;
    return $this;
  }

  public function getValues() {
    return get_object_vars($this);
  }

}
