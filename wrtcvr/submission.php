<?php

require_once(dirname(__FILE__).'/classes/submission_form.php');

$form = new mod_wrtcvr_submission_form();

if($form->is_cancelled()) {
    echo 'Ah bon ?';
} else if($fromform = $form->get_data()) {
    echo 'Yay !';
} else {
    $data = new stdClass();
    $data->file_url =$file_url;
    $form->set_data($data);
    $form->display();
}
?>
