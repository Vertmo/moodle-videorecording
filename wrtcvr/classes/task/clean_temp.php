<?php
/**
 * Task used to clean the content of the /uploads folder once in a while
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 *
 * @todo: coder l'effacement des fichiers
 */

 namespace mod_wrtcvr\task;

 class clean_temp extends \core\task\scheduled_task {
     public function get_name() {
         return get_string('cleantemp', 'wrtcvr');
     }

     public function execute() {
         // Code goes here
     }
 }
?>
