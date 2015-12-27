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

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Fluid\Core\Parser\SyntaxTree\Expression\LegacyNamespaceExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\CastingExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode;

/**
 * Class ViewHelperResolver
 *
 * Class whose purpose is dedicated to resolving classes which
 * can be used as ViewHelpers and ExpressionNodes in Fluid.
 *
 * This Flow-specific version of the ViewHelperResolver works
 * almost exactly like the one from Fluid itself, with the main
 * differences being that this one supports a legacy mode flag
 * which when toggled on makes the Fluid parser behave exactly
 * like it did in the legacy Fluid package.
 *
 * In addition to modifying the behavior or the parser when
 * legacy mode is requested, this ViewHelperResolver is also
 * made capable of "mixing" two different ViewHelper namespaces
 * to effectively create aliases for the Fluid core ViewHelpers
 * to be loaded in the TYPO3\Fluid\ViewHelpers scope as well.
 */
class ViewHelperResolver extends \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver {

	/**
	 * @var bool
	 */
	protected $legacyMode = FALSE;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * List of class names implementing ExpressionNodeInterface
	 * which will be consulted when an expression does not match
	 * any built-in parser expression types.
	 *
	 * @var array
	 */
	protected $expressionNodeTypes = array(
		CastingExpressionNode::class,
		MathExpressionNode::class,
		TernaryExpressionNode::class,
		LegacyNamespaceExpressionNode::class
	);

	/**
	 * List of class names for ExpressionNodes which apply only
	 * when the View is explicitly toggled into legacy mode.
	 *
	 * @var array
	 */
	protected $legacyExpressionNodeTypes = array(
		LegacyNamespaceExpressionNode::class,
	);

	/**
	 * @return boolean
	 */
	public function isLegacyMode() {
		return $this->legacyMode;
	}

	/**
	 * @param boolean $legacyMode
	 * @return void
	 */
	public function setLegacyMode($legacyMode) {
		$this->legacyMode = $legacyMode;
	}

	/**
	 * Get the ExpressionNode types which apply in the current
	 * compatibility mode.
	 *
	 * @return array
	 */
	public function getExpressionNodeTypes() {
		if ($this->legacyMode === TRUE) {
			return $this->legacyExpressionNodeTypes;
		}
		return $this->expressionNodeTypes;
	}

	/**
	 * Overridden namespace registration. Only difference from
	 * base method is that the "f" namespace is allowed to be
	 * registered with the legacy TYPO3\Fluid namespace without
	 * causing exceptions.
	 *
	 * @param string $identifier
	 * @param string $phpNamespace
	 * @return void
	 */
	public function registerNamespace($identifier, $phpNamespace) {
		if ('f' !== $identifier && $phpNamespace !== 'TYPO3\\Fluid\\ViewHelpers') {
			parent::registerNamespace($identifier, $phpNamespace);
		}
	}

	/**
	 * @param string $namespaceIdentifier
	 * @param string $methodIdentifier
	 * @return NULL|string
	 */
	public function resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier) {
		if ($namespaceIdentifier === 'f') {
			$actualViewHelperName = implode('\\', array_map('ucfirst', explode('.', $methodIdentifier)));
			$alternativeClassName = 'TYPO3\\Fluid\\ViewHelpers\\' . $actualViewHelperName . 'ViewHelper';
			if (class_exists($alternativeClassName)) {
				return $alternativeClassName;
			}
		}
		return parent::resolveViewHelperClassName($namespaceIdentifier, $methodIdentifier);
	}

	/**
	 * @param string $viewHelperClassName
	 * @return ViewHelperInterface
	 */
	public function createViewHelperInstanceFromClassName($viewHelperClassName) {
		return $this->objectManager->get($viewHelperClassName);
	}

}
