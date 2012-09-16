<?php
/**
 * Created by JetBrains PhpStorm.
 * User: daviddjian
 * Date: 16/09/12
 * Time: 21:22
 * To change this template use File | Settings | File Templates.
 */

  namespace Acme\DemoBundle\Facebook;

  use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

  /**
   * A token represents the user authentication data present in the request.
   * Once a request is authenticated, the token retains the user`s data,
   * and delivers this data across the security context.
   */
  class FbUserToken extends AbstractToken
  {
    public $accessToken;

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCredentials()
    {
        return '';
    }
  }