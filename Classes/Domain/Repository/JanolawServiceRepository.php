<?php

declare(strict_types=1);

namespace Janolaw\Janolawservice\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for JanolawServices
 *
 * @extends Repository<\Janolaw\Janolawservice\Domain\Model\JanolawService>
 */
class JanolawServiceRepository extends Repository
{
    public function findByJanolawServiceParams($language, $type, $userid, $shopid, $pdf): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('legacyLanguage', $language),
                $query->equals('userid', (int)$userid),
                $query->equals('shopid', (int)$shopid),
                $query->equals('type', $type),
                $query->equals('pdf', $pdf)
            )
        );

        return $query->execute();
    }
}
