<?php
/**
 * Plugin Name: Adopte un corail / Gestion des codes cadeaux
 * Plugin URI:
 * Description: Gestion des codes cadeaux
 * Version: 0.1
 * Requires PHP: 8.1
 * Author: Benoit DELBOE & Grégory COLLIN
 * Author URI:
 * Licence: GPLv2
 */
add_filter(\Hyperion\Doctrine\Plugin::ADD_ENTITIES_FILTER, function (array $entityPaths) {
    $entityPaths[] = __DIR__."/src/Entity";

    return $entityPaths;
});
add_action('cli_init', [\D4rk0snet\GiftCode\Plugin::class,'addCliCommand']);
add_action('plugins_loaded', [\D4rk0snet\GiftCode\Plugin::class,'launchActions']);