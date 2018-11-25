<?php

declare(strict_types=1);
namespace OWOX\PhpSpec\Amp\Matcher;

use Amp\Promise;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;
use PhpSpec\Wrapper\DelayedCall;

final class FailWithMatcher implements Matcher
{
    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'failWith' === $name
            && 1 === \count($arguments)
            && \is_string($arguments[0])
            && \class_exists($arguments[0])
            && $subject instanceof Promise;
    }

    public function positiveMatch(string $name, $subject, array $arguments): ?DelayedCall
    {
        $exception = $arguments[0];

        try {
            Promise\wait($subject);
        } catch (\Throwable $e) {
            if ($e instanceof $exception) {
                return null;
            }

            throw $e;
        }

        throw new FailureException("Promise expected to fail with $exception");
    }

    public function negativeMatch(string $name, $subject, array $arguments): ?DelayedCall
    {
        try {
            $this->positiveMatch($name, $subject, $arguments);
        } catch (FailureException $e) {
            return null;
        }

        throw new FailureException(
            "Promise expected to succeed, but failed with {$arguments[0]}"
        );
    }

    public function getPriority(): int
    {
        return 0;
    }
}
