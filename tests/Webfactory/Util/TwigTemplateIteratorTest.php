<?php

namespace Webfactory\Util;

/**
 * Tests the Twig template iterator.
 */
class TwigTemplateIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System under test.
     *
     * @var TwigTemplateIterator
     */
    protected $iterator = null;

    /**
     * Initializes the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

    }

    /**
     * Cleans up the test environment.
     */
    protected function tearDown()
    {

        parent::tearDown();
    }

    /**
     * Checks if the object is an iterator.
     */
    public function testIsIterator()
    {

    }

    /**
     * Ensures that the iterator returns file paths.
     */
    public function testIteratorReturnsFilePaths()
    {

    }

    /**
     * Checks if templates on application level are recognized.
     */
    public function testIteratesOverApplicationLevelTemplates()
    {

    }

    /**
     * Checks if bundle templates are recognized.
     */
    public function testIteratesOverBundleTemplates()
    {

    }

    /**
     * Ensures that paths to normal files are not returned.
     */
    public function testDoesNotIteratorOverNonTemplates()
    {

    }

    /**
     * Ensures that the iterator works if no template exists.
     */
    public function testWorksIfNoTemplateFilesAreAvailable()
    {

    }
}
