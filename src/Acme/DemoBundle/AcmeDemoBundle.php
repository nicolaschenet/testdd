<?php

namespace Acme\DemoBundle;

use Acme\DemoBundle\Facebook;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AcmeDemoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new \Acme\DemoBundle\Facebook\FbFactory());
    }
}
