<?php

namespace D4rk0snet\GiftCode\API;

use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use WP_REST_Request;
use WP_REST_Response;

class GetAdoptionWithGiftCode extends APIEnpointAbstract
{
    public const GIFT_CODE_PARAM = "gift_code";
    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $giftCode = $request->get_param(self::GIFT_CODE_PARAM);
        if ($giftCode === null) {
            return APIManagement::APIError('Missing gift code GET parameter', 400);
        }

        /** @var GiftCodeEntity[] $giftCodeEntity */
        $giftCodeEntity = DoctrineService::getEntityManager()->getRepository(GiftCodeEntity::class)->findBy(["giftCode" => $giftCode]);
        if(count($giftCodeEntity) === 0) {
            return APIManagement::APINotFound();
        }

        return APIManagement::APIOk(["uuid" => current($giftCodeEntity)->getGiftAdoption()->getUuid()]);
    }

    public static function getMethods(): array
    {
        return ["GET"];
    }

    public static function getPermissions(): string
    {
        return "__return_true";
    }

    public static function getEndpoint(): string
    {
        return "giftcode/getAdoption";
    }
}