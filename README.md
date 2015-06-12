# Symfony Application Tests #

[![Build Status](https://travis-ci.org/webfactory/symfony-application-tests.svg?branch=master)](https://travis-ci.org/webfactory/symfony-application-tests)

Generic functional tests that can be applied to any Symfony application.

## Usage ##

Simply add the tests directory to your ``phpunit.xml``:

```xml
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>vendor/webfactory/symfony-application-tests/src/Webfactory/Tests/</directory>
        </testsuite>
    </testsuites>
```

The library uses the functional testing infrastructure from Symfony.
Therefore, you might have to configure your kernel directory:

```xml
    <php>
        <server name="KERNEL_DIR" value="/path/to/your/app/" />
    </php>
```

Details are available at http://symfony.com/doc/current/book/testing.html#your-first-functional-test.

If the kernel directory configuration does not suit your needs, then you also have the opportunity
to configure the class of the kernel:

```xml
    <php>
        <server name="KERNEL_CLASS" value="My\TestKernel" />
    </php>
```

To make this work, the kernel must be loadable by the class loader.

## Credits, Copyright and License ##

This bundle was started at webfactory GmbH, Bonn.

- <http://www.webfactory.de>
- <http://twitter.com/webfactory>

Copyright 2014-2015 webfactory GmbH, Bonn. Code released under [the MIT license](LICENSE).
