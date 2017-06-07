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
echo '" x-theme="" x-template=""><form><vision class="content-info-section content-base"><inputs type="title"><input class="Title" name="TITLE" type="text" value="';
echo $VALUES["TITLE"];
echo '" placeholder="在这里输入标题 "></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["BaseInfo"];
echo '</label><el type="afterlabel">';
echo $LOCAL->edit["Group"];
echo '</el><input type="text" name="GROUPCODE" long value="';
echo $VALUES["GROUPCODE"];
echo '" placeholder=""><el type="beforeinput">';
echo $LOCAL->edit["Alias"];
echo '</el><input type="text" name="ALIAS" long value="';
echo $VALUES["ALIAS"];
echo '" placeholder=""></inputs><inputs type="text"><label>';
echo $LOCAL->edit["ViewCount"];
echo '</label><input type="text" name="KEY_COUNT" class="page-template " value="';
echo $VALUES["KEY_COUNT"];
echo '" placeholder=""></inputs><inputs type="text" inpreview="outpreview"><label>';
echo $LOCAL->edit["Banner"];
echo '</label><input type="text" name="BANNER" value="';
echo $VALUES["BANNER"];
echo '"><image-vision name="BANNER"></image-vision><click type="pick" name="BANNER" filetype="image ">';
echo $LOCAL->edit["Upload"];
echo '</vision></inputs><inputs type="textarea"><label>';
echo $LOCAL->edit["Description"];
echo '</label><textarea name="DESCRIPTION">';
echo $VALUES["DESCRIPTION"];
echo '</textarea></inputs><inputs type="editor"><label>';
echo $LOCAL->edit["Content"];
echo '</label><textarea name="CONTENT" placeholder="">';
echo $VALUES["CONTENT"];
echo '</textarea></inputs><inputs type="textarea"><label>';
echo $LOCAL->edit["STAND_I"];
echo '</label><textarea name="CUSTOM_I" placeholder="留空则自动截取文字内容部分">';
echo $VALUES["CUSTOM_I"];
echo '</textarea></inputs><inputs type="textarea"><label>';
echo $LOCAL->edit["STAND_II"];
echo '</label><textarea name="CUSTOM_II" placeholder="留空则自动截取文字内容部分">';
echo $VALUES["CUSTOM_II"];
echo '</textarea></inputs><inputs type="tags" class="seo"><input name="KEYWORDS" type="text" placeholder="输入SEO关键词，多个关键词请用 \',\'隔开。 " value="';
echo $VALUES["KEYWORDS"];
echo '"></vision></vision></form></form-vision><edit-panel bgcolor="datered"><click href="trigger://';
echo AI_CURR;
echo '::ToList" args="';
echo $ARGS;
echo '" class="left-panel-button">';
echo $LOCAL->panel["return"];
echo '</click><click href="trigger://';
echo AI_CURR;
echo '::ToTop " class="right-totop-button"><vision class="arrow"></vision><vision class="stick"></vision></click><click href="trigger://';
echo AI_CURR;
echo '::PubItem" args="';
echo $ARGS;
echo '" class="right-panel-button even">';
echo $LOCAL->panel["pub"];
echo '</click></edit-panel>';
/*
 * CODE END
 */
