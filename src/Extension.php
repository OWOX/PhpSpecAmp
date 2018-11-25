<?php

declare(strict_types=1);
namespace OWOX\PhpSpec\Amp;

use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\ServiceContainer;
use OWOX\PhpSpec\Amp\Matcher\FailWithMatcher;
use OWOX\PhpSpec\Amp\Matcher\ResolveToAnInstanceOfMatcher;
use OWOX\PhpSpec\Amp\Matcher\ResolveToMatcher;

final class Extension implements \PhpSpec\Extension
{
    public function load(ServiceContainer $container, array $params): void
    {
        $container->define('amp.matchers.resolve_to', function (ServiceContainer $c): ResolveToMatcher {
            /** @var Presenter $presenter */
            $presenter = $c->get('formatter.presenter');

            return new ResolveToMatcher($presenter);
        }, ['matchers']);

        $container->define('amp.matchers.resolve_to_an_instance_of', function (ServiceContainer $c): ResolveToAnInstanceOfMatcher {
            /** @var Presenter $presenter */
            $presenter = $c->get('formatter.presenter');

            return new ResolveToAnInstanceOfMatcher($presenter);
        }, ['matchers']);

        $container->define('amp.matchers.fail_with', function (): FailWithMatcher {
            return new FailWithMatcher();
        }, ['matchers']);
    }
}
