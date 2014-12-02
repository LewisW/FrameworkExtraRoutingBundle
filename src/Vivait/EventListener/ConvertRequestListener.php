<?php

namespace Vivait\FrameworkExtraRoutingBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ConvertRequestListener {

    private $map = [
        '_converters' => 'Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter'
    ];

    /**
     * Modifies the Request object to apply configuration information found in
     * controllers annotations like the template to render or HTTP caching
     * configuration.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelRequest(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

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