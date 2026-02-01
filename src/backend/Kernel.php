<?php

namespace App;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
    }

    protected function configureRoutes(\Symfony\Component\Routing\RouteCollectionBuilder $routes): void
    {
    }
}