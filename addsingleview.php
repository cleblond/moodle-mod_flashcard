<?php

/* @var $DB mysqli_native_moodle_database */
/* @var $OUTPUT core_renderer */
/* @var $PAGE moodle_page */
?>
<?php

/**
 * Screen for adding new FLASHCARD_CARDS_PER_PAGE cards
 * 
 * @package mod-flashcard
 * @category mod
 * @author Tomasz Muras
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
/* @var $OUTPUT core_renderer */

if (!defined('MOODLE_INTERNAL')) {
    error("Illegal direct access to this screen");
}

require_once('cardedit_form.php');

$action = "save";
$cardid = null;

if ($action != '') {
    $result = include "{$CFG->dirroot}/mod/flashcard/editview.controller.php";
}

$cardsnum = $DB->count_records('flashcard_deckdata', array('flashcardid' => $flashcard->id));
$form = new flashcard_cardedit_form(null, array('noaddbutton' => true, 'context' => $context, 'cardid' => $cardid));
$form->set_data(array('view' => 'add', 'id' => $cm->id));
echo "IN ADDSINGLEVUEW";
if ($fromform = $form->get_data()) {
    $fileoptions = array(
        'subdirs' => false,
        'maxfiles' => -1,
        'maxbytes' => 0,
    );

    foreach ($fromform->cardid as $k => $id) {
        if ($fromform->question[$k]['text'] || $fromform->answer[$k]['text']) {
            //insert new
            $newcard = new object();
            $newcard->answertext = '';
            $newcard->questiontext = '';
            $newcard->flashcardid = $flashcard->id;
            $newcard->id = $DB->insert_record('flashcard_deckdata', $newcard);

            //$newcard->questiontext = $fromform->question[$k]['text'];
            //$data = file_postupdate_standard_editor($fromform->question[$k]['text'], 'summary', $editoroptions, $context, 'course', 'section', $section->id);
            //$section->summary = $data->summary;
            $savedquestion = file_save_draft_area_files($fromform->question[$k]['itemid'], $context->id, 'mod_flashcard',
                    'question', $newcard->id, $fileoptions, $fromform->question[$k]['text']);
            $newcard->questiontext = $savedquestion;
            
            $savedanswer = file_save_draft_area_files($fromform->answer[$k]['itemid'], $context->id, 'mod_flashcard',
                    'answer', $newcard->id, $fileoptions, $fromform->answer[$k]['text']);
            $newcard->answertext = $savedanswer;
            print_object($newcard);
            $DB->update_record('flashcard_deckdata', $newcard);
        }
    }
    //redirect to the last page of edit
    $url = new moodle_url('view.php', array('a' => $flashcard->id, 'view' => 'edit', 'subview' => 'add', 'page' => -1));
    redirect($url);
}

echo $out;
echo $OUTPUT->heading("Add new cards");
$form->display();