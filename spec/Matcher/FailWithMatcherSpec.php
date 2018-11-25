<?php

declare(strict_types=1);
namespace spec\OWOX\PhpSpec\Amp\Matcher;

use Amp\Failure;
use Amp\Promise;
use Amp\Success;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;
use PhpSpec\ObjectBehavior;

final class FailWithMatcherSpec extends ObjectBehavior
{
    public function it_is_a_matcher(): void
    {
        $this->shouldHaveType(Matcher::class);
    }

    public function it_should_return_true_if_call_to_matcher_is_supported(Promise $promise): void
    {
        $this->supports('failWith', $promise, [\Exception::class])->shouldReturn(true);
    }

    public function it_should_return_false_if_call_to_matcher_is_not_supported(Promise $promise): void
    {
        $this->supports('foo', $promise, [\Exception::class])->shouldReturn(false);
        $this->supports('failWith', new \stdClass(), [\Exception::class])->shouldReturn(false);
        $this->supports('failWith', $promise, [new \stdClass()])->shouldReturn(false);
        $this->supports('failWith', $promise, [\Exception::class, \Exception::class])->shouldReturn(false);
    }

    public function it_should_succeed_on_positive_match(): void
    {
        $this->positiveMatch('failWith', new Failure(new \Exception()), [\Exception::class])->shouldReturn(null);
    }

    public function it_should_throw_exception_when_failing_positive_match(): void
    {
        $this->shouldThrow(FailureException::class)->duringPositiveMatch('failWith', new Success(), [\Exception::class]);
    }

    public function it_should_succeed_on_negative_match(): void
    {
        $this->negativeMatch('failWith', new Success(), [\Exception::class])->shouldReturn(null);
    }

    public function it_should_throw_exception_when_failing_negative_match(): void
    {
        $this->shouldThrow(FailureException::class)->duringNegativeMatch('failWith', new Failure(new \Exception()), [\Exception::class]);
    }
}
