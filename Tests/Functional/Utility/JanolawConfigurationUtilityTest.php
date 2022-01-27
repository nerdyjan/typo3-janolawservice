<?php

namespace Janolaw\Janolawservice\Tests\Functional\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class JanolawConfigurationUtilityTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/janolawservice',
    ];

    /**
     * @test
     */
    public function hasValidUserDataForExtConfigVersion()
    {
        $configUtil = new JanolawConfigurationUtility();
        $extConfig = new ExtensionConfiguration();
        $_extConfig = $extConfig->get(
            'janolawservice'
        );

        //we expect invalid for unset default values
        $result = $configUtil->hasValidUserData(
            $_extConfig['user_id'],
            $_extConfig['shop_id']
        );
        self::assertFalse($result);
    }
}
