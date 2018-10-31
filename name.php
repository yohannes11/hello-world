<?php
require('src/Functions.php');
require_once('src/PHPImage.php');
session_start();
$username=$_GET["username"];
$userpic=$_GET["userpic"];
$filename=generate_name();
/*
$image_url="https://abrhamd.sgedu.site/fb/wp-content/themes/template/three.php?myimg=".$picture["url"];
save_image($image_url,$filename);
*/

//SCRAPPER


$randomnames=array("Nobody won't find as awkwardly cute and funny as you!",
                    "A person gifted with awesomeness.",
                    "A very fun person to hang out with and is very smart.",
                    "A person who embodies the pinnacle of all the important social aspects.",
                    "A person who uses a friend or acquaintance solely for the purposes of gaining a type of advantage.",
                    "A person who is a professional at doing some sort of service, but does crappy work.",
                    "A person who lives at the expense of others, contributing nothing in return.",
                    "A person who flakes out and ditches their friends.",
                    "A person who stalks people.",
                    "A person who is daring, fun loving and extremely active.",
                    "A person who loves and is addicted to sex.");
$name =$username;
$html = file_get_contents('https://www.urbandictionary.com/define.php?term='.$name); //get the html returned from the following url
$description = 0;
$urban_doc = new DOMDocument();
libxml_use_internal_errors(TRUE); //disable libxml errors
if(!empty($html)){ //if any html is actually returned
    $urban_doc->loadHTML($html);
    libxml_clear_errors(); //remove errors for yucky html
    $urban_xpath = new DOMXPath($urban_doc);
    //get all the h2's with an id
    $pokemon_row = $urban_xpath->query('//div[@class="meaning"]');
    $user = array();
    if($pokemon_row->length > 0){
        foreach($pokemon_row as $row){
            $user = $row->nodeValue . "<br/>";
            $us[] = strstr($user, '.', true);
        }
        if(strlen($us[0])>5){
            $description = $us[0] . ". ";
        }
        elseif (strlen($us[0])<1 && strlen($us[1]>5)) {
            // list is empty.
            $description = $us[1] . ". ";
        }
        elseif ($us[0]==NULL && strlen($us[1]>5)) {
            $description = $us[1] . ". ";
        }
        elseif ((strlen($us[1])<1) && strlen($us[0]<1)) {
            $description = $randomnames[rand(0, 10)]; //$us[0].". "
        }
        else{
            $description = $randomnames[rand(0, 10)];
        }
    }
} //end of== if(!empty($html))
elseif($http_response_header[0]=='HTTP/1.1 404 Not Found'){
    $description = $randomnames[rand(0,3)];
}


//END OF SCRAPPER

$bg = './img/true name meaning/truenamemeaning.jpg';
$imageQueryString = $userpic;
$me = new PHPImage($imageQueryString);
$me->resize(266, 271,true,true);
$image = new PHPImage();
$image->setDimensionsFromImage($bg);
$image->draw($bg);
$image->draw($me, '15%', '34%');
$image->setFont('./font/LBRITE.TTF');
$image->setStrokeWidth(1);
$image->setStrokeColor(array(0,0,0));
$image->setTextColor(array(255, 255, 255));
$image->textBox($description, array(
    'width' => 335,
    'height' => 189,
    'fontSize' => 24,
    'x' => 354,
    'y' => 110
));
$image->setStrokeWidth(1);
$image->setStrokeColor(array(0,0,0));
$image->setTextColor(array(255, 1, 1));
$image->textBox($username.',', array(
    'width' => 150,
    'height' => 30,
    'fontSize' => 40,
    'x' => 465,
    'y' => 72
));




$image->save($filename);
$page_id=$_SESSION["current_id"];
session_destroy();
header('Location: ./Result.php?postid='.$page_id.'&imagesrc='.$filename);
?>