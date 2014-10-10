<?php

namespace Webfactory\Util;

/**
 * Allows iteration over service that are defined directly in the application.
 *
 * Services that are defined by vendor bundles etc. are filtered and skipped.
 *
 * This iterator combines 2 strategies to determine if a service is defined
 * directly in the application:
 *
 * - Try to determine, which services are defined by the extensions of the application bundles and
 *   whitelist these.
 * - Follow conventions and allow services that use the prefixes of the application bundle extensions.
 */
class ApplicationServiceIterator
{

}
