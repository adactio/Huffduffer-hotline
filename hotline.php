<?php

say(". . Welcome to the Huff duffer Hotline.", array(voice => "simon", allowSignals => ""));

$topic = ask("What would you like to hear?", array(
	choices => "accessibility, anarchy, anthropology, apple, art, atheism, biology, books, business, comedy, conference, cooking, cover, culture, design, economics, fiction, food, future, hacking, hip hop, history, horror, indie, internet, interview, iphone, islam, javascript, jazz, language, linguistics, lecture, mashup, music, new, news, philosophy, politics, popular, radiohead, religion, remix, salter cane, sci-fi, science fiction, science, space, story, technology, web, web design, what's new, what's popular",
	voice => "simon",
	bargein => "false",
	attempts => 20,
	onBadChoice =>  "onBadChoice"
));

function onBadChoice($event) {
	say("I’m sorry,  I didn’t understand that! Try a different tag.", array(voice => "simon"));
}

$request = $topic->value;

say("You want to hear ".$request.". Please wait.", array(voice => "simon"));

switch($request) {
	case "what's new":
	case "new":
		$url = "http://huffduffer.com/new/json";
	break;
	case "what's popular":
	case "popular":
		$url = "http://huffduffer.com/popular/json";
	break;
	default:
		$url = "http://huffduffer.com/tags/".urlencode($request)."/json";
	break;
}


ob_start();
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_HEADER,0);
$ok = curl_exec($ch);
curl_close($ch);
$json = ob_get_contents();
ob_end_clean();

$bookmarks = json_decode($json,true);

foreach ($bookmarks['items'] as $item) {
	$result = ask("Would you like to hear: ".$item['title']."?",array(
		voice => "simon",
		choices =>  "yes, no",
		attempts => 20
	));
	if ($result->value == "yes") {
		say($item['url'], array("voice" => "simon"));
	}
}

?>