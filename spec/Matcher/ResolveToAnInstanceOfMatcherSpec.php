<?php

declare(strict_types=1);
namespace spec\OWOX\PhpSpec\Amp\Matcher;

use Amp\Promise;
use Amp\Success;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\Matcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ResolveToAnInstanceOfMatcherSpec extends ObjectBehavior
{
    public function let(Presenter $presenter): void
    {
        $this->beConstructedWith($presenter);
    }

    public function is_is_a_matcher(): void
    {
        $this->shouldHaveType(Matcher::class);
    }

    public function it_should_return_true_if_call_to_matcher_is_supported(Promise $promise): void
    {
        $this->supports('resolveToAnInstanceOf', $promise, [\stdClass::class])->shouldReturn(true);
    }

    public function it_should_return_false_if_call_to_matcher_is_not_supported(Promise $promise): void
    {
        $this->supports('foo', $promise, [\stdClass::class])->shouldReturn(false);
        $this->supports('resolveToAnInstanceOf', new \stdClass(), [\stdClass::class])->shouldReturn(false);
        $this->supports('resolveToAnInstanceOf', $promise, [])->shouldReturn(false);
        $this->supports('resolveToAnInstanceOf', $promise, ['foo'])->shouldReturn(false);
        $this->supports('resolveToAnInstanceOf', $promise, ['foo', 'bar'])->shouldReturn(false);
    }

    public function it_should_succeed_on_positive_match(): void
    {
        $this->positiveMatch('resolveToAnInstanceOf', new Success(new \stdClass()), [\stdClass::class])
            ->shouldReturn(null);
    }

    public function it_should_throw_exception_on_class_mismatch(Presenter $presenter): void
    {
        $presenter->presentValue(\stdClass::class)->willReturn(\stdClass::class);
        $presenter->presentValue(\DateTimeImmutable::class)->willReturn(\DateTimeImmutable::class);

        $this->beConstructedWith($presenter);

        $this->shouldThrow(FailureException::class)->duringPositiveMatch(
            'resolveToAnInstanceOf',
            new Success(new \DateTimeImmutable()),
            [\stdClass::class]
        );
    }

    public function it_should_throw_exception_on_type_mismatch(Presenter $presenter): void
    {
        $presenter->presentValue(\DateTimeImmutable::class)->willReturn(\stdClass::class);
        $presenter->presentValue('string')->willReturn('string');

        $this->beConstructedWith($presenter);

        $this->shouldThrow(FailureException::class)->duringPositiveMatch(
            'resolveToAnInstanceOf',
            new Success('Date Time Immutable'),
            [\DateTimeImmutable::class]
        );
    }

    public function it_should_succeed_on_negative_match(Presenter $presenter): void
    {
        $presenter->presentValue(Argument::any())->willReturn('foo');
        $presenter->presentValue(Argument::any())->willReturn('bar');

        $this->beConstructedWith($presenter);

        $this->negativeMatch(
            'resolveToAnInstanceOf',
            new Success(new \DateTimeImmutable()),
            [\stdClass::class]
        )->shouldReturn(null);
    }

    public function it_should_throw_exception_when_failing_negative_match(Presenter $presenter): void
    {
        $presenter->presentValue(\stdClass::class)->willReturn(\stdClass::class);

        $this->beConstructedWith($presenter);

        $this->shouldThrow(FailureException::class)->duringNegativeMatch(
            'resolveToAnInstanceOf',
            new Success(new \stdClass()),
            [\stdClass::class]
        );
    }
}
