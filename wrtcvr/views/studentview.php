<?php
/**
 * Prints a the view of a student
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

 defined('MOODLE_INTERNAL') || die();

 if($wrtcvr->allowsubmissionsfromdate > time()) {
     global $PAGE;
     $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
     redirect($urltogo, get_string('toearlytosubmit', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate), 10);
 }

$previousvideo = $DB->get_record('wrtcvr_submissions', array('assignment'=>$wrtcvr->id, 'userid'=>$USER->id));
require_once(dirname(dirname(__FILE__)).'/submission.php');

// Output starts here.
echo $OUTPUT->header();

echo '<link rel="stylesheet" type="text/css" href="style.css">';

// Conditions to show the intro can change to look for own settings or whatever.
if ($wrtcvr->intro) {
    echo $OUTPUT->box(format_module_intro('wrtcvr', $wrtcvr, $cm->id), 'generalbox mod_introbox', 'wrtcvrintro');
}

echo $OUTPUT->heading(get_string('modulename', 'wrtcvr'));

if($previousvideo) echo '<p class = "alert alert-info alert-block">'.get_string('alreadysubmittedvideo', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate).'</p>';
else {
    if($wrtcvr->duedate > time()) {
        echo '<p class = "alert alert-info alert-block">'.get_string('nosubmittedvideo', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate).'</p>';
    }
    else echo '<p class = "alert alert-danger alert-block">'.get_string('latetosubmitvideo', 'mod_wrtcvr').date('d.m.y', $wrtcvr->duedate).'</p>';
}

echo '<script>fileName="'.$_SESSION['file_url'].'"</script>';

if($wrtcvr->withvideo) echo '<p>'.get_string('audioandvideo', 'mod_wrtcvr').'</p>';
else echo '<p>'.get_string('audioonly', 'mod_wrtcvr').'</p>';
?>

<div id="webrtcwindow">
    <?php
        if($wrtcvr->withvideo) echo '<video id="video" width="640" height="360" controls>Votre navigateur ne supporte pas la video</video>';
        else echo '<audio id="video" controls>Votre navigateur ne supporte pas la video</audio>';
    ?>
    <br/>
    <button id="btnStart" class="btn btn-secondary" type="button">START RECORDING</button>
    <button id="btnStop" class="btn btn-secondary" type="button">STOP RECORDING</button>
</div>

<script src="https://cdn.WebRTC-Experiment.com/RecordRTC.js"></script>
<script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>

<script>
    var webrtcwindow = document.getElementById("webrtcwindow");
    video = document.getElementById("video");

    var btnStart = document.getElementById("btnStart");
    var btnStop = document.getElementById("btnStop");

    var recordRTC;

    function successCallback(stream) {
        video.src = window.URL.createObjectURL(stream);
        video.play();
        <?php
            if($wrtcvr->withvideo) echo 'var options = {
                mimeType: "video/webm\;codecs=h264",
                audioBitsPerSecond: 128000,
                videoBitsPerSecond: 128000,
                video: {
                    width: 640,
                    height: 360
                },
            };';
            else echo 'var options = {
                type: "audio",
                recorderType: StereoAudioRecorder
            };';
        ?>
        recordRTC = RecordRTC(stream, options);
        recordRTC.startRecording();
    }

    function errorCallback(error) {
        window.alert("Le navigateur n'a pas pu détecter le bon périphérique d'enregistrement");
    }

    btnStart.onclick = function() {
        var mediaContraints;
        <?php
            if($wrtcvr->withvideo) echo 'var mediaConstraints = {
                audio: true,
                video: {
                    width: {exact: 640},
                    height: {exact: 360}
                }
            };';
            else echo 'var mediaConstraints = {
                audio: true,
                video: false
            };';
        ?>
        navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
    }

    btnStop.onclick = function() {
        recordRTC.stopRecording(function(videoURL) {
            video.src = videoURL;
            var blob = recordRTC.getBlob();

            var fileType = 'video';

            var formData = new FormData();
            formData.append(fileType + '-filename', fileName);
            formData.append(fileType + '-blob', blob);

            xhr('save.php', formData, function (fName) {});

            function xhr(url, data, callback) {
                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        callback(request.responseText);
                    }
                };
                request.open('POST', url);
                request.send(data);
            }
        });
    };
</script>

<?php
if($previousvideo) {
    $previousvideofile = $DB->get_record('files', array('id'=>$previousvideo->fileid));
    $previousvideofileurl = moodle_url::make_pluginfile_url($previousvideofile->contextid, $previousvideofile->component, $previousvideofile->filearea, $previousvideofile->itemid, $previousvideofile->filepath, $previousvideofile->filename);
    echo '<script>video.src = "'.$previousvideofileurl.'";</script>';
}
$form->display();

// Finish the page.
echo $OUTPUT->footer();
 ?>
