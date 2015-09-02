<?php
namespace TYPO3\Fluid\Core\ViewHelper;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Argument definition of each view helper argument
 *
 * This subclass of ArgumentDefinition from Fluid has
 * one additional capability: defining that an argument
 * should be expected as a parameter for the render()
 * method - which means the ViewHelperInvoker will be
 * processing it a bit differently. Other than this it
 * is a normal Fluid ArgumentDefinition.
 *
 * @deprecated
 */
class ArgumentDefinition extends \NamelessCoder\Fluid\Core\ViewHelper\ArgumentDefinition {

	/**
	 * TRUE if it is a method parameter
	 *
	 * @var bool
	 */
	protected $isMethodParameter = FALSE;

	/**
	 * Constructor for this argument definition.
	 *
	 * @param string $name Name of argument
	 * @param string $type Type of argument
	 * @param string $description Description of argument
	 * @param bool $required TRUE if argument is required
	 * @param mixed $defaultValue Default value
	 * @param bool $isMethodParameter TRUE if this argument is a method parameter
	 */
	public function __construct($name, $type, $description, $required, $defaultValue = NULL, $isMethodParameter = FALSE) {
		$this->name = $name;
		$this->type = $type;
		$this->description = $description;
		$this->required = $required;
		$this->defaultValue = $defaultValue;
		$this->isMethodParameter = $isMethodParameter;
	}

	/**
	 * TRUE if it is a method parameter
	 *
	 * @return bool TRUE if it's a method parameter
	 */
	public function isMethodParameter() {
		return $this->isMethodParameter;
	}

}
