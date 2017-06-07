<?php
/*
 * PHP CODE COMPILED FROM NIML
 * CODE START
 */
echo '<view title="';
echo $PAGETITLE;
echo '" lang="';
echo $LANG;
echo '"><top-vision bgcolor="magenta"><vision><ico class="stk-icon"></ico><tools class="stk-ctrl"><list class="stk-ctrl-group odd"><item x-usefor="item-fix" class="stk-ctrl-item item-fix" title="Fixed To Tablet"></item></list><list class="stk-ctrl-group"><item x-usefor="label-new" class="stk-ctrl-item label-new ';
echo $TOP["home_list"];
echo '" title="Create A New Label"></item><item x-usefor="label-save" class="stk-ctrl-item label-save ';
echo $TOP["new_mod"];
echo '" title="Save Label"></item><item x-usefor="label-del" class="stk-ctrl-item label-del ';
echo $TOP["new_mod"];
echo '" title="Delete Label"></item></list><list class="stk-ctrl-group odd"><item x-usefor="label-list" class="stk-ctrl-item preset-list ';
echo $TOP["new_mod"];
echo '" title="Return To Item List"></item></list></tools></vision></top-vision><vision bgcolor="silvery"><left bgcolor="light"><scroll-vision scroll-y="true">';
echo $SIDE;
echo '</scroll-vision></left><main posi="right">';
echo $MAIN;
echo '</main></vision></view>';
/*
 * CODE END
 */
