<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * @return array
     */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @inheritdoc
     */
    public function getCacheDir()
    {
        if (getenv('TRAVIS')) {
            return '/var/ramfs/cache/' .  $this->environment;
        }

        return '/tmp/entity-merger/cache';
    }

    /**
     * @inheritdoc
     */
    public function getLogDir()
    {
        if (getenv('TRAVIS')) {
            return '/var/ramfs/logs';
        }

        return '/tmp/entity-merger/logs';
    }
} 
