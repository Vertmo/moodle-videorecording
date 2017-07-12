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
     redirect($urltogo, get_string('toearlytosubmit', 'mod_wrtcvr').date('d.m.y H:i', $wrtcvr->duedate), 10);
 }

$previousvideo = $DB->get_record('wrtcvr_submissions', array('assignment'=>$wrtcvr->id, 'userid'=>$USER->id));
require_once(dirname(dirname(__FILE__)).'/submission.php');

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($wrtcvr->intro) echo $OUTPUT->box(format_module_intro('wrtcvr', $wrtcvr, $cm->id), 'generalbox mod_introbox', 'wrtcvrintro');

echo $OUTPUT->heading(get_string('modulename', 'wrtcvr'));

if($previousvideo) {
    $previousvideofile = $DB->get_record('files', array('id'=>$previousvideo->fileid));
    $previousvideofileurl = moodle_url::make_pluginfile_url($previousvideofile->contextid, $previousvideofile->component, $previousvideofile->filearea, $previousvideofile->itemid, $previousvideofile->filepath, $previousvideofile->filename);
    if($wrtcvr->duedate > time()) echo '<p class = "alert alert-success alert-block">'.get_string('alreadysubmittedvideo', 'mod_wrtcvr').date('d.m.y H:i', $wrtcvr->duedate).'</p>';
    else echo '<p class = "alert alert-success alert-block">'.get_string('aftersubmittedvideo', 'mod_wrtcvr').'</p>';
    echo '<a class="btn btn-secondary" href="'.$previousvideofileurl.'">'.get_string('download_previous', 'mod_wrtcvr').'</a><br/>';
}
else {
    if($wrtcvr->duedate > time()) echo '<p class = "alert alert-info alert-block">'.get_string('nosubmittedvideo', 'mod_wrtcvr').date('d.m.y H:i', $wrtcvr->duedate).'</p>';
    else echo '<p class = "alert alert-danger alert-block">'.get_string('latetosubmitvideo', 'mod_wrtcvr').date('d.m.y H:i', $wrtcvr->duedate).'</p>';
}

echo '<script>fileName="'.$_SESSION['file_url'].'"</script>';
echo '<br/>';

if($wrtcvr->withvideo) echo '<h3>'.get_string('audioandvideo', 'mod_wrtcvr').'</h3>';
else echo '<h3>'.get_string('audioonly', 'mod_wrtcvr').'</h3>';
echo '<br/>';
?>

<div id="webrtcwindow">
    <?php
        if($wrtcvr->duedate > time()) echo '<button id="btnRecord" class="btn btn-secondary" type="button">Start Recording</button><br/>';

        if($wrtcvr->withvideo) echo '<video id="video" width="640" height="360" style="display:none" controls>Votre navigateur ne supporte pas la video</video>';
        else echo '<audio id="video" style="display:none" controls>Votre navigateur ne supporte pas la video</audio>';
    ?>
    <br/>
</div>

<script src="https://cdn.WebRTC-Experiment.com/RecordRTC.js"></script>
<script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script>

<script>
    var webrtcwindow = document.getElementById("webrtcwindow");
    video = document.getElementById("video");

    var btnRecord = document.getElementById("btnRecord");

    var recordRTC;

    function successCallback(stream) {
        btnRecord.innerHTML = "Stop Recording";
        video.src = window.URL.createObjectURL(stream);
        video.play();
        <?php
            if($wrtcvr->withvideo) echo 'var options = {
                mimeType: "video/webm",
                audioBitsPerSecond: 128000,
                videoBitsPerSecond: 128000,
                video: {
                    width: 640,
                    height: 360
                },
            };';
            else echo 'var options = {
                type: "audio",
                audioBitsPerSecond: 128000
            };';
        ?>
        recordRTC = RecordRTC(stream, options);
        recordRTC.startRecording();
    }

    function errorCallback(error) {
        window.alert("Le navigateur n'a pas pu détecter le bon périphérique d'enregistrement");
    }

    btnRecord.onclick = function() {
        video.style.display = "block";
        if(btnRecord.innerHTML==='Start Recording') {
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
        } else {
            recordRTC.stopRecording(function(videoURL) {
                video.src = videoURL;
                var blob = recordRTC.getBlob();

                var fileType = 'video';

                var formData = new FormData();
                formData.append(fileType + '-filename', fileName);
                formData.append(fileType + '-blob', blob);

                function xhr(url, data, callback) {
                    var request = new XMLHttpRequest();

                    request.addEventListener("progress", function(oEvent) {
                        if (oEvent.lengthComputable) {
                            var percentComplete = oEvent.loaded / oEvent.total * 100;
                            btnRecord.innerHTML = 'Uploading to Server... Progress : ' + percentComplete.toString();
                        }
                    }, false);

                    request.onreadystatechange = function () {
                        if (request.readyState == 4 && request.status == 200) {
                            callback(request.responseText);
                        }
                    };
                    request.open('POST', url);
                    request.send(data);
                }

                btnRecord.innerHTML = 'Uploading to Server';
                btnRecord.disabled = true;

                xhr('save.php', formData, function (fName) {
                    btnRecord.innerHTML = 'Start Recording';
                    btnRecord.disabled = false;
                });
            });
        }
    }
</script>

<?php
if($previousvideo) echo '<script>video.src = "'.$previousvideofileurl.'"; video.style.display="block"</script>';
if($wrtcvr->duedate > time()) $form->display();

// Finish the page.
echo $OUTPUT->footer();
?>
