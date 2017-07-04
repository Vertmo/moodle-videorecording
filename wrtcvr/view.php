<?php
/**
 * Prints a particular instance of wrtcvr
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... wrtcvr instance ID - it should be named as the first character of the module.

if ($id) {
    $_SESSION['wrtcvr_currentcourseid'] = $id;
    $cm         = get_coursemodule_from_id('wrtcvr', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $wrtcvr  = $DB->get_record('wrtcvr', array('id' => $cm->instance), '*', MUST_EXIST);

    global $USER;
    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    $_SESSION['file_url'] = $USER->lastname.'_'.$USER->firstname.'_'.$USER->username.'_'.date('dMY_His', $timestamp).'.webm';
} else if ($n) {
    $wrtcvr  = $DB->get_record('wrtcvr', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $wrtcvr->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('wrtcvr', $wrtcvr->id, $course->id, false, MUST_EXIST);
} else {
    $id = $_SESSION['wrtcvr_currentcourseid'];
    $cm         = get_coursemodule_from_id('wrtcvr', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $wrtcvr  = $DB->get_record('wrtcvr', array('id' => $cm->instance), '*', MUST_EXIST);
}

$context = context_module::instance($cm->id);

require_login($course, true, $cm);
require_capability('mod/wrtcvr:view', $context);

// Print the page header.
$PAGE->set_url('/mod/wrtcvr/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($wrtcvr->name));
$PAGE->set_heading(format_string($course->fullname));

if(has_capability('mod/wrtcvr:grade', $context)) {
    require_once(dirname(__FILE__).'/views/teacherview.php');
} else if(has_capability('mod/wrtcvr:submit', $context)) {
    require_once(dirname(__FILE__).'/views/studentview.php');
} else {
    echo $OUTPUT->header();
    echo $OUTPUT->footer();
}
