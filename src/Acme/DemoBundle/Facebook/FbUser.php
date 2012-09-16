<?php
/**
 * Created by JetBrains PhpStorm.
 * User: daviddjian
 * Date: 16/09/12
 * Time: 21:06
 * To change this template use File | Settings | File Templates.
 */

  namespace Acme\DemoBundle\Facebook;
  use Symfony\Component\Security\Core\User\UserInterface;

  class FbUser
  {
      public function __construct($data)
      {
          $this->id = $data->id;
          $this->username = $data->first_name;
          $this->roles = $this->getRoles();
      }

      public function getRoles()
      {
           return array('ROLE_USER');
      }

      public function getUsername()
      {
          return $this->username;
      }

      public function getId()
      {
          return $this->id;
      }

      public function __toString()
      {
          return serialize($this);
      }
  }