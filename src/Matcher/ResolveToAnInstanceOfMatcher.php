<?php

declare(strict_types=1);
namespace OWOX\PhpSpec\Amp\Matcher;

use Amp\Promise;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\Matcher;
use PhpSpec\Wrapper\DelayedCall;

final class ResolveToAnInstanceOfMatcher implements Matcher
{
    /** @var Presenter */
    private $presenter;

    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'resolveToAnInstanceOf' === $name
            && 1 === \count($arguments)
            && (\class_exists($arguments[0]) || \interface_exists($arguments[0]))
            && $subject instanceof Promise;
    }

    public function positiveMatch(string $name, $subject, array $arguments): ?DelayedCall
    {
        $expectedClass = $arguments[0];
        $actual = Promise\wait($subject);

        if (! \is_object($actual) || ! \is_a($actual, $expectedClass)) {
            throw new FailureException(
                \sprintf(
                    'Expected promise to resolve to an instance of %s, but was resolved to %s',
                    $this->presenter->presentValue($expectedClass),
                    $this->presenter->presentValue(\is_object($actual) ? \get_class($actual) : \gettype($actual))
                )
            );
        }

        return null;
    }

    public function negativeMatch(string $name, $subject, array $arguments): ?DelayedCall
    {
        try {
            $this->positiveMatch($name, $subject, $arguments);
        } catch (FailureException $e) {
            return null;
        }

        throw new FailureException(
            \sprintf(
                'Expected promise to not resolve to an instance of %s',
                $this->presenter->presentValue($arguments[0])
            )
        );
    }

    public function getPriority(): int
    {
        return 0;
    }
}
