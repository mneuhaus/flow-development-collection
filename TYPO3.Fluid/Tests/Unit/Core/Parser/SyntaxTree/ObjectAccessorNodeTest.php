<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Parser\SyntaxTree;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use TYPO3\Fluid\Core\Parser\SyntaxTree\TemplateObjectAccessInterface;
use TYPO3\Fluid\Core\Variables\FlowVariableProvider;

/**
 * Testcase for ObjectAccessorNode
 */
class ObjectAccessorNodeTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function evaluateGetsPropertyPathFromVariableContainer() {
		$node = new ObjectAccessorNode('foo.bar');
		$renderingContext = $this->getMock(RenderingContextInterface::class);
		$variableProvider = new FlowVariableProvider(array(
			'foo' => array(
				'bar' => 'some value'
			)
		));
		$renderingContext->expects($this->any())->method('getVariableProvider')->will($this->returnValue($variableProvider));

		$value = $node->evaluate($renderingContext);

		$this->assertEquals('some value', $value);
	}

	/**
	 * @test
	 */
	public function evaluateCallsObjectAccessOnSubjectWithTemplateObjectAccessInterface() {
		$node = new ObjectAccessorNode('foo.bar');
		$renderingContext = $this->getMock(RenderingContextInterface::class);
		$templateObjectAccessValue = $this->getMock(TemplateObjectAccessInterface::class);
		$variableProvider = new FlowVariableProvider(array(
			'foo' => array(
				'bar' => $templateObjectAccessValue
			)
		));
		$renderingContext->expects($this->any())->method('getVariableProvider')->will($this->returnValue($variableProvider));

		$templateObjectAccessValue->expects($this->once())->method('objectAccess')->will($this->returnValue('special value'));

		$value = $node->evaluate($renderingContext);

		$this->assertEquals('special value', $value);
	}

}
