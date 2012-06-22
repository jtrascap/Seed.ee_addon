<?php

/**
 * Seed Config File *
 * @package         seed_ee_addon
 * @version         0.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */

if ( ! defined('SEED_NAME'))
{
	define('SEED_NAME',         'Seed');
	define('SEED_CLASS_NAME',   'Seed');
	define('SEED_VERSION',      '0.1');
	define('SEED_DOCS',         '##');
	define('SEED_DEBUG',        TRUE);
	define('SEED_CACHE_TIME',    900);

	define('SEED_FIELD_PLUGIN_PATH', dirname(__FILE__).'/fieldtypes/');
	define('SEED_TEXT_SRC', dirname(__FILE__).'/seed/english/seed.kant.txt');
}

$config['name']    = SEED_NAME;
$config['version'] = SEED_VERSION;
