<?php
/**
 * Prints a particular instance of wrtcvr
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... wrtcvr instance ID - it should be named as the first character of the module.

if ($id) {
    session_start();
    $_SESSION['wrtcvr_currentcourseid'] = $id;
    $cm         = get_coursemodule_from_id('wrtcvr', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $wrtcvr  = $DB->get_record('wrtcvr', array('id' => $cm->instance), '*', MUST_EXIST);
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

require_login($course, true, $cm);

/*$event = \mod_wrtcvr\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $wrtcvr);
$event->trigger();*/

// Print the page header.
$PAGE->set_url('/mod/wrtcvr/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($wrtcvr->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($wrtcvr->intro) {
    echo $OUTPUT->box(format_module_intro('wrtcvr', $wrtcvr, $cm->id), 'generalbox mod_introbox', 'wrtcvrintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading(get_string('modulename', 'wrtcvr'));

$date = new DateTime();
$timestamp = $date->getTimestamp();
global $USER;
$userid = $USER->id;
$file_url = $timestamp.'_'.$userid.'.mp4';
echo '<script>fileName="'.$file_url.'"</script>';
echo file_get_contents(dirname(__FILE__).'/assets/index.html');

require_once(dirname(__FILE__).'/submission.php');

// Finish the page.
echo $OUTPUT->footer();
