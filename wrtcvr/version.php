<?php
/**
 * Defines the version and other meta-info about the plugin
 *
 * Setting the $plugin->version to 0 prevents the plugin from being installed.
 * See https://docs.moodle.org/dev/version.php for more info.
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_wrtcvr';
$plugin->version = 2017070401;
$plugin->release = 'v0.1';
$plugin->requires = 2014051200;
$plugin->maturity = MATURITY_ALPHA;
$plugin->cron = 0;
$plugin->dependencies = array();
