<?php

namespace Janolaw\Janolawservice\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for JanolawServices
 */
class JanolawServiceRepository extends Repository
{
    public function findByJanolawServiceParams($language, $type, $userid, $shopid, $pdf)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('legacy_language', $language),
                $query->equals('userid', $userid),
                $query->equals('shopid', $shopid),
                $query->equals('type', $type),
                $query->equals('pdf', $pdf)
            )
        );

        return $query->execute();
    }
}
