<?php

namespace Janolaw\Janolawservice\Utility;

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * helper Class for user function in ext_conf_template.txt
 */
class ExtensionConfigUtility
{
    /**
     * Checks the backend configuration and shows a message if necessary.
     * contructor Injection is not working with type=user in ext_conf_template
     * because of this problem we Us GeneralUtility twice
     */
    public function checkUserData(): string
    {
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        return GeneralUtility::makeInstance(JanolawConfigurationUtility::class, $requestFactory)->checkUserData();
    }
}
