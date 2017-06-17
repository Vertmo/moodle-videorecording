<?php

require_once(dirname(__FILE__).'/submission_form.php');

$form = new mod_wrtcvr_submission_form();

if($form->is_cancelled()) {
    global $PAGE;
    $urltogo= new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo);
} else if($fromform = $form->get_data()) {
    echo 'Yay enfin !';
    global $CFG;
    /*global $PAGE;
    $urltogo= new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo);*/
} else {
    $data = new stdClass();
    $data->file_url =$file_url;
    $form->set_data($data);
    $form->display();
}
?>
