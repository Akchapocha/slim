<?php

use DevCoder\DotEnv;
use PriNikApp\FrontTest\Infrastructure\Twig\AssetExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function DI\autowire;
use function DI\get;

$env_file = __DIR__ . '/../.env';
if (is_file($env_file)) {
    (new DotEnv($env_file))->load();
}

return [
    'server.params' => $_SERVER,

    'settings' => [
        'slim' => [
            // Returns a detailed HTML page with error details and
            // a stack trace. Should be disabled in production.
            'displayErrorDetails' => true,

            // Whether to display errors on the internal PHP log or not.
            'logErrors' => true,

            // If true, display full errors with message and stack trace on the PHP log.
            // If false, display only "Slim Application Error" on the PHP log.
            // Doesn't do anything when 'logErrors' is false.
            'logErrorDetails' => true,
        ],
        'doctrine' => [
            // Enables or disables Doctrine metadata caching
            // for either performance or convenience during development.
            'dev_mode' => true,

            // Path where Doctrine will cache the processed metadata
            // when 'dev_mode' is false.
            'cache_dir' => __DIR__ . '/../tmp/doctrine',

            // List of paths where Doctrine will search for metadata.
            // Metadata can be either YML/XML files or PHP classes annotated
            // with comments or PHP8 attributes.
            'metadata_dirs' => [__DIR__ . '/Domain'],

            // The parameters Doctrine needs to connect to your database.
            // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
            // needs a 'path' parameter and doesn't use most of the ones shown in this example).
            // Refer to the Doctrine documentation to see the full list
            // of valid parameters:
            // https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
            'docker' => [
                'driver' => 'pdo_mysql',
                'host' => 'mysql',
                'port' => 3306,
                'dbname' => (getenv('DB_NAME') ?: 'db'),
                'user' => (getenv('DB_USER') ?: 'user'),
                'password' => (getenv('DB_PASS') ?: 'pass')
            ]
        ]
    ],
    FilesystemLoader::class => autowire()
        ->constructorParameter('paths', __DIR__ . '/../templates'),

    Environment::class => autowire()
        ->constructorParameter('loader', get(FilesystemLoader::class))
        ->method("addExtension", get(AssetExtension::class)),

    AssetExtension::class => autowire()
        ->constructorParameter('serverParams', get('server.params')),

];
