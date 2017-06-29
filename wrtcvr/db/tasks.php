<?php
/**
 * The plugin's tasks
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

$tasks = array(
    array(
        'classname'=>'mod_wrtcvr\task\clean_temp',
        'blocking'=>0,
        'minute'=>'0',
        'hour'=>'05',
        'day'=>'*',
        'dayoftheweek'=>'*',
        'month'=>'*'
    )
);
?>
