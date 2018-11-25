<?php

declare(strict_types=1);
namespace OWOX\PhpSpec\Amp\Matcher;

use Amp\Promise;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\Matcher;
use PhpSpec\Wrapper\DelayedCall;

final class ResolveToMatcher implements Matcher
{
    /** @var Presenter */
    private $presenter;

    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'resolveTo' === $name
            && 1 === \count($arguments)
            && $subject instanceof Promise;
    }

    public function positiveMatch(string $name, $subject, array $arguments): ?DelayedCall
    {
        $expected = $arguments[0];
        $actual = Promise\wait($subject);

        if ($expected !== $actual) {
            throw new FailureException(
                \sprintf(
                    'Expected promise to resolve to %s, but was resolved to %s',
                    $this->presenter->presentValue($expected),
                    $this->presenter->presentValue($actual)
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
                'Expected promise to not resolve to %s',
                $this->presenter->presentValue($arguments[0])
            )
        );
    }

    public function getPriority(): int
    {
        return 0;
    }
}
