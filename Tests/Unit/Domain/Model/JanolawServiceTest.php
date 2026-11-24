<?php

namespace Janolaw\Janolawservice\Tests\Unit\Domain\Model;

use PHPUnit\Framework\Attributes\Test;
use Janolaw\Janolawservice\Domain\Model\JanolawService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JanolawServiceTest extends UnitTestCase
{
    /** @var JanolawService */
    protected JanolawService $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new JanolawService();
    }

    #[Test]
    public function userIdCanBeSet()
    {
        $value = 123;
        $this->subject->setUserId($value);
        self::assertEquals($value, $this->subject->getUserId());
    }

    #[Test]
    public function pdfCanBeSet()
    {
        $value = 'pdf_top';
        $this->subject->setPdf($value);
        self::assertEquals($value, $this->subject->getPdf());
    }

    #[Test]
    public function externalCanBeSet()
    {
        $value = 'external';
        $this->subject->setExternal($value);
        self::assertEquals($value, $this->subject->getExternal());
    }

    #[Test]
    public function typeCanBeSet()
    {
        $value = 'legal';
        $this->subject->setType($value);
        self::assertEquals($value, $this->subject->getType());
    }

    #[Test]
    public function languageCanBeSet()
    {
        $value = 'de';
        $this->subject->setLegacyLanguage($value);
        self::assertEquals($value, $this->subject->getLegacyLanguage());
    }

    #[Test]
    public function shopIdCanBeSet()
    {
        $value = 123;
        $this->subject->setShopId($value);
        self::assertEquals($value, $this->subject->getShopId());
    }
}
