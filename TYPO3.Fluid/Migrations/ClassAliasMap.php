<?php
use TYPO3Fluid\Fluid\Core\Exception;
use TYPO3Fluid\Fluid\Core\Parser\InterceptorInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\RootNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\Parser\TemplateParser;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception as ViewHelperException;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer;
use TYPO3Fluid\Fluid\View\Exception as ViewException;
use TYPO3Fluid\Fluid\View\Exception\InvalidSectionException;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

return array(
	'TYPO3\\Fluid\\Core\\Parser\\InterceptorInterface' => InterceptorInterface::class,
	'TYPO3\\Fluid\\Core\\Parser\\SyntaxTree\\NodeInterface' => NodeInterface::class,
	'TYPO3\\Fluid\\Core\\Rendering\\RenderingContextInterface' => RenderingContextInterface::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\ViewHelperInterface' => ViewHelperInterface::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\Facets\\ChildNodeAccessInterface' => ViewHelperInterface::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\Facets\\CompilableInterface' => ViewHelperInterface::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\Facets\\PostParseInterface' => ViewHelperInterface::class,

	// Fluid-specific errors
	'TYPO3\\Fluid\\Core\\Exception' => Exception::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\Exception' => ViewHelperException::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\Exception\\InvalidVariableException' => Exception::class,
	'TYPO3\\Fluid\\View\\Exception' => ViewException::class,
	'TYPO3\\Fluid\\View\\Exception\\InvalidSectionException' => InvalidSectionException::class,
	'TYPO3\\Fluid\\View\\Exception\\InvalidTemplateResourceException' => InvalidTemplateResourceException::class,

	// Fluid variable containers, ViewHelpers, interfaces
	'TYPO3\\Fluid\\Core\\Parser\\SyntaxTree\\RootNode' => RootNode::class,
	'TYPO3\\Fluid\\Core\\Parser\\SyntaxTree\\ViewHelperNode' => ViewHelperNode::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\TemplateVariableContainer' => StandardVariableProvider::class,
	'TYPO3\\Fluid\\Core\\ViewHelper\\ViewHelperVariableContainer' => ViewHelperVariableContainer::class,

	// Semi API level classes; mainly used in unit tests
	'TYPO3\\Fluid\\Core\\ViewHelper\\TagBuilder' => TagBuilder::class,

	// needed?
	'TYPO3\\Fluid\\Core\\Parser\\TemplateParser' => TemplateParser::class,
	'TYPO3\\Fluid\\Core\\Parser\\SyntaxTree\\AbstractNode' => AbstractNode::class,
);