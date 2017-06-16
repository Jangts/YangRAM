<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<popup-form><form-vision>';
echo $FORM;
echo '</form-vision><vision class="ctrl-buttons"><vision class="item-ctrl-btn"><click href="trigger://';
echo AI_CURR;
echo '::Cancel" args="" class="content-delete-btn">';
echo $LOCAL->labels["abandon"];
echo '</click></vision><vision class="item-ctrl-btn"><click href="trigger://';
echo AI_CURR;
echo '::SaveItem" args="';
echo $PID;
echo '" class="content-delete-btn">';
echo $LOCAL->labels["save"];
echo '</click></vision></vision></popup-form>';
/*
 * CODE END
 */
