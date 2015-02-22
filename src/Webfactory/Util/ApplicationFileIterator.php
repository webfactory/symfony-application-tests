<?php

namespace Webfactory\Util;

/**
 * Iterator that filters vendor files and directories out of a given list.
 */
class ApplicationFileIterator extends \FilterIterator
{
    /**
     * Creates an iterator that checks the given files.
     *
     * @param string[]|\Traversable $files
     */
    public function __construct($files)
    {
        if (is_array($files)) {
            $files = new \ArrayIterator($files);
        }
        parent::__construct($files);
    }

    /**
     * Checks if the current element is accepted.
     *
     * @return boolean
     */
    public function accept()
    {
        return !VendorResources::isVendorFile(parent::current());
    }
}
