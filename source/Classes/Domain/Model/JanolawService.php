<?php
namespace Janolaw\Janolawservice\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * JanolawService
 */
class JanolawService extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * type
     *
     * @var string
     * @validate NotEmpty
     */
    protected $type = 0;

    /**
     * shopid
     *
     * @var int
     * @validate NotEmpty
     */
    protected $shopid = null;

    /**
     * userid
     *
     * @var int
     * @validate NotEmpty
     */
    protected $userid = null;

    /**
     * content
     *
     * @var string
     */
    protected $content = '';
    
    /**
     * legacyLanguage
     *
     * @var string
     */
    protected $legacyLanguage = '';

    /**
     * pdf
     *
     * @var string
     */
    protected $pdf = '';

    /**
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets the content
     *
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Returns the type
     *
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Sets the type
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Returns the userid
     *
     * @return int userid
     */
    public function getUserId()
    {
        return $this->userid;
    }
    
    /**
     * Sets the userid
     *
     * @param int $userid
     * @return void
     */
    public function setUserId($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Returns the shopid
     *
     * @return int shopid
     */
    public function getShopId()
    {
        return $this->shopid;
    }

    /**
     * Sets the shopid
     *
     * @param int shopid
     * @return void
     */
    public function setShopId($shopid)
    {
        $this->shopid = $shopid;
    }

    /**
     * Returns the legacyLanguage
     *
     * @return string $legacyLanguage
     */
    public function getLegacyLanguage()
    {
        return $this->legacyLanguage;
    }

    /**
     * Sets the legacyLanguage
     *
     * @param string $legacyLanguage
     * @return void
     */
    public function setLegacyLanguage($legacyLanguage)
    {
        $this->legacyLanguage = $legacyLanguage;
    }

/**
 * Returns the pdf
 *
 * @return string $pdf
 */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Sets the legacyLanguage
     *
     * @param string $legacyLanguage
     * @return void
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
    }

}