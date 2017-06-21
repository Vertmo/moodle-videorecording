<?php
/**
 * Prints a the view of a student
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

$previousvideo = $DB->get_record('wrtcvr_submissions', array('assignment'=>$wrtcvr->id, 'userid'=>$USER->id));
require_once(dirname(dirname(__FILE__)).'/submission.php');

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($wrtcvr->intro) {
    echo $OUTPUT->box(format_module_intro('wrtcvr', $wrtcvr, $cm->id), 'generalbox mod_introbox', 'wrtcvrintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading(get_string('modulename', 'wrtcvr'));

if($previousvideo) echo '<p class = "alert alert-info alert-block">'.get_string('alreadysubmittedvideo', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate).'</p>';
else echo '<p class = "alert alert-info alert-block">'.get_string('nosubmittedvideo', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate).'</p>';

echo '<script>fileName="'.$_SESSION['file_url'].'"</script>';
echo file_get_contents(dirname(__FILE__).'/index.html');

if($previousvideo) {
    $previousvideofile = $DB->get_record('files', array('id'=>$previousvideo->fileid));
    $previousvideofileurl = moodle_url::make_pluginfile_url($previousvideofile->contextid, $previousvideofile->component, $previousvideofile->filearea, $previousvideofile->itemid, $previousvideofile->filepath, $previousvideofile->filename);
    echo '<script>video.src = "'.$previousvideofileurl.'";</script>';
}
$form->display();

// Finish the page.
echo $OUTPUT->footer();
 ?>
