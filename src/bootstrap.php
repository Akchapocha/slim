<?php

use DI\Container;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PriNikApp\FrontTest\Service\AccessService;
use PriNikApp\FrontTest\Service\PageService;
use PriNikApp\FrontTest\Service\SerialService;
use PriNikApp\FrontTest\Service\UserService;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config.php');
$container = $builder->build();

$container->set(
    EntityManager::class,
    static function (Container $c): EntityManager {
        /** @var array $settings */
        $settings = $c->get('settings');

        // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
        // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
        $cache = $settings['doctrine']['dev_mode']
            ?
            DoctrineProvider::wrap(new ArrayAdapter())
            :
            DoctrineProvider::wrap(
                new FilesystemAdapter(
                    directory: $settings['doctrine']['cache_dir']
                )
            );

        $config = Setup::createAttributeMetadataConfiguration(
            $settings['doctrine']['metadata_dirs'],
            $settings['doctrine']['dev_mode'],
            null,
            $cache
        );

        return EntityManager::create(
            $settings['doctrine']['sklad'],
            $config
        );
    }
);

$container->set(
    AccessService::class,
    static fn(Container $c) => new AccessService($c->get(EntityManager::class))
);

$container->set(
    PageService::class,
    static fn(Container $c) => new PageService($c->get(EntityManager::class))
);

$container->set(
    SerialService::class,
    static fn(Container $c) => new SerialService($c->get(EntityManager::class))
);

$container->set(
    UserService::class,
    static fn(Container $c) => new UserService($c->get(EntityManager::class))
);
return $container;
