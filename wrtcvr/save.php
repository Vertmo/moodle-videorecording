<?php

/**
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

 foreach(array('video', 'audio') as $type) {
     if (isset($_FILES["${type}-blob"])) {

         echo 'uploads/';

         $fileName = $_POST["${type}-filename"];
         $uploadDirectory = $CFG->tempdir.'/'.$fileName;

         if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
             echo(" problem moving uploaded file");
         }

         echo($fileName);
     }
 }
 ?>
