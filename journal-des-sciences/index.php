<?php

include '../inc/functions.func.php';

$pfe = new PodcastFeedEditor("https://radiofrance-podcast.net/podcast09/35099478-7c72-4f9e-a6de-1b928400e9e5/rss_14312.xml");

$pfe->changePodcastTitle("Le journal des sciences (La Méthode scientifique)");


$pfe->filter(function($item) {
  return (str2Secs($item->getElementsByTagNameNS('*', 'duration')->item(0)->textContent) / 60) < 15;
});

echo $pfe->render();
