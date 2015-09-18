<?php
namespace TYPO3\Fluid\Core\Parser\SyntaxTree\Expression;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3Fluid\Fluid\Core\Parser\ParsingState;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\AbstractExpressionNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\ExpressionNodeInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class LegacyNamespaceExpression
 */
class LegacyNamespaceExpressionNode extends AbstractExpressionNode implements ExpressionNodeInterface {

	/**
	 * Pattern which detects ternary conditions written in shorthand
	 * syntax, e.g. {checkvar ? thenvar : elsevar}.
	 */
	public static $detectionExpression = '/{namespace\\s*([a-z0-9]+)\\s*=\\s*([a-z0-9_\\\\]+)\\s*}/i';

	/**
	 * @param string $expression
	 * @param array $matches
	 * @param ParsingState $parsingState
	 */
	public function __construct($expression, array $matches, ParsingState $parsingState = NULL) {
		parent::__construct($expression, $matches, $parsingState);
		if ($parsingState) {
			$parsingState->getViewHelperResolver()->registerNamespace($matches[1], $matches[2]);
		}
	}

	/**
	 * @param RenderingContextInterface $renderingContext
	 * @param string $expression
	 * @return mixed
	 */
	public static function evaluateExpression(RenderingContextInterface $renderingContext, $expression, array $matches) {
		$renderingContext->getViewHelperResolver()->registerNamespace($matches[1], $matches[2]);
	}

}
