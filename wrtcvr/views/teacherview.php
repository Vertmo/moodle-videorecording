<?php
/**
 * Prints a the view of a student
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/gradelib.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $gradeid => $grade) {
        $matches = array();
        preg_match('/\d+/', $gradeid, $matches);
        $userid = $matches[0];
        $date = new DateTime();
        if($DB->get_record('wrtcvr_grades', array('userid'=>$userid, 'assignment'=>$wrtcvr->id))) {
            $DB->set_field('wrtcvr_grades', 'grade', $grade, array('userid'=>$userid, 'assignment'=>$wrtcvr->id));
            $DB->set_field('wrtcvr_grades', 'timemodified', $date->getTimeStamp(), array('userid'=>$userid, 'assignment'=>$wrtcvr->id));
        } else {
            $record = new stdClass();
            $record->assignment = $wrtcvr->id;
            $record->userid = $userid;
            $record->timecreated = $date->getTimeStamp();
            $record->timemodified = $record->timecreated;
            $record->grader = $USER->id;
            $record->grade = intval($grade);
            $DB->insert_record('wrtcvr_grades', $record);
        }
        wrtcvr_update_grades($wrtcvr, $userid);
    }
    global $PAGE;
    $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo, get_string('gradeshavebeenupdated', 'mod_wrtcvr'), 10);
} else {
    echo $OUTPUT->header();

    echo '<div id="webrtcwindow">';
    if($wrtcvr->withvideo) echo '<video id="video" width="640" height="360" controls>Votre navigateur ne supporte pas la video</video>';
    else echo '<audio id="video" controls>Votre navigateur ne supporte pas la video</audio>';
    echo '</div>';
    echo '<script>function setVideoSrc(videoURL) {
            var video = document.getElementById("video");
            video.src = videoURL;
        }</script>';

    $enrolledusers = get_enrolled_users($context);
    $userids = array_map(function($obj) {return $obj->id;}, $enrolledusers);
    $gradeinfo = grade_get_grades($course->id, 'mod', 'wrtcvr', $wrtcvr->id, $userids);
    $table = new html_table();
    if($gradeinfo->items) $table->head = array(get_string('teachertableauthor', 'mod_wrtcvr'), get_string('teachertabledate', 'mod_wrtcvr'), get_string('teachertablewatch', 'mod_wrtcvr'), get_string('download'), get_string('teachertablegrade', 'mod_wrtcvr').' (/'.intval($gradeinfo->items[0]->grademax).')');
    else $table->head = array(get_string('teachertableauthor', 'mod_wrtcvr'), get_string('teachertabledate', 'mod_wrtcvr'), get_string('teachertablewatch', 'mod_wrtcvr'), get_string('download'));
    echo '<form action="#" method="post">';

    foreach($enrolledusers as $user) {
        $date = get_string('teachertablenosubmittedvideo', 'mod_wrtcvr');
        $button_watch = get_string('teachertablenosubmittedvideo', 'mod_wrtcvr');
        $button_download = get_string('teachertablenosubmittedvideo', 'mod_wrtcvr');
        $grade = get_string('teachertablenosubmittedvideo', 'mod_wrtcvr');
        $submission = $DB->get_record('wrtcvr_submissions', array('assignment'=>$wrtcvr->id, 'userid'=>$user->id));

        if($submission) {
            $file = $DB->get_record('files', array('id'=>$submission->fileid));
            $date = date('d/m/Y H:i:s', $file->timemodified);
            $fileurl = moodle_url::make_pluginfile_url($file->contextid, $file->component, $file->filearea, $file->itemid, $file->filepath, $file->filename);
            $button_watch = '<input type="button" class="btn btn-secondary" value="'.get_string('teachertablewatch', 'mod_wrtcvr').'" onclick="setVideoSrc(\''.$fileurl.'\')">';
            $button_download = '<a class="btn btn-secondary" href="'.$fileurl.'">'.get_string('download').'</a>';

            if($gradeinfo->items) {
                if($currentgrade = $gradeinfo->items[0]->grades[$user->id]->grade) $grade = '<input type="number" name="grade_'.$user->id.'" value="'.$currentgrade.'" min="0" max="'.intval($gradeinfo->items[0]->grademax).'">';
                else $grade = '<input type="number" name="grade_'.$user->id.'" value="0" min="0" max="'.intval($gradeinfo->items[0]->grademax).'">';
            }
        }
        if($gradeinfo->items) $table->data[] = array($user->firstname.' '.$user->lastname, $date, $button_watch, $button_download, $grade);
        else $table->data[] = array($user->firstname.' '.$user->lastname, $date, $button_watch, $button_download);
    }
    echo html_writer::table($table);
    if($gradeinfo->items) echo '<input id="id_submitbutton" type="submit" class="btn btn-primary" value="'.get_string('updategrades', 'mod_wrtcvr').'"/>';
    echo '</form>';

    echo $OUTPUT->footer();
}
?>
