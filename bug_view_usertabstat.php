<?php
if( !defined( 'BUG_VIEW_INC_ALLOW' ) ) {
	return;
}
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'bug_api.php' );
require_api( 'category_api.php' );
require_api( 'columns_api.php' );
require_api( 'compress_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'current_user_api.php' );
require_api( 'filter_api.php' );
require_api( 'custom_field_api.php' );
require_api( 'date_api.php' );
require_api( 'event_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'prepare_api.php' );
require_api( 'print_api.php' );
require_api( 'project_api.php' );
require_api( 'string_api.php' );
require_api( 'tag_api.php' );
require_api( 'utility_api.php' );
require_api( 'version_api.php' );
require_css( 'status_config.php' );

function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}
function timestampf($time) {
    return date("N", strtotime($time));
}

$t_user_id = gpc_get_int( 'userid' );
$t_year = gpc_get_int( 'year' );
$t_mount = gpc_get_int( 'mount' );
compress_enable();
if( $t_show_page_header ) {
	layout_page_header( lang_get( 'task_schedule' ), null, 'view-usertabstat-page' );
	layout_page_begin( 'usertabstat.php' );
}

#
# Start of Template
#
if($t_user_id === 0 ){
    $t_user_id = auth_get_current_user_id();
}

$t_where_params = array();
$p_per_page = 50;
$t_query = 'SELECT `realname`,`id` FROM {user}';
$t_offset = 0;
$t_result = db_query( $t_query, $t_where_params, $p_per_page, $t_offset );

$t_users = array();
echo '<ul class="nav nav-tabs padding-18">';
while( $t_row = db_fetch_array( $t_result ) ) {
    echo '<li class="'.(($t_row['id']==$t_user_id)?'active':'').'"><a href="/usertabstat.php?userid='.$t_row['id'].'&year='.$t_year.'&mount='.$t_mount.'">'.$t_row['realname'].'</a></li>';
}
echo '</ul>';
$t_user_count = count( $t_users );


$f_page_number = 1;
$t_per_page = null;
$t_bug_count = null;
$t_page_count = null;

$strtime = '17-'.$t_mount.'-'.$t_year;
$timestamp = strtotime($strtime);

$date = new DateTime('1-'.$t_mount.'-'.$t_year);
$date->modify('last day of this month');
$c_filter= array(
    'handler_id' => array(
        '0' =>  $t_user_id,
    ),   'custom_fields' => array(
        '1' => array(
            0=>'5',
            1=>strtotime('1-'.$t_mount.'-'.$t_year),
            2=>strtotime($date->format('d').'-'.$t_mount.'-'.$t_year)
        ) ,
    )
);

$t_rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $c_filter, null, null, true );
$c_filter= array(
    'handler_id' => array(
        '0' =>  $t_user_id,
    ), 'status' => array(
        '0' =>  90,
    ),    'custom_fields' => array(
        '1' => array(
            0=>'5',
            1=>strtotime('1-'.$t_mount.'-'.$t_year),
            2=>strtotime($date->format('d').'-'.$t_mount.'-'.$t_year)
        ) ,
    )
);
$t_rows2 = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $c_filter, null, null, true );

$t_rows = array_merge($t_rows, $t_rows2);
$t_bugslist = array();
$t_row_count = count( $t_rows );
for( $i=0; $i < $t_row_count; $i++ ) {
    array_push( $t_bugslist, [
            'id'=>$t_rows[$i]->id,
        'status'=>$t_rows[$i]->status,
        'summary'=>$t_rows[$i]->summary,
        'description'=>$t_rows[$i]->description,
        'timestart'=>custom_field_get_value( 1, $t_rows[$i]->id ),
        'mnday'=>custom_field_get_value( 2, $t_rows[$i]->id )
    ] );
}

$t_bugslist_tmp = [];
$t_row_count = count( $t_bugslist );
for( $i=0; $i < $t_row_count; $i++ ) {
$d1 = floor(date('d',$t_bugslist[$i]["timestart"]));
    if(isWeekend($t_year.'-'.$t_mount.'-'.$d1)){
        $d1 = $d1 + 1;
        if(isWeekend($t_year.'-'.$t_mount.'-'.$d1)){
            $d1 = $d1 + 1;
        }
}
$mnd = $t_bugslist[$i]["mnday"];
if(floor($mnd)<1){
    $t_bugslist_tmp[$d1][]=$t_bugslist[$i];
}else{
    $d2 = $d1+ceil($mnd);
    for(  $i2=$d1; $i2 < $d2; $i2++ ) {
        $d3 =$i2;
        if(isWeekend($t_year.'-'.$t_mount.'-'.$d3)){
            $d3 = $d3 + 1;
            $d2 =  $d2 + 1;
            $i2 =  $i2 + 1;
            if(isWeekend($t_year.'-'.$t_mount.'-'.$d3)){
                $d3 = $d3 + 1;
                $d2 =  $d2 + 1;
                $i2 =  $i2 + 1;
            }
        }
        $t_bugslist_tmp[$d3][]=$t_bugslist[$i];
    }
}
}
$t_bugslist = $t_bugslist_tmp;




















