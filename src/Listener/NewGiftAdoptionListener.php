<?php

namespace D4rk0snet\GiftCode\Listener;

use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\Adoption\Enums\CoralAdoptionFilters;
use D4rk0snet\Adoption\Models\GiftAdoptionModel;
use D4rk0snet\CoralCustomer\Enum\CustomerType;
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
        $em = DoctrineService::getEntityManager();
        $newGiftAdoptionEntity = apply_filters(CoralAdoptionFilters::GET_GIFTADOPTION->value, $giftAdoptionEntityUUID);

        //@todo: la condition n'est plus exacte car avec le formulaire complet entreprise/particulier
        // on ne demande plus les info de l'ami avant le paiement

        if ($giftAdoptionModel->getCustomerModel()->getCustomerType() === CustomerType::COMPANY) {
            for ($i = 0; $i < $giftAdoptionModel->getQuantity(); $i++) {
                self::createGiftCode(1, $newGiftAdoptionEntity);
                // en mode entreprise on ne renseigne pas les friend au moment de créer la gift adoption
            }
        } else {
            // il n'y a qu'un ami pour les particuliers
            $friend = current($giftAdoptionModel->getFriends());
            $giftCode = self::createGiftCode($giftAdoptionModel->getQuantity(), $newGiftAdoptionEntity);

            $friendEntity = new Friend(
                friendFirstname: $friend->getFriendFirstname(),
                friendLastname: $friend->getFriendLastname(),
                friendEmail: $friend->getFriendEmail(),
                giftCode: $giftCode
            );

            $giftCode->setFriend($friendEntity);

            $em->persist($friendEntity);
        }

        $em->flush();
        do_action(CoralGiftActions::GIFTADOPTION_GIFTCODE_CREATED->value);
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

        return $giftCode;
    }
}