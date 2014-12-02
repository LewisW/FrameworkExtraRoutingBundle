<?php

namespace Vivait\FrameworkExtraRoutingBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ConvertRequestListener {

    private $map = [
        '_converters' => 'Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter'
    ];

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->map as $param => $class) {
            if ($configuration = $request->attributes->get($param)) {
                $newConfiguration = [];
                foreach (is_array($configuration) ? $configuration : array($configuration) as $configuration) {
                    if (is_array($configuration)) {
                        $newConfiguration[] = new $class($configuration);
                    }
                }

                $request->attributes->set($param, $newConfiguration);
            }
        }
    }

}