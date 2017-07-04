<?php
/**
 * This file contains the submission form used by the wrtcvr module.
 *
 * @package   mod_wrtcvr
 * @copyright 2017 UPMC
 */

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');


require_once($CFG->libdir . '/formslib.php');

/**
 * wrtcvr submission form
 *
 * @package   mod_wrtcvr
 * @copyright 2017 UPMC
 */
class mod_wrtcvr_submission_form extends moodleform {

    /**
     * Define this form - called by the parent constructor
     */
    public function definition() {
        $mform = $this->_form;

        //$mform->addElement('header', 'general', get_string('submitvideo', 'mod_wrtcvr'));

        $mform->addElement('hidden', 'file_url', get_string('url'));
        $mform->setType('file_url', PARAM_RAW);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        $errors = array();
        global $CFG;
        clearstatcache();
        if(!file_exists($CFG->tempdir.'/'.$data['file_url'])) {
            $errors['file_url'] = get_string('errornofilesubmitted', 'mod_wrtcvr');
        }
        return $errors;
    }
}
