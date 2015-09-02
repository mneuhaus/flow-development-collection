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

use NamelessCoder\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Log\SystemLoggerInterface;
use TYPO3\Flow\Mvc\Controller\ControllerContext;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Reflection\ReflectionService;
use TYPO3\Fluid\Core\Exception;
use TYPO3\Fluid\Core\Rendering\RenderingContext;

/**
 * TODO
 *
 * @api
 */
abstract class AbstractConditionViewHelper extends \NamelessCoder\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Reflection service
	 *
	 * @Flow\Inject
	 * @var ReflectionService
	 */
	protected $reflectionService;

	/**
	 * Controller Context to use
	 *
	 * @var ControllerContext
	 * @api
	 */
	protected $controllerContext;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @param RenderingContextInterface $renderingContext
	 * @return void
	 * @throws \TYPO3\Flow\Exception
	 */
	public function setRenderingContext(RenderingContextInterface $renderingContext) {
		if (!$renderingContext instanceof RenderingContext) {
			// FIXME
			throw new \TYPO3\Flow\Exception('invalid rendering context..');
		}
		$this->renderingContext = $renderingContext;
		$this->controllerContext = $renderingContext->getControllerContext();
		$this->templateVariableContainer = $renderingContext->getVariableProvider();
		$this->viewHelperVariableContainer = $renderingContext->getViewHelperVariableContainer();
	}

	/**
	 * Call the render() method and handle errors.
	 *
	 * @return string the rendered ViewHelper
	 * @throws Exception
	 */
	protected function callRenderMethod() {
		$renderMethodParameters = array();
		foreach ($this->argumentDefinitions as $argumentName => $argumentDefinition) {
			if ($argumentDefinition instanceof ArgumentDefinition && $argumentDefinition->isMethodParameter()) {
				$renderMethodParameters[$argumentName] = $this->arguments[$argumentName];
			}
		}

		try {
			return call_user_func_array(array($this, 'render'), $renderMethodParameters);
		} catch (Exception $exception) {
			if (!$this->objectManager->getContext()->isProduction()) {
				throw $exception;
			} else {
				$this->systemLogger->log('An Exception was captured: ' . $exception->getMessage() . '(' . $exception->getCode() . ')', LOG_ERR, 'TYPO3.Fluid', get_class($this));
				return '';
			}
		}
	}

	/**
	 * Register method arguments for "render" by analysing the doc comment above.
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function registerRenderMethodArguments() {
		$methodParameters = $this->reflectionService->getMethodParameters(get_class($this), 'render');
		if (count($methodParameters) === 0) {
			return;
		}

		$methodTags = $this->reflectionService->getMethodTagsValues(get_class($this), 'render');

		$paramAnnotations = array();
		if (isset($methodTags['param'])) {
			$paramAnnotations = $methodTags['param'];
		}

		$i = 0;
		foreach ($methodParameters as $parameterName => $parameterInfo) {
			$dataType = NULL;
			if (isset($parameterInfo['type'])) {
				$dataType = isset($parameterInfo['array']) && (bool)$parameterInfo['array'] ? 'array' : $parameterInfo['type'];
			} else {
				throw new Exception('could not determine type of argument "' . $parameterName . '" of the render-method in ViewHelper "' . get_class($this) . '". Either the methods docComment is invalid or some PHP optimizer strips off comments.', 1242292003);
			}

			$description = '';
			if (isset($paramAnnotations[$i])) {
				$explodedAnnotation = explode(' ', $paramAnnotations[$i]);
				array_shift($explodedAnnotation);
				array_shift($explodedAnnotation);
				$description = implode(' ', $explodedAnnotation);
			}
			$defaultValue = NULL;
			if (isset($parameterInfo['defaultValue'])) {
				$defaultValue = $parameterInfo['defaultValue'];
			}
			$this->argumentDefinitions[$parameterName] = new ArgumentDefinition($parameterName, $dataType, $description, ($parameterInfo['optional'] === FALSE), $defaultValue, TRUE);
			$i++;
		}
	}

	/**
	 * @return ArgumentDefinition[]
	 */
	public function prepareArguments() {
		$this->registerRenderMethodArguments();
		return parent::prepareArguments();
	}

}
