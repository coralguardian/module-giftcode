<?php

namespace D4rk0snet\GiftCode\Repository;

use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;

class GiftCodeRepository extends EntityRepository
{
    /**
     * @return GiftCodeEntity[]
     */
    public function getAllGiftCodeToDealWithToday()
    {
        $query = $this->getEntityManager()->createQuery(
            '
            SELECT gc
            FROM \D4rk0snet\Adoption\Entity\GiftCode gc
            JOIN gc.giftAdoption ga
            WHERE ga.sendOn = :today 
            AND WHERE ga.sendToFriend = :sendToFriend'
        );
        $query->setParameter(':today', (new DateTime())->format('Y-m-d'));
        $query->setParameter(':sendToFriend', true);

        return $query->getResult();
    }
}