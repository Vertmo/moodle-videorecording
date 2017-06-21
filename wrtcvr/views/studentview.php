<?php
/**
 * Prints a the view of a student
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

require_once(dirname(dirname(__FILE__)).'/submission.php');

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($wrtcvr->intro) {
    echo $OUTPUT->box(format_module_intro('wrtcvr', $wrtcvr, $cm->id), 'generalbox mod_introbox', 'wrtcvrintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading(get_string('modulename', 'wrtcvr'));

echo '<script>fileName="'.$_SESSION['file_url'].'"</script>';
echo file_get_contents(dirname(__FILE__).'/index.html');
$form->display();

// Finish the page.
echo $OUTPUT->footer();
 ?>
