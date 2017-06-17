<?php
/**
 * This file contains the submission form used by the wrtcvr module.
 *
 * @package   mod_wrtcvr
 * @copyright 2017 UPMC
 */

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');


require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/mod/wrtcvr/locallib.php');

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
        //global $USER;
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('submitvideo', 'mod_wrtcvr'));

        $mform->addElement('hidden', 'file_url', get_string('url'));
        $mform->setType('file_url', PARAM_RAW);

        //$mform->addElement('filepicker', 'video', get_string('file'), null, array('maxbyte'=>8000, 'accepted_types'=>'*'));
        /*list($wrtcvr, $data) = $this->_customdata;
        $instance = $wrtcvr->get_instance();
        if ($instance->teamsubmission) {
            $submission = $wrtcvr->get_group_submission($data->userid, 0, true);
        } else {
            $submission = $wrtcvr->get_user_submission($data->userid, true);
        }
        if ($submission) {
            $mform->addElement('hidden', 'lastmodified', $submission->timemodified);
            $mform->setType('lastmodified', PARAM_INT);
        }

        $wrtcvr->add_submission_form_elements($mform, $data);
        $this->add_action_buttons(true, get_string('savechanges', 'wrtcvr'));
        if ($data) {
            $this->set_data($data);
        }*/

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        $errors = array();
        global $CFG;
        clearstatcache();
        if(!file_exists($CFG->dirroot.'/mod/wrtcvr/uploads/'.$data['file_url'])) {
            $errors['file_url'] = get_string('errornofilesubmitted', 'mod_wrtcvr');
        }
        return $errors;
    }
}
