<?php

/**
 * This file contains the forms to create and edit an instance of this module
 *
 * @package   mod_webrtcvideorecording
 * @copyright 2017 UPMC
 */

 defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

 require_once($CFG->dirroot.'/course/moodleform_mod.php');
 require_once($CFG->dirroot.'/mod/webrtcvideorecording/lib.php');

 /**
  * webrtcvideorecording settings form
  *
  * @package   mod_webrtcvideorecording
  * @copyright 2017 UPMC
  */
 class mod_webrtcvideorecording_mod_form extends moodleform_mod {
     /**
      * Called to define this moodle form
      *
      * @todo Completer le formulaire
      *
      * @return void
      */
      public function definition() {
          global $CFG, $COURSE, $DB, $PAGE;
          $mform = $this->_form;
      }
 }
