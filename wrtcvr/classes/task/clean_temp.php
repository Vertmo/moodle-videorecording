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
         $uploaddirectory = dirname(dirname(__DIR__)).'/uploads/';
         if ($handle = opendir($uploaddirectory)) {
             while (false !== ($entry = readdir($handle))) {
                 if ($entry != "." && $entry != ".." && $entry != "README.txt") {
                     if(time()-filemtime($uploaddirectory.$entry) > 60*60*2) unlink($uploaddirectory.$entry);
                 }
             }
             closedir($handle);
         }
     }
 }
?>
