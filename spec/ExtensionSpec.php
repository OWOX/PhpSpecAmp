<?php

declare(strict_types=1);
namespace spec\OWOX\PhpSpec\Amp;

use PhpSpec\Extension;
use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;

final class ExtensionSpec extends ObjectBehavior
{
    public function let(ServiceContainer $container): void
    {
        $container->define(Argument::cetera());
    }

    public function it_is_a_phpspec_extension(): void
    {
        $this->shouldHaveType(Extension::class);
    }

    public function it_registers_resolve_to_matcher(ServiceContainer $container): void
    {
        $container->define('amp.matchers.resolve_to', Argument::type(\Closure::class), ['matchers'])
            ->shouldBeCalled();

        $this->load($container, []);
    }

    public function it_registers_resolve_to_an_instance_of_matcher(ServiceContainer $container): void
    {
        $container->define('amp.matchers.resolve_to_an_instance_of', Argument::type(\Closure::class), ['matchers'])
            ->shouldBeCalled();

        $this->load($container, []);
    }

    public function it_registers_fail_with_matcher(ServiceContainer $container): void
    {
        $container->define('amp.matchers.fail_with', Argument::type(\Closure::class), ['matchers'])
            ->shouldBeCalled();

        $this->load($container, []);
    }
}
