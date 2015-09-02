<?php
namespace TYPO3\Fluid\Core\Rendering;

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
use TYPO3\Flow\Object\ObjectManagerInterface;

/**
 * The rendering context that contains useful information during rendering time of a Fluid template
 */
class RenderingContext extends \NamelessCoder\Fluid\Core\Rendering\RenderingContext {

	/**
	 * Object manager which is bubbled through. The ViewHelperNode cannot get an ObjectManager injected because
	 * the whole syntax tree should be cacheable
	 *
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * Controller context being passed to the ViewHelper
	 *
	 * @var ControllerContext
	 */
	protected $controllerContext;

//	/**
//	 * ViewHelper Variable Container
//	 *
//	 * var \NamelessCoder\Fluid\Core\ViewHelper\ViewHelperVariableContainer
//	 */
//	protected $viewHelperVariableContainer;

	public function initializeObject() {
		//$this->viewHelperVariableContainer = new ViewHelperVariableContainer();
	}

	/**
	 * Returns the object manager. Only the ViewHelperNode should do this.
	 *
	 * @return ObjectManagerInterface
	 */
	public function getObjectManager() {
		return $this->objectManager;
	}

	/**
	 * Get the template variable container
	 *
	 * @see getVariableProvider
	 * @return VariableProviderInterface The Template Variable Container
	 */
	public function getTemplateVariableContainer() {
		return $this->getVariableProvider();
	}

	/**
	 * Set the controller context which will be passed to the ViewHelper
	 *
	 * @param ControllerContext $controllerContext The controller context to set
	 * @return void
	 */
	public function setControllerContext(ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
		$request = $this->controllerContext->getRequest();
		if (!$request instanceof ActionRequest) {
			return;
		}
		$this->setControllerAction($request->getControllerActionName());

		// Check if Request is using a sub-package key; in which case we translate this
		// for our RenderingContext as an emulated plain old sub-namespace controller.
		if ($request->getControllerSubpackageKey() !== NULL) {
			$this->setControllerName($request->getControllerName());
		} else {
			$this->setControllerName($request->getControllerSubpackageKey() . '\\' . $request->getControllerName());
		}
	}

	/**
	 * Get the controller context which will be passed to the ViewHelper
	 *
	 * @return ControllerContext The controller context to set
	 */
	public function getControllerContext() {
		return $this->controllerContext;
	}

//	/**
//	 * Get the ViewHelperVariableContainer
//	 *
//	 * return ViewHelperVariableContainer
//	 */
//	public function getViewHelperVariableContainer() {
//		return $this->viewHelperVariableContainer;
//	}

}
