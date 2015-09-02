<?php
namespace TYPO3\Fluid\Core\Variables;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use NamelessCoder\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Variable provider using Flow's ObjectAccess to traverse through object graphs
 */
class FlowVariableProvider extends StandardVariableProvider {

	/**
	 * Get a variable by dotted path expression, retrieving the variable from nested arrays/objects one segment at a time.
	 * If the second argument is provided, it must be an array of  accessor names which can be used to extract each value in
	 * the dotted path.
	 *
	 * @param string $path
	 * @param array $accessors
	 * @return mixed
	 */
	public function getByPath($path, array $accessors = array()) {
		return ObjectAccess::getPropertyPath($this->variables, $path);
	}

}
