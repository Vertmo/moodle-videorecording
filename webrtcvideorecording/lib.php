<?php
/**
 * This file contains the moodle hooks for the webrtcvideorecording module.
 *
 * @package   mod_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

/**
 * Adds a new webrtcvideorecording instance
 *
 * @param stdClass $webrtcvideorecording
 * @return int instance id
 */
 function webrtcvideorecording_add_instance($webrtcvideorecording, $mform = null) {
     global $DB;

     $webrtcvideorecording->timemodified = time();
     $webrtcvideorecording->courseid = $webrtcvideorecording->course;
     $returnid = $DB->insert_record("webrtcvideorecording", $webrtcvideorecording);
     $webrtcvideorecording->id = $returnid;
     return $returnid;
 }

 /**
  * Given an object containing all the necessary data,
  * (defined by the form in mod_form.php) this function
  * will update an existing instance with new data.
  *
  * @param object $webrtcvideorecording
  * @return bool
  */
  function webrtcvideorecording_update_instance($webrtcvideorecording) {
      global $DB, $CFG;
      //require_once($CFG->dirroot.'/mod/webrtcvideorecording/locallib.php');

      $webrtcvideorecording->id = $webrtcvideorecording->instance;
      $webrtcvideorecording->timemodified = time();

      return $DB->update_record('webrtcvideorecording', $webrtcvideorecording);
  }

 /**
 * Deletes an webrtcvideorecording instance
 *
 * @todo s'occuper de la suppression (ou non) des vidéos
 *
 * @param $id
 */
 function webrtcvideorecording_delete_instance($id) {
     global $CFG, $DB;
     if(!webrtcvideorecording=$DB->get_record('webrtcvideorecording', array('id'=>$id))) {
         return false;
     }

     $result = true;

     //Ici s'occuper de la suppression des vidéos (voir le plugin assignment 2.2)

     return $result;
 }
