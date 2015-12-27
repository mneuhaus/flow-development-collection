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

use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Mvc\Controller\ControllerContext;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

/**
 * The main template view. Should be used as view if you want Fluid Templating
 *
 * @api
 */
class TemplateView extends AbstractTemplateView {

	/**
	 * Set default options based on the supportedOptions provided
	 *
	 * @param array $options
	 * @throws Exception
	 */
	public function __construct(array $options = array()) {
		parent::__construct($options);
	}

	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'templateRootPathPattern' => array('@packageResourcesPath/Private/Templates', 'Pattern to be resolved for "@templateRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),
		'partialRootPathPattern' => array('@packageResourcesPath/Private/Partials', 'Pattern to be resolved for "@partialRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),
		'layoutRootPathPattern' => array('@packageResourcesPath/Private/Layouts', 'Pattern to be resolved for "@layoutRoot" in the other patterns. Following placeholders are supported: "@packageResourcesPath"', 'string'),

		'templateRootPaths' => array(NULL, 'Path(s) to the template root. If NULL, then $this->options["templateRootPathPattern"] will be used to determine the path', 'array'),
		'partialRootPaths' => array(NULL, 'Path(s) to the partial root. If NULL, then $this->options["partialRootPathPattern"] will be used to determine the path', 'array'),
		'layoutRootPaths' => array(NULL, 'Path(s) to the layout root. If NULL, then $this->options["layoutRootPathPattern"] will be used to determine the path', 'array'),

		'templatePathAndFilenamePattern' => array('@templateRoot/@subpackage/@controller/@action.@format', 'File pattern for resolving the template file. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@action", "@format"', 'string'),
		'partialPathAndFilenamePattern' => array('@partialRoot/@subpackage/@partial.@format', 'Directory pattern for global partials. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@partial", "@format"', 'string'),
		'layoutPathAndFilenamePattern' => array('@layoutRoot/@layout.@format', 'File pattern for resolving the layout. Following placeholders are supported: "@templateRoot",  "@partialRoot", "@layoutRoot", "@subpackage", "@layout", "@format"', 'string'),

		'templatePathAndFilename' => array(NULL, 'Path and filename of the template file. If set,  overrides the templatePathAndFilenamePattern', 'string'),
		'layoutPathAndFilename' => array(NULL, 'Path and filename of the layout file. If set, overrides the layoutPathAndFilenamePattern', 'string'),
	);

	/**
	 * Sets the path and name of of the template file. Effectively overrides the
	 * dynamic resolving of a template file.
	 *
	 * @param string $templatePathAndFilename Template file path
	 * @return void
	 * @api
	 */
	public function setTemplatePathAndFilename($templatePathAndFilename) {
		$this->options['templatePathAndFilename'] = $templatePathAndFilename;
		$this->templatePaths->setTemplatePathAndFilename($templatePathAndFilename);
	}

	/**
	 * Sets the path and name of the layout file. Overrides the dynamic resolving of the layout file.
	 *
	 * @param string $layoutPathAndFilename Path and filename of the layout file
	 * @return void
	 * @api
	 */
	public function setLayoutPathAndFilename($layoutPathAndFilename) {
		$this->options['layoutPathAndFilename'] = $layoutPathAndFilename;
		$this->templatePaths->setLayoutPathAndFilename($layoutPathAndFilename);
	}

	/**
	 * Set the root path to the templates.
	 * If set, overrides the one determined from $this->options['templateRootPathPattern']
	 *
	 * @param string $templateRootPath Root path to the templates. If set, overrides the one determined from $this->templateRootPathPattern
	 * @return void
	 * @see setTemplateRootPaths()
	 * @api
	 */
	public function setTemplateRootPath($templateRootPath) {
		$this->setTemplateRootPaths(array($templateRootPath));
	}

	/**
	 * Set the root path(s) to the templates.
	 * If set, overrides the one determined from $this->options['templateRootPathPattern']
	 *
	 * @param array $templateRootPaths Root path(s) to the templates. If set, overrides the one determined from $this->options['templateRootPathPattern']
	 * @return void
	 * @api
	 */
	public function setTemplateRootPaths(array $templateRootPaths) {
		$this->options['templateRootPaths'] = $templateRootPaths;
		$this->templatePaths->setTemplateRootPaths($templateRootPaths);
	}

	/**
	 * Resolves the template root to be used inside other paths.
	 *
	 * @return array Path(s) to template root directory
	 */
	public function getTemplateRootPaths() {
		if ($this->options['templateRootPaths'] !== NULL) {
			return $this->options['templateRootPaths'];
		}
		/** @var $actionRequest \TYPO3\Flow\Mvc\ActionRequest */
		$actionRequest = $this->controllerContext->getRequest();
		return array(str_replace('@packageResourcesPath', 'resource://' . $actionRequest->getControllerPackageKey(), $this->options['templateRootPathPattern']));
	}

	/**
	 * Set the root path to the partials.
	 * If set, overrides the one determined from $this->options['partialRootPathPattern']
	 *
	 * @param string $partialRootPath Root path to the templates. If set, overrides the one determined from $this->options['partialRootPathPattern']
	 * @return void
	 * @see setPartialRootPaths()
	 * @api
	 */
	public function setPartialRootPath($partialRootPath) {
		$this->setPartialRootPaths(array($partialRootPath));
	}

	/**
	 * Set the root path(s) to the partials.
	 * If set, overrides the one determined from $this->options['partialRootPathPattern']
	 *
	 * @param array $partialRootPaths Root paths to the partials. If set, overrides the one determined from $this->options['partialRootPathPattern']
	 * @return void
	 * @api
	 */
	public function setPartialRootPaths(array $partialRootPaths) {
		$this->options['partialRootPaths'] = $partialRootPaths;
		$this->templatePaths->setPartialRootPaths($partialRootPaths);
	}

	/**
	 * Resolves the partial root to be used inside other paths.
	 *
	 * @return array Path(s) to partial root directory
	 */
	protected function getPartialRootPaths() {
		$partialRootPaths = $this->templatePaths->getPartialRootPaths();
		if ($partialRootPaths !== array()) {
			return $partialRootPaths;
		}
		/** @var $actionRequest \TYPO3\Flow\Mvc\ActionRequest */
		$actionRequest = $this->controllerContext->getRequest();
		return array(str_replace('@packageResourcesPath', 'resource://' . $actionRequest->getControllerPackageKey(), $this->options['partialRootPathPattern']));
	}

	/**
	 * Set the root path to the layouts.
	 * If set, overrides the one determined from $this->options['layoutRootPathPattern']
	 *
	 * @param string $layoutRootPath Root path to the layouts. If set, overrides the one determined from $this->options['layoutRootPathPattern']
	 * @return void
	 * @see setLayoutRootPaths()
	 * @api
	 */
	public function setLayoutRootPath($layoutRootPath) {
		$this->setLayoutRootPaths(array($layoutRootPath));
	}

	/**
	 * Set the root path(s) to the layouts.
	 * If set, overrides the one determined from $this->options['layoutRootPathPattern']
	 *
	 * @param array $layoutRootPaths Root paths to the layouts. If set, overrides the one determined from $this->options['layoutRootPathPattern']
	 * @return void
	 * @api
	 */
	public function setLayoutRootPaths(array $layoutRootPaths) {
		$this->options['layoutRootPaths'] = $layoutRootPaths;
		$this->templatePaths->setLayoutRootPaths($layoutRootPaths);
	}

	/**
	 * Resolves the layout root to be used inside other paths.
	 *
	 * @return string Path(s) to layout root directory
	 */
	protected function getLayoutRootPaths() {
		$layoutRootPaths = $this->templatePaths->getLayoutRootPaths();
		if ($layoutRootPaths !== array()) {
			return $layoutRootPaths;
		}
		/** @var $actionRequest \TYPO3\Flow\Mvc\ActionRequest */
		$actionRequest = $this->controllerContext->getRequest();
		return array(str_replace('@packageResourcesPath', 'resource://' . $actionRequest->getControllerPackageKey(), $this->options['layoutRootPathPattern']));
	}

		/**
	 * Checks whether a template can be resolved for the current request context.
	 *
	 * @param ControllerContext $controllerContext Controller context which is available inside the view
	 * @return bool
	 * @api
	 */
	public function canRender(ControllerContext $controllerContext) {
		try {
			$request = $controllerContext->getRequest();
			if (!$request instanceof ActionRequest) {
				// TODO
			}
			$this->setControllerContext($controllerContext);
			if ($request->getControllerSubpackageKey()) {
				$controllerName = $request->getControllerSubpackageKey() . '\\' . $request->getControllerName();
			} else {
				$controllerName = $request->getControllerName();
			}
			$this->templatePaths->setFormat($request->getFormat());
			$this->templatePaths->getTemplateSource($controllerName, $request->getControllerActionName());
			return TRUE;
		} catch (InvalidTemplateResourceException $e) {
			return FALSE;
		}
	}
}
