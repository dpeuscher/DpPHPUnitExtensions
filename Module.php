<?php
namespace DpPHPUnitExtensions;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;


class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
	    return array(
		    'Zend\Loader\StandardAutoloader' => array(
			    'namespaces' => array(
				    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
			    ),
		    ),
	    );
    }
}
