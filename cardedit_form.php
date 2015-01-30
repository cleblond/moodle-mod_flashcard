<?php

require_once($CFG->libdir . '/formslib.php');
//echo "<br>cardedit_form";
class flashcard_cardedit_form extends moodleform {

    //protected $numelements = 10;
    protected $numelements = 1;
    protected function definition() {
        global $COURSE, $CFG, $PAGE;

        $mform = $this->_form;
        if (isset($this->_customdata['noaddbutton']) && $this->_customdata['noaddbutton']) {
            $noaddbutton = true;
        } else {
            $noaddbutton = false;
        }
        //echo "cardid=$cardid";
        $context = $this->_customdata['context'];
        $cardid = $this->_customdata['cardid'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'view');
        $mform->setType('view', PARAM_TEXT);
        //print_object($question);
        //print_object($this);
        //echo "cardid=$cardid";
            //$i=7;
            $cardid=$cardid-1;
            $mform->addElement('editor', "question", get_string('question', 'flashcard'), null,
                    array('context' => $context, 'maxfiles' => EDITOR_UNLIMITED_FILES,'noclean'=>true));
            $mform->addElement('editor', "answer", get_string('answer', 'flashcard'), null,
                    array('context' => $context, 'maxfiles' => EDITOR_UNLIMITED_FILES,'noclean'=>true));
            $mform->addElement('hidden', "cardid");
            $mform->setType("cardid", PARAM_INT);



/*

        for ($i = 0; $i < $this->numelements; $i++) {
            $mform->addElement('editor', "question[$i]", get_string('question', 'flashcard'), null,
                    array('context' => $context, 'maxfiles' => EDITOR_UNLIMITED_FILES,'noclean'=>true));
            $mform->addElement('editor', "answer[$i]", get_string('answer', 'flashcard'), null,
                    array('context' => $context, 'maxfiles' => EDITOR_UNLIMITED_FILES,'noclean'=>true));
            $mform->addElement('hidden', "cardid[$i]");
            $mform->setType("cardid[$i]", PARAM_INT);
        }
*/

        //-------------------------------------------------------------------------------
//        $mform->addElement('header', 'general', get_string('general', 'form'));
//        echo 'ok';
        /*
          $mform->addElement('editor', 'page', get_string('content', 'page'));//, null, array('context'=>$context,'changeformat'=>1,'trusttext'=>1));
          return;

         */
        /*
          foreach($cards as $card) {
          $mform->addElement('editor', "question[{$card->id}]", 'question');//, null, array('context'=>$context,'changeformat'=>1,'trusttext'=>1));

          }
         */
        if (!$noaddbutton) {
            $mform->addElement('submit', 'addmore', get_string('saveadd', 'flashcard'));
        }
        $this->add_action_buttons();
    }

}
