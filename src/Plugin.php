<?php

namespace D4rk0snet\GiftCode;

use D4rk0snet\GiftCode\API\GetAdoptionWithGiftCode;

class Plugin
{
    public static function launchActions()
    {
        do_action(\Hyperion\RestAPI\Plugin::ADD_API_ENDPOINT_ACTION, new GetAdoptionWithGiftCode());
    }
}