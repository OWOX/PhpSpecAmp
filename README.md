# [PhpSpec](https://github.com/phpspec/phpspec) matchers to use with [Amp](https://github.com/amphp/amp/) Promises

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Installation

Install it with:

```bash
composer require owox/phpspec-amp
```

Configure PhpSpec:

```yml
extensions:
  OWOX\PhpSpec\Amp\Extension: ~
```

## Usage

Extension provides following matchers:

- `resolveTo`
- `resolveToAnInstance`
- `failWith`

