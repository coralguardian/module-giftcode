<?php

namespace D4rk0snet\GiftCode\Command;

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
            GiftCodeSent::sendEvent($giftCode);
            OwnerScheduledCodeSentNotificationEvent::sendEvent($giftCode);

            WP_CLI::log("=> Code cadeau de la commande de ".$giftCode->getGiftAdoption()->getCustomer()->getEmail()." envoyé.");
        }
        WP_CLI::log("");

        return WP_CLI::success("Fin de l'envoi des codes cadeaux");
    }
}