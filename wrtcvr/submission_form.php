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
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_wrtcvr_submission_form extends moodleform {

    /**
     * Define this form - called by the parent constructor
     */
    public function definition() {
        global $USER;
        $mform = $this->_form;
        list($wrtcvr, $data) = $this->_customdata;
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
        }
    }
}
