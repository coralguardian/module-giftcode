<?php

namespace D4rk0snet\GiftCode\API;

use D4rk0snet\Adoption\Entity\GiftAdoption;
use D4rk0snet\GiftCode\Entity\GiftCodeEntity;
use D4rk0snet\GiftCode\Service\GiftCodeService;
use Hyperion\Doctrine\Service\DoctrineService;
use Hyperion\RestAPI\APIEnpointAbstract;
use Hyperion\RestAPI\APIManagement;
use WP_REST_Request;
use WP_REST_Response;

class GetGiftCodesFile extends APIEnpointAbstract
{
    public const ADOPTION_PARAM = "stripePaymentIntentId";

    public static function callback(WP_REST_Request $request): WP_REST_Response
    {
        $stripePaymentIntentId = $request->get_param(self::ADOPTION_PARAM);
        if ($stripePaymentIntentId === null) {
            return APIManagement::APIError('Missing stripePaymentIntentId GET parameter', 400);
        }
        /** @var GiftAdoption $adoptionEntity */
        $adoptionEntity = DoctrineService::getEntityManager()
            ->getRepository(GiftAdoption::class)
            ->findOneBy(['stripePaymentIntentId' => $stripePaymentIntentId]);

        if ($adoptionEntity === null) {
            return APIManagement::APINotFound();
        }

        // @todo: check if adoption payée
        $filename = "giftcodes_" . $adoptionEntity->getUuid() . ".txt";
        $filePath = __DIR__ . "/../../tmp/" . $filename;

        $label = $adoptionEntity->getLang()->value === "fr" ? "Codes cadeaux" : "Gift codes";
        $codes = array_map(function (GiftCodeEntity $code) {
            return $code->getGiftCode();
        }, $adoptionEntity->getGiftCodes()->toArray());

        GiftCodeService::exportAsTxt([$label], $codes, " ", $filePath);

        $response = APIManagement::APIClientDownloadWithURL($filePath, $filename);
        unlink($filePath);
        return $response;
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
        return "giftcode/getFile";
    }
}