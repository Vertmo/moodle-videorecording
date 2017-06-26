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

 require_once(dirname(dirname(dirname(dirname(__DIR__)))).'/config.php');

 class clean_temp extends \core\task\scheduled_task {
     public function get_name() {
         return get_string('cleantemp', 'wrtcvr');
     }

     public function execute() {
         $uploaddirectory = $CFG->tempdir;
         if ($handle = opendir($uploaddirectory)) {
             while (false !== ($entry = readdir($handle))) {
                 if (preg_match('/\d+_\d+\.mp4/', $entry)) {
                     if(time()-filemtime($uploaddirectory.'/'.$entry) > 60*60*2) unlink($uploaddirectory.'/'.$entry);
                 }
             }
             closedir($handle);
         }
     }
 }
?>
