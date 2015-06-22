<?php

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Message,
    Phalcon\Mvc\Model\Validator\InclusionIn,
    Phalcon\Mvc\Model\Validator\Uniqueness;

class Users extends Model{

 public function validation()
    {

        $this->validate(new InclusionIn(
            array(
                "field"  => "type",
                "domain" => array(0, 1)
            )
        ));

        //Robot name must be unique
        $this->validate(new Uniqueness(
            array(
                "field"   => "username",
                "message" => "El username no está disponible"
            )
        ));

        //Year cannot be less than zero
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->appendMessage(new Message("El email es incorrecto"));
        }

        //Check if any messages have been produced
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

}