<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Parser\Interceptor;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
 
 use TYPO3\Fluid\Core\ViewHelper\ViewHelperResolver;
 use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\TextNode;
 use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\RootNode;
 use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
 use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
 use TYPO3Fluid\Fluid\Core\Parser\InterceptorInterface;
 use TYPO3Fluid\Fluid\Core\Parser\ParsingState;
 use TYPO3\Fluid\Core\Parser\Interceptor\ResourceInterceptor;
 use TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper;
 use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
 use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Testcase for Interceptor\Resource
 *
 */
class ResourceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function resourcesInCssUrlsAreReplacedCorrectly() {
		$mockDummyNode = $this->getMock(NodeInterface::class);
		$mockPathNode = $this->getMock(NodeInterface::class);
		$mockViewHelper = $this->getMock(AbstractViewHelper::class);

		$originalText1 = '<style type="text/css">
			#loginscreen {
				height: 768px;
				background-image: url(';
		$originalText2 = '../../../../Public/Backend/Media/Images/Login/MockLoginScreen.png';
		$path = 'Backend/Media/Images/Login/MockLoginScreen.png';
		$originalText3 = ')
				background-repeat: no-repeat;
			}';
		$originalText = $originalText1 . $originalText2 . $originalText3;
		$mockTextNode = $this->getMock(TextNode::class, array('evaluateChildNodes'), array($originalText));
		$this->assertEquals($originalText, $mockTextNode->evaluate($this->getMock(RenderingContextInterface::class)));

		$mockViewHelperResolver = $this->getMock(ViewHelperResolver::class);
		$mockViewHelperResolver->expects($this->once())->method('createViewHelperInstance')->with('f', 'uri.resource')->will($this->returnValue($mockViewHelper));
		$mockViewHelperResolver->expects($this->once())->method('getArgumentDefinitionsForViewHelper')->with($mockViewHelper)->will($this->returnValue(array()));

		$mockParsingState = $this->getMock(ParsingState::class);
		$mockParsingState->expects($this->once())->method('getViewHelperResolver')->will($this->returnValue($mockViewHelperResolver));

		$interceptor = new ResourceInterceptor();
		$node = $interceptor->process($mockTextNode, InterceptorInterface::INTERCEPT_TEXT, $mockParsingState);
		$viewHelperNode = $node->getChildNodes()[1];
		$textNode = $viewHelperNode->getArguments()['path'];
		$this->assertEquals($textNode->getText(), $path);
	}

	/**
	 * Return source parts and expected results.
	 *
	 * @return array
	 * @see supportedUrlsAreDetected
	 */
	public function supportedUrls() {
		return array(
			array( // mostly harmless
				'<link rel="stylesheet" type="text/css" href="',
				'../../../Public/Backend/Styles/Login.css',
				'">',
				'Backend/Styles/Login.css',
				'Acme.Demo'
			),
			array( // refer to another package
				'<link rel="stylesheet" type="text/css" href="',
				'../../../../Acme.OtherPackage/Resources/Public/Backend/Styles/FromOtherPackage.css',
				'">',
				'Backend/Styles/FromOtherPackage.css',
				'Acme.OtherPackage'
			),
			array( // refer to another package in different category
				'<link rel="stylesheet" type="text/css" href="',
				'../../../Plugins/Acme.OtherPackage/Resources/Public/Backend/Styles/FromOtherPackage.css',
				'">',
				'Backend/Styles/FromOtherPackage.css',
				'Acme.OtherPackage'
			),
			array( // path with spaces (ugh!)
				'<link rel="stylesheet" type="text/css" href="',
				'../../Public/Backend/Styles/With Spaces.css',
				'">',
				'Backend/Styles/With Spaces.css',
				'Acme.Demo'
			),
			array( // single quote around url and spaces
				'<link rel="stylesheet" type="text/css" href=\'',
				'../Public/Backend/Styles/With Spaces.css',
				'\'>',
				'Backend/Styles/With Spaces.css',
				'Acme.Demo'
			)
		);
	}

	/**
	 * @dataProvider supportedUrls
	 * @test
	 */
	public function supportedUrlsAreDetected($part1, $part2, $part3, $expectedPath, $expectedPackageKey) {
		$mockDummyNode = $this->getMock(NodeInterface::class);
		$mockPathNode = $this->getMock(NodeInterface::class);
		$mockPackageKeyNode = $this->getMock(NodeInterface::class);
		$mockViewHelper = $this->getMock(AbstractViewHelper::class);

		$originalText = $part1 . $part2 . $part3;
		$mockTextNode = $this->getMock(TextNode::class, array('evaluateChildNodes'), array($originalText));
		$this->assertEquals($originalText, $mockTextNode->evaluate($this->getMock(RenderingContextInterface::class)));

		$mockViewHelperResolver = $this->getMock(ViewHelperResolver::class);
		$mockViewHelperResolver->expects($this->once())->method('createViewHelperInstance')->with('f', 'uri.resource')->will($this->returnValue($mockViewHelper));
		$mockViewHelperResolver->expects($this->once())->method('getArgumentDefinitionsForViewHelper')->with($mockViewHelper)->will($this->returnValue(array()));

		$mockParsingState = $this->getMock(ParsingState::class);
		$mockParsingState->expects($this->once())->method('getViewHelperResolver')->will($this->returnValue($mockViewHelperResolver));

		$interceptor = new ResourceInterceptor();
		$node = $interceptor->process($mockTextNode, InterceptorInterface::INTERCEPT_TEXT, $mockParsingState);
		$viewHelperNode = $node->getChildNodes()[1];
		$textNode = $viewHelperNode->getArguments()['path'];
		$this->assertEquals($textNode->getText(), $expectedPath);
	}

}
