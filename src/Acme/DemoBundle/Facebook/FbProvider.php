<?php
/**
 * Created by JetBrains PhpStorm.
 * User: daviddjian
 * Date: 16/09/12
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */

  namespace Acme\DemoBundle\Facebook;

  use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
  use Symfony\Component\Security\Core\User\UserProviderInterface;
  use Symfony\Component\Security\Core\Exception\AuthenticationException;
  use Symfony\Component\Security\Core\Exception\NonceExpiredException;
  use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
  use Acme\DemoBundle\Facebook;

  class FbProvider implements AuthenticationProviderInterface
  {
      private $userProvider;
      private $cacheDir;

      public function __construct(UserProviderInterface $userProvider, $cacheDir)
      {
          $this->userProvider = $userProvider;
          $this->cacheDir     = $cacheDir;
      }

      public function authenticate(TokenInterface $token)
      {
          $user = $this->userProvider->loadUserByUsername($token->getUsername());

          if ($user) {
              $authenticatedToken = new FbUserToken($user->getRoles());
              $authenticatedToken->setUser($user);

              return $authenticatedToken;
          }

          throw new AuthenticationException('The Facebook authentication failed.');
      }

      public function supports(TokenInterface $token)
      {
          return $token instanceof FbUserToken;
      }
  }