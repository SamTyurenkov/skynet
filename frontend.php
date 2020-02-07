<!DOCTYPE html>
<html lang="ru_RU">
<?php
$url = 'https://sknt.ru/job/frontend/data.json';

//CHECK IF CURL EXISTS
function curlCheck(){
    return function_exists('curl_version');
}
//USE FILE_GET_CONTENTS TO GET JSON
if( ini_get('allow_url_fopen') ) {
$json = file_get_contents($url);
if($json !== false AND !empty($json)) {
    $data = json_decode($json, true);
}
$debug = 'fopen';
} 
//USE CURL TO GET JSON
else if (curlCheck()) {
$cs = curl_init($url);
curl_setopt($cs, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cs, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($cs, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($cs);
$http = curl_getinfo($cs, CURLINFO_HTTP_CODE);
if(curl_errno($cs) == 0 AND $http == 200) {
    $data = json_decode($json, true);
}
$debug = $url.' curl: '.curl_errno($cs).' http: '.$http;
}
//WHY NOT ALSO GET IT WITH JS IF NO CURL OR FOPEN AVAILABLE? 
else {
	//TO DO
};
?>
<head>
<meta id="myViewport" name="viewport" content="width=device-width, initial-scale=1.0">
<style>
html, body {
	margin: 0;
	height: 100%;
    width: 100%;
}
html {
	background: #f2f2f2;
	font-family: Arial;
	color: #444;
}
.tarifs, .tarifs_options, .tarifs_select {
	-ms-flex-line-pack: start;
	    align-content: flex-start;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	    flex-wrap: wrap;
	-webkit-box-align: center;
	    -ms-flex-align: center;
	        align-items: center;
	-webkit-box-pack: center;
	    -ms-flex-pack: center;
	        justify-content: center;
    width: 100%;
	max-width: 1024px;
    margin: 0 auto;
	padding-top: 100px;
    height: calc(100% - 100px);
}
.tarif {
	background: #fff;
	padding: 10px 20px;
	margin: 10px;
	display: inline-block;
	-webkit-box-flex: 1;
	    -ms-flex: 1 0 27%;
	        flex: 1 0 27%;
    max-width: 27%;
	min-width: 27%;
    border: 1px solid #e0e0e0;
	cursor: pointer;
	text-decoration: none;
}
.title {
	font-weight: 600;
	color: #82c12b;
	border-bottom: 1px solid #f2f2f2;
	padding: 5px 0;
	margin-right: -20px;
}
.speed {
	background: #70603e;
	margin: 5px 0 5px -20px;
	padding: 5px 20px;
	color: #fff;
	width: -webkit-fit-content;
	width: -moz-fit-content;
	width: fit-content;
}
.tarif:nth-child(2) .speed, .tarif:nth-child(4) .speed {
	background: #0075d9;
}
.tarif:nth-child(3) .speed, .tarif:nth-child(5) .speed {
	background: #e74807;
}
.price {
	font-weight: 600;
	padding: 5px 0;
	color: #444;
}
.free_options {
	border-bottom: 1px solid #f2f2f2;
	padding: 5px 20px 5px 0;
	margin-right: -20px;
	min-height: 50px;
	position: relative;
	color: #444;
}
.tarifs_options .free_options {
	border-bottom: none;
}
.tarifs .free_options:after, .tarifs_options .free_options:after {
	content: '>';
    position: absolute;
    display: block;
    width: 40px;
    height: 20px;
    right: 10px;
    bottom: 35px;
    font-size: 25px;
    color: #e0e0e0;
	    transform: scale(1, 2);
    -webkit-transform: scale(1, 2);
    -moz-transform: scale(1, 2);
    -ms-transform: scale(1, 2);
    -o-transform: scale(1, 2);
}
.link {
	color: #0075d9;
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    margin: 0 -20px -10px;
	font-size: 14px;
}
.button {
	color: #fff;
    text-align: center;
    background: #82c12b;
    margin: 10px 0 0;
}
.tarifs_options, .tarifs_options .tarif, .tarifs_select {
	display: none;
}
.dates {
	padding: 10px 0;
    color: #777;
}
.back {
	background: #fff;
	position: relative;
    text-align: center;
    padding: 20px;
    margin: 14px;
    width: 100%;
    border: 1px solid #e0e0e0;
    color: #222;
    font-weight: 600;
	cursor: pointer;
}
.back:before {
	content: '<';
    position: absolute;
    display: block;
    width: 40px;
    height: 20px;
    left: 10px;
    top: 12px;
    font-size: 25px;
    color: #82c12b;
    transform: scale(1, 2);
    -webkit-transform: scale(1, 2);
    -moz-transform: scale(1, 2);
    -ms-transform: scale(1, 2);
    -o-transform: scale(1, 2);
}
@media(max-width:1023px) {
	.tarif {
    -webkit-box-flex: 1;
        -ms-flex: 1 0 40%;
            flex: 1 0 40%;
    max-width: 40%;
	min-width: 40%;
	}
}
@media(max-width:640px) {
	.tarifs, .tarifs_options, .tarifs_select {
    -ms-flex-line-pack: start;
        align-content: flex-start;
	padding-top: 0;
    height: 100%;
	}
	.back {
    margin: 0 0 14px;
	border: none;
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
	}
		.tarif {
	-webkit-box-flex: 1;
	    -ms-flex: 1 0 80%;
	        flex: 1 0 80%;
    max-width: 100%;
	min-width: 80%;
	margin: 0 0 10px;
	border: none;
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
	}
	.free_options {
    min-height: auto;
}
}
</style>
</head>
<body>
<!-- SECTION WITH TARIFS -->
<div class="tarifs">
<?php 
$i = 0;
foreach ($data['tarifs'] as $tarif) {
$i++;
$title = $tarif['title'];
$tarifs = $tarif['tarifs'];
$speed = $tarifs[0]['speed'];
//SORT THE ARRAY
usort($tarifs, function($a, $b) { 
     if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
});	
{ ?>
<div class="tarif option<?php echo $i; ?>">
<div class="title">Тариф "<?php echo $title; ?>"</div>
<div class="speed"><?php echo $speed; ?> Мбит/с</div>
<div class="price"><?php echo end($tarifs)['price']/intval(end($tarifs)['pay_period']).' - '.$tarifs[0]['price']/intval($tarifs[0]['pay_period']); ?> Р/мес</div>
<div class="free_options"><?php 
if(array_key_exists('free_options', $tarif)): 
foreach ($tarif['free_options'] as $option) {
echo $option.'<br>'; 
} endif;
?></div>
<a class="link" href="<?php echo $tarif['link']; ?>">узнать подробнее на сайте www.sknt.ru</a>
</div>
<?php }}; ?>
</div>
<!-- SECTION WITH TARIFS OPTIONS-->
<div class="tarifs_options">
<div class="back">

</div>
<?php 
$i = 0;
foreach ($data['tarifs'] as $tarif) {
$i++;
$title = $tarif['title'];
$tarifs = $tarif['tarifs'];
usort($tarifs, function($a, $b) { //Sort the array using a user defined function
     if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
});   
foreach ($tarifs as $options) {	
?>
<div class="tarif option<?php echo $i; ?>">
<div class="title"><?php echo $options['title']; ?></div>
<div class="price"><?php echo $options['price']/intval($options['pay_period']); ?> Р/мес</div>
<div class="free_options">
<?php echo 'разовый платеж - '.$options['price'].' Р<br>';
if(intval($options['pay_period']) > 1) {
	echo 'скидка - '; echo (($tarifs[0]['price'] * intval($options['pay_period'])) - $options['price']).' Р';
}
?> 
</div>
<meta class="period" value="<?php echo $options['pay_period']; ?>"></meta>
<meta class="enddate" value="<?php echo $options['new_payday']; ?>"></meta>
</div>
<?php }};?>
</div>
<!-- SECTION WITH OPTION CONFIRMATION -->
<div class="tarifs_select">
<div class="back">
Выбор тарифа
</div>
<a class="tarif" href="/">
<div class="title"></div>
<div class="price"></div>
<div class="free_options"></div>
<span class="link button" href="/">Выбрать</span>
</a>
</div>
<script type="text/javascript">
var tarifs = document.querySelector('.tarifs');
var tarif = document.querySelectorAll('.tarifs .tarif');

var options = document.querySelector('.tarifs_options');  
var option = document.querySelectorAll('.tarifs_options .tarif');

var confirms = document.querySelector('.tarifs_select');
var confirm = document.querySelector('.tarifs_select .tarif');

var links = document.querySelectorAll('.link');
var backs = document.querySelectorAll('.back');

//PREVENT TARIF CLICK EVENTS ON LINKS
for (var j = 0; j < links.length; j++) {
links[j].addEventListener("click", function(e) {
    e.stopPropagation();
});
}
//WHEN CLICKED TARIF OPENS RELATED OPTIONS
for (var i = 0; i < tarif.length; i++) {
tarif[i].addEventListener('click', function() {	
var num = '.tarifs_options .option'+this.classList[1].slice(this.classList[1].length - 1);
selection();
hideThings();
var opt = document.querySelectorAll(num); 
for (var k = 0; k < opt.length; k++) {
opt[k].style.display = 'inline-block';
}
var number = parseInt(this.classList[1].slice(this.classList[1].length - 1)) - 1;	
document.querySelector('.tarifs_options .back').innerHTML = tarif[number].getElementsByClassName('title')[0].innerHTML;
}, false);
}

//WHEN CLICKED OPTION OPENS RELATED CONFIRMATION
for (var i = 0; i < option.length; i++) {
option[i].addEventListener('click', function() {	
confirmation();
var period = this.getElementsByClassName('period')[0].getAttribute('value');
var mt;
if (period < 2) {
	mt = 'месяц';
} else if (period < 5) {
	mt = 'месяца';
} else {
	mt = 'месяцев';
}
var timecode = this.getElementsByClassName('enddate')[0].getAttribute('value').split(/[+-]/)[0];
var operation = this.getElementsByClassName('enddate')[0].getAttribute('value').match(/[+-]/)[0];
var offset = this.getElementsByClassName('enddate')[0].getAttribute('value').split(/[+-]/)[1];
var date = new Date(timecode * 1000);
if (operation == '+') {
date.setHours(date.getHours() + offset);
} else {
date.setHours(date.getHours() - offset);
}
console.log(offset);
var days = date.getDate();
var month = date.getMonth() + 1;
var year = date.getYear()+1900;
var number = parseInt(this.classList[1].slice(this.classList[1].length - 1)) - 1;	
document.querySelector('.tarifs_select .tarif .title').innerHTML = this.getElementsByClassName('title')[0].innerHTML;
document.querySelector('.tarifs_select .tarif .price').innerHTML = 'Период оплаты '+period+' '+mt+'<br>'+this.getElementsByClassName('price')[0].innerHTML;
document.querySelector('.tarifs_select .tarif .free_options').innerHTML = this.getElementsByClassName('free_options')[0].innerHTML.split('<br>')[0]+'<br>со счета спишется - '+this.getElementsByClassName('free_options')[0].innerHTML.split(' ')[3]+' Р<div class="dates"></div>';
document.querySelector('.tarifs_select .tarif .dates').innerHTML = 'вступит в силу - сегодня<br>активно до - '+days+'.'+month+'.'+year;
document.querySelector('.tarifs_select .tarif .button').setAttribute('href',tarif[number].getElementsByClassName('link')[0].getAttribute('href'));
document.querySelector('.tarifs_select .tarif').setAttribute('href',tarif[number].getElementsByClassName('link')[0].getAttribute('href'));
}, false);
}

//WHEN CLICKED BACK OPENS PREVIOUS SCREEN
backs[0].addEventListener('click', home, false);
backs[1].addEventListener('click', selection, false);

function home() {
	tarifs.style.display = 'flex';
	options.style.display = 'none';
}
function selection() {
	tarifs.style.display = 'none';
	options.style.display = 'flex';
	confirms.style.display = 'none';
}
function confirmation() {
	options.style.display = 'none';
	confirms.style.display = 'flex';
}
function hideThings() {
	for (var l = 0; l < option.length; l++) {
		option[l].style.display = 'none';
	}
}
</script>
</body>

</html>
