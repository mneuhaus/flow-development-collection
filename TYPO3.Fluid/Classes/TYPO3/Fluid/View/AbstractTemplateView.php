<?php
namespace TYPO3\Fluid\View;

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
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Mvc\Controller\ControllerContext;
use TYPO3\Flow\Mvc\Exception;
use TYPO3\Flow\Mvc\View\ViewInterface;
use TYPO3\Fluid\Core\Parser\Interceptor\ResourceInterceptor;
use TYPO3\Fluid\Core\Rendering\RenderingContext;
use TYPO3\Fluid\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\Core\Parser\Configuration;

/**
 * Abstract Fluid Template View.
 *
 * Contains the fundamental methods which any Fluid based template view needs.
 */
abstract class AbstractTemplateView extends \TYPO3Fluid\Fluid\View\AbstractTemplateView implements ViewInterface {

	/**
	 * @Flow\Inject
	 * @var ViewHelperResolver
	 */
	protected $viewHelperResolver;

	/**
	 * This contains the supported options, their default values, descriptions and types.
	 * Syntax example:
	 *     array(
	 *         'someOptionName' => array('defaultValue', 'some description', 'string'),
	 *         'someOtherOptionName' => array('defaultValue', some description', integer),
	 *         ...
	 *     )
	 *
	 * @var array
	 */
	protected $supportedOptions = array();

	/**
	 * The configuration options of this view
	 * @see $supportedOptions
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * View variables and their values
	 * @var array
	 * @see assign()
	 */
	protected $variables = array();

	/**
	 * @var ControllerContext
	 */
	protected $controllerContext;

	/**
	 * Set default options based on the supportedOptions provided
	 *
	 * @param array $options
	 * @throws Exception
	 */
	public function __construct(array $options = array()) {
		// check for options given but not supported
		if (($unsupportedOptions = array_diff_key($options, $this->supportedOptions)) !== array()) {
			throw new Exception(sprintf('The view options "%s" you\'re trying to set don\'t exist in class "%s".', implode(',', array_keys($unsupportedOptions)), get_class($this)), 1359625876);
		}

		// check for required options being set
		array_walk(
			$this->supportedOptions,
			function($supportedOptionData, $supportedOptionName, $options) {
				if (isset($supportedOptionData[3]) && !array_key_exists($supportedOptionName, $options)) {
					throw new Exception('Required view option not set: ' . $supportedOptionName, 1359625876);
				}
			},
			$options
		);

		// merge with default values
		$this->options = array_merge(
			array_map(
				function ($value) {
					return $value[0];
				},
				$this->supportedOptions
			),
			$options
		);

		$templatePaths = new TemplatePaths($this->options);
		$renderingContext = new RenderingContext();
		parent::__construct($templatePaths, $renderingContext);
	}

	/**
	 * Build parser configuration
	 *
	 * @return Configuration
	 */
	protected function buildParserConfiguration() {
		$parserConfiguration = parent::buildParserConfiguration();

		$request = $this->controllerContext->getRequest();
		if ($request instanceof ActionRequest && in_array($request->getFormat(), array('html', NULL))) {
			$resourceInterceptor = new ResourceInterceptor();
			$parserConfiguration->addInterceptor($resourceInterceptor);
		}

		return $parserConfiguration;
	}

	/**
	 * Get a specific option of this View
	 *
	 * @param string $optionName
	 * @return mixed
	 * @throws Exception
	 */
	public function getOption($optionName) {
		if (!array_key_exists($optionName, $this->supportedOptions)) {
			throw new Exception(sprintf('The view option "%s" you\'re trying to get doesn\'t exist in class "%s".', $optionName, get_class($this)), 1359625876);
		}

		return $this->options[$optionName];
	}

	/**
	 * Set a specific option of this View
	 *
	 * @param string $optionName
	 * @param mixed $value
	 * @return void
	 * @throws Exception
	 */
	public function setOption($optionName, $value) {
		if (!array_key_exists($optionName, $this->supportedOptions)) {
			throw new Exception(sprintf('The view option "%s" you\'re trying to set doesn\'t exist in class "%s".', $optionName, get_class($this)), 1359625876);
		}

		$this->options[$optionName] = $value;
	}

	/**
	 * Set legacy compatibility mode on/off by boolean.
	 * If set to FALSE, the ViewHelperResolver will only load a limited
	 * sub-set of ExpressionNodes, making Fluid behave like the legacy
	 * version of the Fluid core package.
	 *
	 * @param bool $legacyMode
	 * @return void
	 */
	public function setLegacyMode($legacyMode) {
		$this->viewHelperResolver->setLegacyMode($legacyMode);
	}

	public function initializeView() {
	}

	/**
	 * Tells if the view implementation can render the view for the given context.
	 *
	 * By default we assume that the view implementation can handle all kinds of
	 * contexts. Override this method if that is not the case.
	 *
	 * @param ControllerContext $controllerContext Controller context which is available inside the view
	 * @return bool TRUE if the view has something useful to display, otherwise FALSE
	 * @api
	 */
	public function canRender(ControllerContext $controllerContext) {
		return TRUE;
	}

	/**
	 * Sets the current controller context
	 *
	 * @param ControllerContext $controllerContext
	 * @return void
	 */
	public function setControllerContext(ControllerContext $controllerContext) {
		if ($this->templatePaths instanceof TemplatePaths) {
			$this->templatePaths->setControllerContext($controllerContext);
		}
		$this->controllerContext = $controllerContext;
		$request = $this->controllerContext->getRequest();
		if (!$request instanceof ActionRequest) {
			// TODO
		}
		#$this->templatePaths->fillDefaultsByPackageName($request->getControllerPackageKey());
		if ($this->baseRenderingContext instanceof RenderingContext) {
			$this->baseRenderingContext->setControllerContext($controllerContext);
		} else {
			$this->baseRenderingContext->setControllerName($request->getControllerName());
			$this->baseRenderingContext->setControllerAction($request->getControllerActionName());
		}
		$this->baseRenderingContext->setViewHelperResolver($this->viewHelperResolver);
	}

}
