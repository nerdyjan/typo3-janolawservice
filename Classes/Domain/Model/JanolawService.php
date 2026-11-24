<?php

declare(strict_types=1);

namespace Janolaw\Janolawservice\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Cached janolaw document (fallback if the janolaw server is not reachable)
 */
class JanolawService extends AbstractEntity
{
    protected string $type = '';
    protected ?int $shopid = null;
    protected ?int $userid = null;
    protected string $external = '';
    protected string $legacyLanguage = '';
    protected string $pdf = '';

    public function getExternal(): string
    {
        return $this->external;
    }

    public function setExternal(string $external): void
    {
        $this->external = $external;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getUserId(): ?int
    {
        return $this->userid;
    }

    public function setUserId(int $userid): void
    {
        $this->userid = $userid;
    }

    public function getShopId(): ?int
    {
        return $this->shopid;
    }

    public function setShopId(int $shopid): void
    {
        $this->shopid = $shopid;
    }

    public function getLegacyLanguage(): string
    {
        return $this->legacyLanguage;
    }

    public function setLegacyLanguage(string $legacyLanguage): void
    {
        $this->legacyLanguage = $legacyLanguage;
    }

    public function getPdf(): string
    {
        return $this->pdf;
    }

    public function setPdf(string $pdf): void
    {
        $this->pdf = $pdf;
    }
}
