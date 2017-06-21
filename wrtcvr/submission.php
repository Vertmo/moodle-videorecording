<?php

require_once(dirname(__FILE__).'/submission_form.php');

$form = new mod_wrtcvr_submission_form();

if($form->is_cancelled()) {
    $data = $form->get_data();
    $file = $CFG->dirroot.'/mod/wrtcvr/uploads/'.$_SESSION['file_url'];
    if(file_exists($file)) {
        unlink($file);
    }
    global $PAGE;
    $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo);
} else if($fromform = $form->get_data()) {
    global $CFG;
    global $DB;
    global $USER;

    $filepath = $CFG->dirroot.'/mod/wrtcvr/uploads/';

    $fs = get_file_storage();

    $fileinfo = array(
        'contextid' => $context->id,
        'component' => 'mod_wrtcvr',
        'filearea' => 'submission',
        'itemid' => intval(preg_split('/_/', $fromform->file_url)[0]),
        'filepath' => '/',
        'filename' => $fromform->file_url);

    $fileid = $fs->create_file_from_pathname($fileinfo, $filepath.$fromform->file_url)->get_id();
    unlink($filepath.$fromform->file_url);

    $record = new stdClass();
    $record->assignment = $wrtcvr->id;
    $record->userid = $USER->id;
    $record->fileid = $fileid;
    $DB->insert_record('wrtcvr_submissions', $record);

    global $PAGE;
    $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo, get_string('videohasbeenuploaded', 'mod_wrtcvr'), 10);
} else {
    $data = new stdClass();
    $data->file_url = $_SESSION['file_url'];
    $form->set_data($data);
}
?>