$c_filter= array(
    'handler_id' => array(
        '0' =>  $t_user_id,
    ),   'custom_fields' => array(
        '1' => array(
            0=>'5',
            1=>strtotime('1-'.($t_mount-1).'-'.$t_year),
            2=>strtotime($date->format('d').'-'.($t_mount-1).'-'.$t_year)
        ) ,
    )
);

$t_rows = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $c_filter, null, null, true );
$c_filter= array(
    'handler_id' => array(
        '0' =>  $t_user_id,
    ), 'status' => array(
        '0' =>  90,
    ),    'custom_fields' => array(
        '1' => array(
            0=>'5',
            1=>strtotime('1-'.($t_mount-1).'-'.$t_year),
            2=>strtotime($date->format('d').'-'.($t_mount-1).'-'.$t_year)
        ) ,
    )
);
$t_rows2 = filter_get_bug_rows( $f_page_number, $t_per_page, $t_page_count, $t_bug_count, $c_filter, null, null, true );

$t_rows = array_merge($t_rows, $t_rows2);
$t_bugslist2 = array();
$t_row_count = count( $t_rows );
for( $i=0; $i < $t_row_count; $i++ ) {
    array_push( $t_bugslist2, [
        'id'=>$t_rows[$i]->id,
        'status'=>$t_rows[$i]->status,
        'summary'=>$t_rows[$i]->summary,
        'description'=>$t_rows[$i]->description,
        'timestart'=>custom_field_get_value( 1, $t_rows[$i]->id ),
        'mnday'=>custom_field_get_value( 2, $t_rows[$i]->id )
    ] );
}

$t_bugslist_tmp = [];
$t_row_count = count( $t_bugslist2 );
for( $i=0; $i < $t_row_count; $i++ ) {
    $d4 = 0;
    $d1 = floor(date('d',$t_bugslist2[$i]["timestart"]));
    if(isWeekend($t_year.'-'.($t_mount-1).'-'.$d1)){
        $d1 = $d1 + 1;
        if(isWeekend($t_year.'-'.($t_mount-1).'-'.$d1)){
            $d1 = $d1 + 1;
        }
    }
    $mnd = $t_bugslist2[$i]["mnday"];
    if(floor($mnd)<1){

    }else{
        $d2 = $d1+ceil($mnd);
        for(  $i2=$d1; $i2 < $d2; $i2++ ) {
            $d3 =$i2;
            if(isWeekend($t_year.'-'.($t_mount-1).'-'.$d3)){
                $d3 = $d3 + 1;
                $d2 =  $d2 + 1;
                $i2 =  $i2 + 1;
                if(isWeekend($t_year.'-'.($t_mount-1).'-'.$d3)){
                    $d3 = $d3 + 1;
                    $d2 =  $d2 + 1;
                    $i2 =  $i2 + 1;
                }
            }
            if($d3>$date->format('d')){
                if($d4===0){
                    $d4 = $d3;
                }else{
                    $d4 = $d4 + 1;
                }

                if(isWeekend($t_year.'-'.($t_mount).'-'.($d4-$date->format('d')))){
                    $d4 = $d4 + 1;
                    if(isWeekend($t_year.'-'.($t_mount).'-'.($d4-$date->format('d')))){
                        $d4 = $d4 + 1;
                    }
                }
                //var_dump($t_bugslist2[$i]);
                $t_bugslist[($d4-$date->format('d'))][]=$t_bugslist2[$i];
            }
        }
    }
}





echo '<script>
	function setpagetotime(htx){
 let url = \'/usertabstat.php?userid='.$t_user_id.'&year=\'+$("select[name*=\'custom_field_1_year\']").val()+\'&mount=\'+$("select[name*=\'custom_field_1_month\']").val();
window.location.href = url;
window.location.assign(url);
location.assign(location.origin + url)
window.location = url;
location.href=url ;
document.location.assign(document.location.origin + url)
setTimeout(function(){document.location.href = url;},250);
	}
	
