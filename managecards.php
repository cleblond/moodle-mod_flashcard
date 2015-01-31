<?php


	if (!defined('MOODLE_INTERNAL')) die ('You cannot use this script this way');
	
	// echo "[MVC : $action] ";
	if ($action){
		include 'manageview.controller.php';
	}

	$pagesize = 20;
    $allcards = $DB->count_records('flashcard_deckdata', array('flashcardid' => $flashcard->id));
    
    $page = optional_param('page', 0, PARAM_INT);
    $from = $page * $pagesize;

	echo $out;
	
    $cards = $DB->get_records('flashcard_deckdata', array('flashcardid' => $flashcard->id), 'id', '*', $from, $pagesize);
    
    $backstr = get_string('backside', 'flashcard');
    $frontstr = get_string('frontside', 'flashcard');
    
    $table = new html_table();
    $table->head = array('', "<b>$backstr</b>", "<b>$frontstr</b>", '');
    $table->size = array('10%', '40%', '40%', '10%');
    $table->width = '100%';    
    $table->align = array('center', 'center', 'center', 'center');

	$editurl = $CFG->wwwroot.'/mod/flashcard/view.php?id='.$id.'&view=editcard';

	$i = 0;
	if ($cards){
		foreach ($cards as $card){
                        //echo $card->id;
			$check = "<input type=\"checkbox\" name=\"items[]\" value=\"{$card->id}\" />";
	       //CRL for now only show text - maybe thumbnails or somehting
	       /* if ($flashcard->questionsmediatype == FLASHCARD_MEDIA_IMAGE) {
	            // $back = flashcard_print_image($flashcard, $card->questiontext, true);
	            $back = flashcard_print_image($flashcard, "questionimagefile/{$card->id}", true);
	        } elseif ($flashcard->questionsmediatype == FLASHCARD_MEDIA_SOUND){
	            // $back = flashcard_play_sound($flashcard, $card->questiontext, 'false', true, "bell_b$i");
	            $back = flashcard_play_sound($flashcard, "questionsoundfile/{$card->id}", 'false', true, "bell_b$i");
	        } elseif ($flashcard->questionsmediatype == FLASHCARD_MEDIA_VIDEO){
	            // $back = flashcard_play_sound($flashcard, $card->questiontext, 'false', true, "bell_f$i");
	            $back = flashcard_play_video($flashcard, "questionvideofile/{$card->id}", 'false', true, "bell_b$i");
	        } elseif ($flashcard->questionsmediatype == FLASHCARD_MEDIA_IMAGE_AND_SOUND){                            
	            // list($image, $sound) = split('@', $card->questiontext);
	            // $back = flashcard_print_image($flashcard, $image, true);
	            $back = flashcard_print_image($flashcard, "questionimagefile/{$card->id}", true);
	            $back .= "<br/>";
	            // $back .= flashcard_play_sound($flashcard, $sound, 'false', true, "bell_f$i");
	            $back = flashcard_play_sound($flashcard, "questionsoundfile/{$card->id}", 'false', true, "bell_b$i");
	        } else {  */
	            //$back = format_text($card->questiontext, FORMAT_MOODLE);
                    //echo $flashcard->id;
                      
                    $back = file_rewrite_pluginfile_urls($card->questiontext, 'pluginfile.php',
                    $context->id, 'mod_flashcard', 'question', $card->id);
                    //$back = format_text($backtext, FORMAT_MOODLE);
                    
	        //}
	//Before calling format_text(), the content must be processed with file_rewrite_pluginfile_urls()
	        /*if ($flashcard->answersmediatype == FLASHCARD_MEDIA_IMAGE) {
	            // $front = flashcard_print_image($flashcard, $card->answertext, true);
	            $front = flashcard_print_image($flashcard, "answerimagefile/{$card->id}", true);
	        } elseif ($flashcard->answersmediatype == FLASHCARD_MEDIA_SOUND){
	            // $front = flashcard_play_sound($flashcard, $card->answertext, 'false', true, "bell_f$i");
	            $front = flashcard_play_sound($flashcard, "answersoundfile/{$card->id}", 'false', true, "bell_f$i");
	        } elseif ($flashcard->answersmediatype == FLASHCARD_MEDIA_VIDEO){
	            // $front = flashcard_play_sound($flashcard, $card->answertext, 'false', true, "bell_f$i");
	            $front = flashcard_play_video($flashcard, "answervideofile/{$card->id}", 'false', true, "bell_f$i");
	        } elseif ($flashcard->answersmediatype == FLASHCARD_MEDIA_IMAGE_AND_SOUND){                            
	            // list($image, $sound) = split('@', $card->answertext);
	            // $front = flashcard_print_image($flashcard, $image, true);
	            $front = flashcard_print_image($flashcard, "answerimagefile/{$card->id}", true);
	            $front .= "<br/>";
	            // $front .= flashcard_play_sound($flashcard, $sound, 'false', true, "bell_f$i");
	            $front = flashcard_play_sound($flashcard, "answersoundfile/{$card->id}", 'false', true, "bell_f$i");
	        } else {  */
                    $front = file_rewrite_pluginfile_urls($card->questiontext, 'pluginfile.php',
                    $context->id, 'mod_flashcard', 'question', $card->id);
	            //$front = format_text($fronttext, FORMAT_MOODLE);
	        //}
	
			$command = "<a href=\"{$editurl}&what=update&cardid={$card->id}\"><img src=\"".$OUTPUT->pix_url('t/edit').'" /></a>';
			$command .= " <a href=\"{$url}?id={$id}&view=manage&what=delete&items[]={$card->id}\"><img src=\"".$OUTPUT->pix_url('t/delete').'" /></a>';
			$table->data[] = array($check, $back, $front, $command);
			$i++;
		}

		echo '<center>';
		echo $OUTPUT->paging_bar($allcards, $page, $pagesize, $url.'?id='.$id.'&view=manage', 'page');
		echo '</center>';
		echo '<form name="deletecards" action="'.$url.'" method="get">';	
		echo '<input type="hidden" name="sesskey" value="'.sesskey().'" />';	
		echo '<input type="hidden" name="view" value="manage" />';	
		echo '<input type="hidden" name="what" value="delete" />';	
		echo '<input type="hidden" name="id" value="'.$id.'" />';	
		echo html_writer::table($table);
		echo '</form>';
		echo '<center>';
		echo $OUTPUT->paging_bar($allcards, $page, $pagesize, $url.'?id='.$id.'&view=manage', 'page');
		echo '</center>';
	} else {
		echo $OUTPUT->box(get_string('nocards', 'flashcard'));
		echo '<br/>';
	}

	$addone = get_string('addone', 'flashcard');
	$addthree = get_string('addthree', 'flashcard');
	$deleteselectionstr = get_string('deleteselection', 'flashcard');
	$sesskey = sesskey();
	echo '<div class=\"rightlinks\">';
	if ($cards){
		echo "<a href=\"javascript:document.forms['deletecards'].submit();\">$deleteselectionstr</a> - ";
	}
	echo "<a href=\"{$editurl}&what=addone&sesskey={$sesskey}\">$addone</a> - <a href=\"{$editurl}&what=addthree&sesskey={$sesskey}\">$addthree</a></div>";
	
