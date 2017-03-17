# Symfony Application Tests #

[![Build Status](https://travis-ci.org/webfactory/symfony-application-tests.svg?branch=master)](https://travis-ci.org/webfactory/symfony-application-tests)

Generic functional tests that can be applied to any Symfony application.

## Applied Checks ##

The following checks are automatically applied as soon as the provided test cases are registered.

The checks are often simplistic, but they proofed to be helpful to find common configuration mistakes early.

### Twig Templates ###

The tests ensure that the Twig templates are at least compilable and that all used functions are available.

### Twig Extensions ###

Services that are registered as Twig extensions must at least implement the corresponding interface.

### Event Subscribers ###

Registered event subscribers must implement the correct interfaces and all referenced listener methods
must be callable.

### Validators ###

Registered validators must implement the correct interface and in Symfony < 2.7, an alias must be provided in the configuration.

### Form Types ###

Registered form types must implement the corresponding interface and aliases in configuration and 
implementation must match.

### Role Annotations ###

It is ensured that only existing roles are referenced by @Secure annotations.
The support for these annotations is provided by the 
[SecurityExtraBundle](https://github.com/schmittjoh/JMSSecurityExtraBundle), which does not belong to the Symfony core.

Example:

    /**
     * Check will fail if the role ROLE_ADMIN is not configured in the security.yml.
     *
     * @Secure(roles="ROLE_ADMIN")
     */
    public function myAction()
    {
        // [...]
    }

### Services ###

Each application service must be at least instantiable (abstract and synthetic services are excluded).

## Installation ##

Use the following command to install the package via [Composer](http://getcomposer.org/):

    composer require --dev webfactory/symfony-application-tests

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

### Advanced Kernel Configuration ###

If the kernel directory configuration does not suit your needs, then you also have the opportunity
to configure the class of the kernel:

```xml
    <php>
        <server name="KERNEL_CLASS" value="My\TestKernel" />
    </php>
```

To make this work, the kernel must be loadable by the class loader.

## Changelog ##

### 0.5.3 -> 0.6.0 ###

Removed constraints on form type aliases, which are deprecated since Symfony 2.8.

## Credits, Copyright and License ##

This library was started at webfactory GmbH, Bonn.

- <http://www.webfactory.de>
- <http://twitter.com/webfactory>

Copyright 2014-2017 webfactory GmbH, Bonn. Code released under [the MIT license](LICENSE).
