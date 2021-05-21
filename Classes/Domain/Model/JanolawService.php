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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * JanolawService
 */
class JanolawService extends AbstractEntity
{

    /**
     * type
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    protected $type = 0;

    /**
     * shopid
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    protected $shopid = null;

    /**
     * userid
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    protected $userid = null;

    /**
     * external
     *
     * @var string
     */
    protected $external = '';

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
     * JanolawService constructor.
     *
     * @param int|string $type
     * @param int|null $shopid
     * @param int|null $userid
     * @param string $legacyLanguage
     * @param string $pdf
     */
    public function __construct(
        $type,
        ?int $shopid,
        ?int $userid,
        string $legacyLanguage,
        string $pdf
    )
    {
        $this->type = $type;
        $this->shopid = $shopid;
        $this->userid = $userid;
        $this->legacyLanguage = $legacyLanguage;
        $this->pdf = $pdf;
    }


    /**
     * @return string
     */
    public function getExternal(): string
    {
        return $this->external;
    }

    /**
     * @param string $external
     */
    public function setExternal( string $external ): void
    {
        $this->external = $external;
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
     *
     * @return void
     */
    public function setType( string $type )
    {
        $this->type = $type;
    }

    /**
     * Returns the userid
     *
     * @return int userid
     */
    public function getUserId(): ?int
    {
        return $this->userid;
    }

    /**
     * Sets the userid
     *
     * @param int $userid
     *
     * @return void
     */
    public function setUserId( int $userid )
    {
        $this->userid = $userid;
    }

    /**
     * Returns the shopid
     *
     * @return int shopid
     */
    public function getShopId(): ?int
    {
        return $this->shopid;
    }

    /**
     * Sets the shopid
     *
     * @param int shopid
     *
     * @return void
     */
    public function setShopId( $shopid )
    {
        $this->shopid = $shopid;
    }

    /**
     * Returns the legacyLanguage
     *
     * @return string $legacyLanguage
     */
    public function getLegacyLanguage(): string
    {
        return $this->legacyLanguage;
    }

    /**
     * Sets the legacyLanguage
     *
     * @param string $legacyLanguage
     *
     * @return void
     */
    public function setLegacyLanguage( string $legacyLanguage )
    {
        $this->legacyLanguage = $legacyLanguage;
    }

    /**
     * Returns the pdf
     *
     * @return string $pdf
     */
    public function getPdf(): string
    {
        return $this->pdf;
    }

    /**
     * Sets the legacyLanguage
     *
     * @param string $pdf
     *
     * @return void
     */
    public function setPdf( string $pdf )
    {
        $this->pdf = $pdf;
    }

}
