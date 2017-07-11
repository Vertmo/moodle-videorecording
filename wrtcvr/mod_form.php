<?php
/**
 * The main wrtcvr configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_wrtcvr
 * @copyright  2017 UPMC
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_wrtcvr
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_wrtcvr_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     * @todo completer le formulaire pour plus de reglages
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('wrtcvrname', 'wrtcvr'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'wrtcvrname', 'wrtcvr');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of wrtcvr settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
        $mform->addElement('date_time_selector', 'allowsubmissionsfromdate', get_string('allowsubmissionsfromdate', 'mod_wrtcvr'));
        $mform->addElement('date_time_selector', 'duedate', get_string('duedate', 'mod_wrtcvr'));
        $mform->setDefault('duedate', time()+7*24*3600);

        /*$radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'withvideo', '', get_string('audioandvideo', 'mod_wrtcvr'), 1);
        $radioarray[] = $mform->createElement('radio', 'withvideo', '', get_string('audioonly', 'mod_wrtcvr'), 0);
        $mform->setDefault('withvideo', 1);
        $mform->addGroup($radioarray, 'withvideoarray', get_string('withvideo', 'mod_wrtcvr'), array(' '), false);*/

        $options = array(
            1 => get_string('audioandvideo', 'mod_wrtcvr'),
            0 => get_string('audioonly', 'mod_wrtcvr')
        );
        $mform->addElement('select', 'withvideo', get_string('withvideo', 'mod_wrtcvr'), $options);
        $mform->setDefault('withvideo', 1);

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
