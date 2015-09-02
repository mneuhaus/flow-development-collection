<?php
use NamelessCoder\Fluid\Core\Exception;
use NamelessCoder\Fluid\Core\Parser\InterceptorInterface;
use NamelessCoder\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use NamelessCoder\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use NamelessCoder\Fluid\Core\Parser\SyntaxTree\RootNode;
use NamelessCoder\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use NamelessCoder\Fluid\Core\Parser\TemplateParser;
use NamelessCoder\Fluid\Core\Rendering\RenderingContextInterface;
use NamelessCoder\Fluid\Core\ViewHelper\Exception as ViewHelperException;
use NamelessCoder\Fluid\Core\ViewHelper\ViewHelperInterface;
use NamelessCoder\Fluid\Core\ViewHelper\ViewHelperVariableContainer;
use NamelessCoder\Fluid\Core\ViewHelper\TagBuilder;
use NamelessCoder\Fluid\Core\Variables\StandardVariableProvider;
use NamelessCoder\Fluid\View\Exception as ViewException;
use NamelessCoder\Fluid\View\Exception\InvalidSectionException;
use NamelessCoder\Fluid\View\Exception\InvalidTemplateResourceException;

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