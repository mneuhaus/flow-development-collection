<?php
namespace TYPO3\Fluid\Core\Cache;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use NamelessCoder\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3\Flow\Cache\Frontend\PhpFrontend;

/**
 * Connector class that enables the TYPO3 cache called "fluid_template" to be operated with the interface appropriate for the Fluid engine.
 */
class FluidTemplateCache extends PhpFrontend implements FluidCacheInterface {

	/**
	 * @param null $name
	 * @return void
	 */
	public function flush($name = NULL) {
		parent::flush();
	}

	/**
	 * @param string $entryIdentifier
	 * @return mixed
	 */
	public function get($entryIdentifier) {
		return $this->requireOnce($entryIdentifier);
	}

	/**
	 * @param string $entryIdentifier
	 * @param string $sourceCode
	 * @param array $tags
	 * @param integer $lifetime
	 */
	public function set($entryIdentifier, $sourceCode, array $tags = array(), $lifetime = NULL) {
		if (strpos($sourceCode, '<?php') === 0) {
			// Remove opening PHP tag; it is added by the cache backend to which
			// we delegate and would be duplicated if not removed.
			$sourceCode = substr($sourceCode, 6);
		}
		parent::set($entryIdentifier, $sourceCode, $tags, time() + 86400);
	}

}
