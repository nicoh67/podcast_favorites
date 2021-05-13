<?php

include '../inc/functions.func.php';

$pfe = new PodcastFeedEditor("http://radiofrance-podcast.net/podcast09/rss_18153.xml");

$pfe->changePodcastTitle("Par Jupiter ! (longues versions)");

$pfe->filter(function($item) {
  return (str2Secs($item->getElementsByTagNameNS('*', 'duration')->item(0)->textContent) / 60) > 15;
});

echo $pfe->render();
