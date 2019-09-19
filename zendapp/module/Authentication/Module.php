<?php
namespace Authentication;

// Add these import statements:
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/./config/module.config.php';
    }

    // Add this method:
   
    // It will add the Model Details and Databse details
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AuthenticationTable::class => function($container) {
                    $tableGateway = $container->get(Model\AuthenticationTableGateway::class);
                    return new Model\AuthenticationTable($tableGateway);
                },
                Model\AuthenticationTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Authentication());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    // DB Table Name is defined here 
                },
            ],
        ];
    }

     // Add this method:
     // Defining factory
     public function getControllerConfig()
     {
         return [
             'factories' => [
                 Controller\AuthenticationController::class => function($container) {
                     return new Controller\AuthenticationController(
                         $container->get(Model\AuthenticationTable::class)
                     );
                 },
             ],
         ];
     }
}