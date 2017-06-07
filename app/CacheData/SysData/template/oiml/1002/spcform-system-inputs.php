<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<vision class="content-info-section content-prop" style="display:none;"><inputs type="title"><input class="Title_Follow" type="text" value="';
echo $VALUES["TITLE"];
echo '" placeholder="标题" readonly></inputs><inputs type="hidden"><input name="USR_ID" type="text" value="';
echo $UID;
echo '" placeholder=""></inputs><inputs type="hidden"><input name="KEY_MTIME" type="text" value="';
echo DATETIME;
echo '" placeholder=""></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["Rankings"];
echo '</label><el type="afterlabel">';
echo $LOCAL->edit["Stars"];
echo '</el><select name="RANK"><option value="7" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 7 ){
	echo '\'selected\'';
}
echo '>★★★★★★★</option><option value="6" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 6 ){
	echo '\'selected\'';
}
echo '>★★★★★★</option><option value="5" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 5 ){
	echo '\'selected\'';
}
echo '>★★★★★</option><option value="4" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 4 ){
	echo '\'selected\'';
}
echo '>★★★★</option><option value="3" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 3 ){
	echo '\'selected\'';
}
echo '>★★★</option><option value="2" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 2 ){
	echo '\'selected\'';
}
echo '>★★</option><option value="1" ';
if( isset($VALUES["RANK"]) && $VALUES["RANK"] == 1 ){
	echo '\'selected\'';
}
echo '>★</option></select><el type="beforeinput">';
echo $LOCAL->edit["Level"];
echo '</el><input name="LEVEL" type="text" value="';
echo $VALUES["LEVEL"];
echo '"></inputs><inputs type="text"><label>';
echo $LOCAL->edit["ViewCount"];
echo '</label><input name="KEY_COUNT" type="text" value="';
echo $VALUES["KEY_COUNT"];
echo '"></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["Category"];
echo '</label><select name="CAT_ID">';
echo $CATS;
echo '</select><font>(选择栏目后才有可能被该栏目的默认函数调用)</font></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["IsTop"];
echo '</label><input type="radio" name="IS_TOP" value="1" ';
if( isset($VALUES["IS_TOP"]) && $VALUES["IS_TOP"] == 1 ){
	echo 'checked';
}
echo '><el type="afterinput">是</el><input type="radio" name="IS_TOP" value="0" ';
if( isset($VALUES["IS_TOP"]) && $VALUES["IS_TOP"] == 0 ){
	echo 'checked';
}
echo '><el type="afterinput">否</el></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["State"];
echo '</label><input type="radio" name="KEY_STATE" value="1" ';
if( isset($VALUES["KEY_STATE"]) && $VALUES["KEY_STATE"] == 1 ){
	echo 'checked';
}
echo '><el type="afterinput">已审核</el><input type="radio" name="KEY_STATE" value="0" ';
if( isset($VALUES["KEY_STATE"]) && $VALUES["KEY_STATE"] == 0 ){
	echo 'checked';
}
echo '><el type="afterinput">待审核</el></inputs><inputs type="textarea"><label>';
echo $LOCAL->edit["Description"];
echo '</label><textarea name="DESCRIPTION" placeholder="留空则自动截取文字内容部分">';
echo $VALUES["DESCRIPTION"];
echo '</textarea></inputs><inputs type="text"><label>';
echo $LOCAL->edit["Keywords"];
echo '</label><input type="text" name="KEYWORDS" value="';
echo $VALUES["KEYWORDS"];
echo '"></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["Limited"];
echo '</label><select name="KEY_LIMIT"><option value="0" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 0 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["Default"];
echo '</option><option value="1" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 1 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["AnyPersonSee"];
echo '</option><option value="2" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 2 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["FriendsSee"];
echo '</option><option value="3" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 2 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["FollowingsSee"];
echo '</option><option value="4" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 2 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["FollowersSee"];
echo '</option><option value="5" ';
if( isset($VALUES["KEY_LIMIT"]) && $VALUES["KEY_LIMIT"] == 2 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["AuthorSee"];
echo '</option></select><font>(选择可查阅该内容的用户组，默认为使用栏目设置)</font></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["Pay"];
echo '</label><input type="text" name="CHARGE_VALUE" value="';
echo $VALUES["CHARGE_VALUE"];
echo '"><select name="CHARGE_TYPE"><option value="1" ';
if( isset($VALUES["CHARGE_TYPE"]) && $VALUES["CHARGE_TYPE"] == 1 ){
	echo '\'selected\'';
}
echo '>× 0 (';
echo $LOCAL->edit["Free"];
echo ')</option><option value="2" ';
if( isset($VALUES["CHARGE_TYPE"]) && $VALUES["CHARGE_TYPE"] == 2 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["Scores"];
echo '</option><option value="3" ';
if( isset($VALUES["CHARGE_TYPE"]) && $VALUES["CHARGE_TYPE"] == 3 ){
	echo '\'selected\'';
}
echo '>';
echo $LOCAL->edit["VCoin"];
echo '</option></select><font>查阅该内容可能需要支付积分、虚拟币或货币</font></inputs><inputs type="multiple"><label>';
echo $LOCAL->edit["ReCharge"];
echo '</label><input type="text" name="RECHARGE_HOURS" value="';
echo $VALUES["RECHARGE_HOURS"];
echo '"><el type="afterinput">小时后再次收费</el><input type="text" name="RECHARGE_TIMES" value="';
echo $VALUES["RECHARGE_TIMES"];
echo '"><el type="afterinput">次后再次收费</el><font>（仅对非免费内容有效，0为不限时间或次数，两项皆不为0时，满足其一即需重复付费)</font></inputs></vision><vision class="content-info-section content-rele" style="display:none;"><inputs type="title"><input class="Title_Follow" type="text" value="';
echo $VALUES["TITLE"];
echo '" placeholder="标题" readonly></inputs><inputs type="longtext"><label>请输入相关内容的预设代号与内容ID： </label><font>格式为{&quot;预设代号&quot;：ID}，多个内容用", "隔开，可以使用下面的选择工具辅助操作</font><textarea name="RELATES" class="desc" placeholder="{&quot;预设代号&quot;：ID}">';
echo $VALUES["RELATES"];
echo '</textarea></inputs></vision>';
/*
 * CODE END
 */
