<?php

namespace Janolaw\Janolawservice\Tests\Unit\Domain\Model;

use Janolaw\Janolawservice\Domain\Model\JanolawService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JanolawServiceTest extends UnitTestCase
{
    /** @var JanolawService */
    protected $subject;

    public function setup(): void
    {
        parent::setup();
        $this->subject = new JanolawService('legal', 123, 123, 'de', 'pdf_top');
    }

    /**
     * @test
     */
    public function userIdCanBeSet()
    {
        $value = '123';
        $this->subject->setUserId($value);
        self::assertEquals($value, $this->subject->getUserId());
    }

    /**
     * @test
     */
    public function pdfCanBeSet()
    {
        $value = 'pdf_top';
        $this->subject->setPdf($value);
        self::assertEquals($value, $this->subject->getPdf());
    }

    /**
     * @test
     */
    public function externalCanBeSet()
    {
        $value = 'external';
        $this->subject->setExternal($value);
        self::assertEquals($value, $this->subject->getExternal());
    }

    /**
     * @test
     */
    public function typeCanBeSet()
    {
        $value = 'legal';
        $this->subject->setType($value);
        self::assertEquals($value, $this->subject->getType());
    }

    /**
     * @test
     */
    public function languageCanBeSet()
    {
        $value = 'de';
        $this->subject->setLegacyLanguage($value);
        self::assertEquals($value, $this->subject->getLegacyLanguage());
    }

    /**
     * @test
     */
    public function shopIdCanBeSet()
    {
        $value = '123';
        $this->subject->setShopId($value);
        self::assertEquals($value, $this->subject->getShopId());
    }
}
