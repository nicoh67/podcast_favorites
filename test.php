<?php
//header('Content-Type: application/rss+xml; charset=utf-8');

include 'inc/functions.func.php';

$xml = filterXML("http://radiofrance-podcast.net/podcast09/rss_18153.xml", 'item', function($marker) {
    $minutes = str2Secs($marker->getElementsByTagNameNS('http://www.itunes.com/dtds/podcast-1.0.dtd', 'duration')->item(0)->textContent) / 60;

    return [
        "duration" => $minutes > 50,
        "title" => $marker->getElementsByTagName('title')->item(0) != "",
    ];
});

print($xml);

?>