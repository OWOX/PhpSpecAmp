<?php

declare(strict_types=1);
namespace spec\OWOX\PhpSpec\Amp\Matcher;

use Amp\Promise;
use Amp\Success;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\Matcher;
use PhpSpec\ObjectBehavior;

final class ResolveToMatcherSpec extends ObjectBehavior
{
    public function let(Presenter $presenter): void
    {
        $presenter->presentValue('foo')->willReturn('foo');
        $presenter->presentValue('bar')->willReturn('bar');

        $this->beConstructedWith($presenter);
    }

    public function is_is_a_matcher(): void
    {
        $this->shouldHaveType(Matcher::class);
    }

    public function it_should_return_true_if_call_to_matcher_is_supported(Promise $promise): void
    {
        $this->supports('resolveTo', $promise, ['foo'])->shouldReturn(true);
    }

    public function it_should_return_false_if_call_to_matcher_is_not_supported(Promise $promise): void
    {
        $this->supports('foo', $promise, ['foo'])->shouldReturn(false);
        $this->supports('resolveTo', new \stdClass(), ['foo'])->shouldReturn(false);
        $this->supports('resolveTo', $promise, [])->shouldReturn(false);
        $this->supports('resolveTo', $promise, ['foo', 'bar'])->shouldReturn(false);
    }

    public function it_should_succeed_on_positive_match(): void
    {
        $this->positiveMatch('resolveTo', new Success('foo'), ['foo'])->shouldReturn(null);
    }

    public function it_should_throw_exception_when_failing_positive_match(): void
    {
        $this->shouldThrow(FailureException::class)->duringPositiveMatch('resolveTo', new Success('foo'), ['bar']);
    }

    public function it_should_succeed_on_negative_match(): void
    {
        $this->negativeMatch('resolveTo', new Success('foo'), ['bar'])->shouldReturn(null);
    }

    public function it_should_throw_exception_when_failing_negative_match(): void
    {
        $this->shouldThrow(FailureException::class)->duringNegativeMatch('resolveTo', new Success('foo'), ['foo']);
    }
}
