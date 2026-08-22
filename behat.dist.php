<?php

declare(strict_types=1);

use App\Tests\Behat\Context\ApiContext;
use App\Tests\Behat\Context\RecipeContext;
use App\Tests\Behat\Context\UserContext;
use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\Config\Suite;
use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__.'/vendor/autoload.php';

$_SERVER['APP_ENV'] ??= 'test';
$_ENV['APP_ENV'] ??= 'test';

(new Dotenv())->bootEnv(__DIR__.'/.env');

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('default'))
                    ->withPaths('%paths.base%/features')
                    ->withContexts(
                        ApiContext::class,
                        RecipeContext::class,
                        UserContext::class,
                    ),
            )
            ->withExtension(
                new Extension(
                    SymfonyExtension::class,
                ),
            ),
    );
