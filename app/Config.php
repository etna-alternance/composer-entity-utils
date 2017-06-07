<?php

namespace TestEntityUtils;

use Silex\Application;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Configuration principale de l'application
 */
class Config implements ServiceProviderInterface
{
    /**
     * @params string $env
     */
    public function __construct()
    {
    }

    /**
     * @{inherit doc}
     */
    public function register(Container $app)
    {
        $this->registerEnvironmentParams($app);
    }

    /**
     * Set up environmental variables
     *
     * If development environment, set xdebug to display all the things
     *
     * @param Application $app Silex Application
     *
     */
    private function registerEnvironmentParams(Application $app)
    {
        $app['application_name']      = 'test_entity_utils';
        $app['application_env']       = 'testing';
        $app['application_path']      = realpath(__DIR__ . "/../");
        $app['application_namespace'] = __NAMESPACE__;

        ini_set('display_errors', true);
        ini_set('xdebug.var_display_max_depth', 100);
        ini_set('xdebug.var_display_max_children', 100);
        ini_set('xdebug.var_display_max_data', 100);
        error_reporting(E_ALL);
        $app["debug"] = true;
    }

}