const getDaysInMonth = (month, year) => (new Array(31)).fill(\'\').map((v,i)=>new Date(year,month-1,i+1)).filter(v=>v.getMonth()===month-1)
let datevar = getDaysInMonth(1,2021);
//console.log(datevar);
</script>';


echo '<div class="col-md-12 col-xs-12">';
echo '<div class="space-10"></div>';
echo '<div class="widget-box widget-color-blue2">';
echo '<div class="widget-header widget-header-small">
<div class="widget-toolbar">
<select class="input-sm" tabindex="1" name="custom_field_1_year" onchange="setpagetotime(this);">
<option value="0"></option>
<option value="2021" '.(($t_year==2021)?'selected="selected"':'').'   >2021</option>
<option value="2022" '.(($t_year==2022)?'selected="selected"':'').'>2022</option>
<option value="2023" '.(($t_year==2023)?'selected="selected"':'').'>2023</option>
<option value="2024" '.(($t_year==2024)?'selected="selected"':'').'>2024</option>
<option value="2025" '.(($t_year==2025)?'selected="selected"':'').'>2025</option>
</select>
<select class="input-sm" tabindex="2" name="custom_field_1_month" onchange="setpagetotime(this);">
<option value="0"></option>
<option value="1" '.(($t_mount==1)?'selected="selected"':'').'>январь</option>
<option value="2" '.(($t_mount==2)?'selected="selected"':'').'>февраль</option>
<option value="3" '.(($t_mount==3)?'selected="selected"':'').'>март</option>
<option value="4" '.(($t_mount==4)?'selected="selected"':'').'>апрель</option>
<option value="5" '.(($t_mount==5)?'selected="selected"':'').'>май</option>
<option value="6" '.(($t_mount==6)?'selected="selected"':'').'>июнь</option>
<option value="7" '.(($t_mount==7)?'selected="selected"':'').'>июль</option>
<option value="8" '.(($t_mount==8)?'selected="selected"':'').'>август</option>
<option value="9" '.(($t_mount==9)?'selected="selected"':'').'>сентябрь</option>
<option value="10" '.(($t_mount==10)?'selected="selected"':'').'>октябрь</option>
<option value="11" '.(($t_mount==11)?'selected="selected"':'').'>ноябрь</option>
<option value="12" '.(($t_mount==12)?'selected="selected"':'').'>декабрь</option>
</select>
';

echo '
		<div class="widget-menu">
			<a href="#" data-action="settings" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-bars ace-icon bigger-125"></i>						</a>
						<ul class="dropdown-menu dropdown-menu-right dropdown-yellow dropdown-caret dropdown-closer">
							<li><a href="view_all_set.php?type=2&amp;view_type=advanced"><i class="fa fa-toggle-on ace-icon"></i>&nbsp;&nbsp;Расширенные фильтры</a></li>
							<li><a href="permalink_page.php?url=https%3A%2F%2Fsupport.qwertynetworks.com%2Fsearch.php%3Fproject_id%3D1%26sticky%3Don%26sort%3Dlast_updated%26dir%3DDESC%26hide_status%3D90%26match_type%3D0&amp;permalink_token=20210717F3udxzgTmrWmhRHOWXqtCld-Y2bRmR_Y"><i class="fa fa-link ace-icon"></i>&nbsp;&nbsp;Создать постоянную ссылку</a></li>						</ul>
					</div>
								<a id="filter-toggle" data-action="collapse" href="#">
					<i class="fa 1 ace-icon bigger-125 fa-chevron-up"></i>				</a>
			</div>
			';
echo '<h4 class="widget-title lighter">';
print_icon( 'fa-bar-chart-o', 'ace-icon' );
echo string_display_line(lang_get( 'task_schedule' )) .' на '. (array(
    '1'=>'январь',
    '2'=>'февраль',
    '3'=>'март',
    '4'=>'апрель',
    '5'=>'май',
    '6'=>'июнь',
    '7'=>'июль',
    '8'=>'август',
    '9'=>'сентябрь',
    '10'=>'октябрь',
    '11'=>'ноябрь',
    '12'=>'декабрь'
        )[$t_mount]);
echo '</h4>';
echo '</div>';
echo '<div class="widget-body">';
echo '<div class="widget-toolbox padding-8 clearfix noprint">';


//<select class="input-sm" tabindex="7" id="status" name="status">
//<option value="10">новая</option>
//<option value="20">обратная связь</option>
//<option value="30">рассматривается</option>
//<option value="40">подтверждена</option>
//<option value="50" selected="selected">назначена</option>
//<option value="80">решена</option>
//<option value="90">закрыта</option></select>

function sethtmlissue($i,$t_bugslist){
    $htmlend = '';
    $statuslist = [
    '10'=>'новая',
    '20'=>'обратная связь',
    '30'=>'рассматривается',
    '40'=>'подтверждена',
    '50'=>'назначена',
    '80'=>'решена',
    '90'=>'закрыта'
    ];
    $colorlist = [
    '10'=>'#fcbdbd',
    '20'=>'#e3b7eb',
    '30'=>'#ffcd85',
    '40'=>'#fff494',
    '50'=>'#c2dfff',
    '80'=>'#d2f5b0',
    '90'=>'#c9ccc4'
    ];



    foreach ($t_bugslist[$i] as $key => $value) {
        // $arr[3] будет перезаписываться значениями $arr при каждой итерации цикла
        echo ' <a href="/view.php?id='.$value['id'].'" title="'.$statuslist[$value['status']].': '.$value['description'].'">';
        echo '  <div class="widget-toolbox padding-8 clearfix noprint" style="color: #000;background-color: '.$colorlist[$value['status']].';border-bottom: 1px solid #CCC;    border-top: 1px solid #fff;    overflow: hidden;font-size: 12px;
    max-height: 49px;">';
        echo '<i style="    float: right;
    border: 1px solid #6FB3E0;
    border-radius: 9px;
    width: 26px;
    height: 20px;
    padding-left: 4px;
    color: #fff;
    background-color: #307ecc;">'.$value['mnday'].'</i>';
        echo '<b style="    color: #000;">#'.$value['id'].'</b>&nbsp;'.$value['summary'];

        echo ' </div>';
        echo '</a>';
    }
    return $htmlend;
}
//<select class="input-sm" tabindex="7" id="status" name="status">
//<option value="10">новая</option>
//<option value="20">обратная связь</option>
//<option value="30">рассматривается</option>
//<option value="40">подтверждена</option>
//<option value="50" selected="selected">назначена</option>
//<option value="80">решена</option>
//<option value="90">закрыта</option></select>

echo '<div class="container-fluid">';
echo '<div class="row">';
    $t_row_count =  (int)$date->format('d')+timestampf($t_year.'-'.$t_mount.'-'.'1') - 1 ;
$daylist = [
    '1'=>'Понедельник',
    '2'=>'Вторник',
    '3'=>'Среда',
    '4'=>'Четверг',
    '5'=>'Пятница',
    '6'=>'Суббота',
    '7'=>'Воскресенье'
];


for( $i=0; $i < $t_row_count; $i++ ) {

    if(timestampf($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))==1){
        echo '<div class="row">';
        echo '</div>';
    }


if((($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1))<=0){
    echo '  <div class="col-xs-2 widget-box  ml-2" style="margin-left: 10px !important;width: 250px;" >';
    echo '  <div class="widget-header widget-header-small" >';
    echo   '<h4 class="widget-title lighter"></h4>';
    echo ' </div>';
    echo '  <div class="widget-body" style="min-height: 90px;">';

    echo ' </div>';
    echo ' </div>';
}else{
    if(!isWeekend($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))){
        echo '  <div class="col-xs-2 widget-box '.(isWeekend($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))?'widget-color-red':'widget-color-blue').' ml-2" style="margin-left: 10px !important;width: 250px;" >';
        echo '  <div class="widget-header widget-header-small" >';
        echo   '<h4 class="widget-title lighter">'.$daylist[timestampf($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))].' <span style="float:right;">'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)).'&nbsp;</span></h4>';
        echo ' </div>';
        echo '  <div class="widget-body" style="min-height: 90px;">';
        //  echo '  <div class="widget-toolbox padding-8 clearfix noprint">';
        echo  sethtmlissue((($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)),$t_bugslist);
        //  echo ' </div>';
        echo ' </div>';
        echo ' </div>';
    }else{
        echo '  <div class="col-xs-2 widget-box '.(isWeekend($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))?'widget-color-red':'widget-color-blue').' ml-2" style="margin-left: 10px !important;width: 150px;" >';
        echo '  <div class="widget-header widget-header-small" >';
        echo   '<h4 class="widget-title lighter">'.$daylist[timestampf($t_year.'-'.$t_mount.'-'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)))].' <span style="float:right;">'.(($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)).'&nbsp;</span></h4>';
        echo ' </div>';
        echo '  <div class="widget-body" style="min-height: 90px;">';
        //  echo '  <div class="widget-toolbox padding-8 clearfix noprint">';
        echo  sethtmlissue((($i+1)-(timestampf($t_year.'-'.$t_mount.'-'.'1') - 1)),$t_bugslist);
        //  echo ' </div>';
        echo ' </div>';
        echo ' </div>';
    }

}

}
echo '</div>';
echo '</div>';



echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
layout_page_end();



