<?php
#-----------------------------------------------------#
#          ********* WAP-MOTORS *********             #
#             Made by   :  VANTUZ                     #
#               E-mail  :  visavi.net@mail.ru         #
#                 Site  :  http://pizdec.ru           #
#             WAP-Site  :  http://visavi.net          #
#                  ICQ  :  36-44-66                   #
#  Вы не имеете право вносить изменения в код скрипта #
#        для его дальнейшего распространения          #
#-----------------------------------------------------#	
require_once "../includes/start.php";
require_once "../includes/functions.php";
require_once "../includes/header.php";
include_once "../themes/".$config['themes']."/index.php";

if (isset($_GET['action'])) {$action = check($_GET['action']);} else {$action = "";}

echo '<img src="../images/img/partners.gif" alt="image" /> <b>Исправительная</b><br /><br />';	

if (is_user()){

############################################################################################
##                                    Главная страница                                    ##
############################################################################################
if($action==""){

echo 'Если вы не злостный нарушитель, но по какой-то причине получили строгое нарушения и хотите от него избавиться<br />';
echo 'Тогда вы попали по адресу. Здесь самое лучшее место, чтобы встать на путь исправления<br /><br />';
echo 'Снять нарушение можно раз в месяц при условии, что с вашего последнего бана вы не нарушали правил и были добросовестным участником сайта<br />';
echo 'Также вы должны будете выплатить банку штраф в размере '.moneys(100000).'<br />';
echo 'Если с момента вашего последнего бана прошло менее месяца или у вас нет на руках суммы для штрафа, тогда строгое нарушение снять не удастся<br /><br />';
echo 'Общее число строгих нарушений: <b>'.(int)$udata[64].'</b><br />';

if($udata[52]>0 && $udata[64]>0){
$lost_time = round(((SITETIME - $udata[52]) / 3600) / 24);
echo 'Дней прошедших с момента последнего нарушения: <b>'.$lost_time.'</b><br />';
} else {echo 'Дата последнего нарушения не указана<br />';}

echo 'Денег на руках: <b>'.moneys($udata[41]).'</b><br /><br />';

if ($udata[64]>0 && $lost_time>=30 && $udata[41]>=100000){
echo 'У вас есть возможность снять нарушение<br />';
echo '<img src="../images/img/open.gif" alt="image" /> <b><a href="razban.php?action=go&amp;'.SID.'">Снять нарушение</a></b><br />';
} else {
echo '<b>Вы не можете снять нарушение</b><br />';
echo 'Возможно у вас нет нарушений, не прошло еще 30 дней или недостатачная сумма на счету<br />';
}}


############################################################################################
##                                   Снятие нарушений                                     ##
############################################################################################
if($action=="go"){

$lost_time=round(((SITETIME - $udata[52]) / 3600) / 24);
if($udata[64]>0 && $lost_time>=30 && $udata[41]>=100000){

//------------------------------ Запись в профиль ----------------------------//
$ufile = file_get_contents(DATADIR."profil/$log.prof"); 
$udata = explode(":||:",$ufile);

$udata[52]=SITETIME;
$udata[64]--;
$udata[41]=$udata[41]-100000;

$utext = "";
for ($u=0; $u<$config['userprofkey']; $u++){
$utext.=$udata[$u].':||:';}

if($udata[0]!="" && $udata[1]!="" && $udata[4]!="" && $utext!=""){
$fp=fopen(DATADIR."profil/$log.prof","a+");
flock($fp,LOCK_EX);           
ftruncate($fp,0);                                                                 
fputs($fp,$utext);
fflush($fp);
flock($fp,LOCK_UN);
fclose($fp);  
unset($utext);
}

echo 'Нарушение успешно списано, с вашего счета снято <b>'.moneys(100000).'</b><br />';
echo 'Следующее нарушение вы сможете снять не ранее чем через 1 месяц<br />';	

echo 'Число строгих нарушений: <b>'.(int)$udata[64].'</b><br />';
echo 'Остаток на счету: <b>'.moneys($udata[41]).'</b><br />';

} else {
echo '<b>Вы не можете снять нарушение</b><br />';
echo 'Возможно у вас нет нарушений, не прошло еще 30 дней или недостатачная сумма на счету<br />';
}

echo '<br /><img src="../images/img/back.gif" alt="image" /> <a href="razban.php?'.SID.'">Вернуться</a>'; 
}

} else {show_login('Вы не авторизованы, чтобы снять нарушение, необходимо');}

echo '<br /><img src="../images/img/homepage.gif" alt="image" /> <a href="../index.php?'.SID.'">На главную</a>';
include_once "../themes/".$config['themes']."/foot.php";
?>