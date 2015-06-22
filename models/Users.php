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

        $this->validate(new Uniqueness(
            array(
                "field"   => "username",
                "message" => "El username no está disponible"
            )
        ));

        $this->validate(new Uniqueness(
            array(
                "field"   => "email",
                "message" => "El email no está disponible"
            )
        ));

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->appendMessage(new Message("El email es incorrecto"));
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

}