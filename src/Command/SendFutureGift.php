<?php

namespace D4rk0snet\GiftCode\Command;

use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Coralguardian\Event\GiftCodeSent;
use D4rk0snet\Coralguardian\Event\OwnerScheduledCodeSentNotificationEvent;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use D4rk0snet\GiftCode\Repository\GiftCodeRepository;
use Hyperion\Doctrine\Service\DoctrineService;
use WP_CLI;

class SendFutureGift
{
    public static function runCommand()
    {
        WP_CLI::log("== Lancement du script d'envoi des codes cadeaux ==\n");

        /** @var GiftCodeRepository $repository */
        $repository = DoctrineService::getEntityManager()->getRepository(GiftCodeEntity::class);

        /** @var GiftCodeEntity[] $giftCodesTosendToday */
        $giftCodesTosendToday = $repository->getAllGiftCodeToDealWithToday();
        if(count($giftCodesTosendToday) === 0) {
            return WP_CLI::success("Aucun code à envoyer aujourd'hui");
        }

        foreach($giftCodesTosendToday as $giftCode)
        {
            // @bug: Pour une raison obscure, doctrine n'arrive pas à récupérer le friend dans l'association bi-directionnelle one-to-one
            $friend = DoctrineService::getEntityManager()->getRepository(Friend::class)->findOneBy(['giftCode' => $giftCode->getUuid()]);
            GiftCodeSent::sendEvent($giftCode, $friend);
            OwnerScheduledCodeSentNotificationEvent::sendEvent($giftCode);

            WP_CLI::log("=> Code cadeau de la commande de ".$giftCode->getGiftAdoption()->getCustomer()->getEmail()." envoyé.");
        }
        WP_CLI::log("");

        return WP_CLI::success("Fin de l'envoi des codes cadeaux");
    }
}