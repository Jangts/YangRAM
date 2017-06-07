<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<form-vision type="origin" x-cid="';
echo $CID;
echo '" x-sort="';
echo $SORT;
echo '" x-page="';
echo $PAGE;
echo '" x-status="';
echo $STTS;
echo '" x-cls="';
echo $CLS;
echo '" x-theme="';
echo $PREVIEW_THEME;
echo '" x-template="';
echo $PREVIEW_TEMPLATE;
echo '"><form>';
$this->including('spcform-custom-inputs');
$this->including('spcform-system-inputs');
echo '</form></form-vision><edit-panel bgcolor="datered"><click href="trigger://';
echo AI_CURR;
echo '::ToList" args="';
echo $ARGS;
echo '" class="left-panel-button">';
echo $LOCAL->panel["return"];
echo '</click><click href="trigger://';
echo AI_CURR;
echo '::ToTop" class="right-totop-button"><vision class="arrow"></vision><vision class="stick"></vision></click><click href="trigger://';
echo AI_CURR;
echo '::PubItem" args="';
echo $ARGS;
echo '" class="right-panel-button even">';
echo $LOCAL->panel["pub"];
echo '</click><click href="trigger://';
echo AI_CURR;
echo '::SaveItem" args="';
echo $ARGS;
echo '" class="right-panel-button">';
echo $LOCAL->panel["save"];
echo '</click></edit-panel>';
/*
 * CODE END
 */
