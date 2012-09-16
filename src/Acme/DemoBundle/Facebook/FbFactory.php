<?php
/**
 * Created by JetBrains PhpStorm.
 * User: daviddjian
 * Date: 16/09/12
 * Time: 21:13
 * To change this template use File | Settings | File Templates.
 */
  namespace Acme\DemoBundle\Facebook;

  use Symfony\Component\DependencyInjection\ContainerBuilder;
  use Symfony\Component\DependencyInjection\Reference;
  use Symfony\Component\DependencyInjection\DefinitionDecorator;
  use Symfony\Component\Config\Definition\Builder\NodeDefinition;
  use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
  use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

  class FbFactory implements SecurityFactoryInterface
  {
      public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
      {
          $providerId = 'security.authentication.provider.facebook.'.$id;
          $providerDD = new DefinitionDecorator('facebook.security.authentication.provider');

          $container->setDefinition($providerId, $providerDD)
                    ->replaceArgument(0, new Reference($userProvider));

          $listenerId = 'security.authentication.listener.facebook.'.$id;

          $listenerDD = new DefinitionDecorator('facebook.security.authentication.listener');

          $listener = $container->setDefinition($listenerId, $listenerDD);

          return array($providerId, $listenerId, $defaultEntryPoint);
      }

      public function getPosition()
      {
          return 'pre_auth';
      }

      public function getKey()
      {
          return 'facebook';
      }

      public function addConfiguration(NodeDefinition $node)
      {

      }
  }
