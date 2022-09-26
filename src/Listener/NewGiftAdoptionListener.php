<?php

namespace D4rk0snet\GiftCode\Listener;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\CoralAdoptionFilters;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use D4rk0snet\GiftCode\Enum\CoralGiftActions;
use D4rk0snet\GiftCode\Service\GiftCodeService;
use Exception;
use Hyperion\Doctrine\Service\DoctrineService;

class NewGiftAdoptionListener
{
    /**
     * @throws Exception
     */
    public static function doAction(GiftAdoptionModel $giftAdoptionModel, string $giftAdoptionEntityUUID) : void
    {

        $newGiftAdoptionEntity = apply_filters(CoralAdoptionFilters::GET_GIFTADOPTION->value, $giftAdoptionEntityUUID);

        for ($i = 0; $i < $giftAdoptionModel->getQuantity(); $i++) {
            self::createGiftCode(1, $newGiftAdoptionEntity);
        }

        do_action(CoralGiftActions::GIFTADOPTION_GIFTCODE_CREATED->value, $newGiftAdoptionEntity);
    }

    /**
     * @throws Exception
     */
    private static function createGiftCode(int $quantity, GiftAdoption $newGiftAdoptionEntity) : GiftCodeEntity
    {
        $em = DoctrineService::getEntityManager();

        $giftCode = new GiftCodeEntity(
            giftCode: GiftCodeService::createGiftCode(bin2hex(random_bytes(20)), $newGiftAdoptionEntity->getAdoptedProduct()),
            giftAdoption: $newGiftAdoptionEntity,
            productQuantity: $quantity
        );
        $em->persist($giftCode);
        $em->flush();

        return $giftCode;
    }
}