<?php
/**
 * Created by JetBrains PhpStorm.
 * User: daviddjian
 * Date: 16/09/12
 * Time: 21:24
 * To change this template use File | Settings | File Templates.
 */

  namespace Acme\DemoBundle\Facebook;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpKernel\Event\GetResponseEvent;
  use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
  use Symfony\Component\Security\Http\Firewall\ListenerInterface;
  use Symfony\Component\Security\Core\Exception\AuthenticationException;
  use Symfony\Component\Security\Core\SecurityContextInterface;
  use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
  use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
  use Acme\DemoBundle\Facebook;


  class FbListener implements ListenerInterface
  {
      protected $securityContext;
      protected $authenticationManager;

      public function __construct(SecurityContextInterface $securityContext,
                                  AuthenticationManagerInterface $authenticationManager,
                                  $appId, $appSecret)
      {
          $this->securityContext = $securityContext;
          $this->authenticationManager = $authenticationManager;
          $this->appId = $appId;
          $this->appSecret = $appSecret;
      }

      public function handle(GetResponseEvent $event)
      {
          $request = $event->getRequest();
          if (null !== $this->securityContext->getToken()) {
              return;
          }

          $cookie = $this->getFacebookCookie();

          if ($cookie) {
              $token = new FbUserToken();
              $token->setAccessToken($cookie['access_token']);


              $content = @file_get_contents(
                      'https://graph.facebook.com/me?access_token=' .
                      $token->getAccessToken());
              if($content) {
                  $userData = json_decode($content);
                  $user = new FbUser($userData);
                  $token->setUser($user);
                  $this->securityContext->setToken($token);
              }
          }
      }

      private function getFacebookCookie() {
          $args = array();
          if (!isset($_COOKIE['fbs_' . $this->appId])) {
              return;
          }
          parse_str(trim($_COOKIE['fbs_' . $this->appId], '\\"'), $args);
          ksort($args);
          $payload = '';
          foreach ($args as $key => $value) {
              if ($key != 'sig') {
                  $payload .= $key . '=' . $value;
              }
          }
          if (md5($payload . $this->appSecret) != $args['sig']) {
              return null;
          }
          return $args;
      }
  }