<?php

namespace Janolaw\Janolawservice\Domain\Model;

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
    protected $type = '';

    /**
     * shopid
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    protected $shopid;

    /**
     * userid
     *
     * @var int
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     */
    protected $userid;

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
    ) {
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
    public function setExternal(string $external): void
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
     */
    public function setType(string $type)
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
     */
    public function setUserId(int $userid)
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
     * @param int $shopid
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
    public function getLegacyLanguage(): string
    {
        return $this->legacyLanguage;
    }

    /**
     * Sets the legacyLanguage
     *
     * @param string $legacyLanguage
     */
    public function setLegacyLanguage(string $legacyLanguage)
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
     */
    public function setPdf(string $pdf)
    {
        $this->pdf = $pdf;
    }
}
