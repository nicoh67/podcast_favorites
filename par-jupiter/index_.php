<?php
header('Content-Type: application/rss+xml; charset=utf-8');

$xml = simplexml_load_file("http://radiofrance-podcast.net/podcast09/rss_18153.xml");

echo('<'.'?xml version="1.0" encoding="UTF-8"?'.'>');
?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:pa="http://podcastaddict.com" xmlns:podcastRF="http://radiofrance.fr/Lancelot/Podcast#" xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" version="2.0">
  <channel>
    <title>Par Jupiter (versions longues) !</title>
    <link>https://www.franceinter.fr/emissions/par-jupiter</link>
    <description>Rendez-vous sur l'application Radio France pour découvrir tous les autres épisodes / Par Jupiter ! Les irréductibles de l'équipe « Si tu écoutes, j'annule tout » n'annulent rien et reprennent du service pour secouer de l'info. L'actualité n'a qu'à bien se tenir !</description>
    <language>fr</language>
    <copyright>Radio France</copyright>
    <lastBuildDate><?php echo date("l, F d, Y") ?></lastBuildDate>
    <generator>Radio France</generator>
    <image>
      <url>https://cdn.radiofrance.fr/s3/cruiser-production/2021/04/095ac0b6-7b87-4eee-a58a-9a923bead61a/1400x1400_rf_omm_0000029022_ite.jpg</url>
      <title>Par Jupiter !</title>
      <link>https://www.franceinter.fr/emissions/par-jupiter</link>
    </image>
    <itunes:author>France Inter</itunes:author>
    <itunes:category text="Comedy"/>
    <itunes:explicit>no</itunes:explicit>
    <itunes:image href="https://cdn.radiofrance.fr/s3/cruiser-production/2021/04/095ac0b6-7b87-4eee-a58a-9a923bead61a/1400x1400_rf_omm_0000029022_ite.jpg"/>
    <itunes:owner>
      <itunes:email>podcast@radiofrance.com</itunes:email>
      <itunes:name>Radio France</itunes:name>
    </itunes:owner>
    <itunes:subtitle>Par Jupiter (versions longues) !</itunes:subtitle>
    <itunes:summary>Par Jupiter (versions longues) ! Les irréductibles de l'équipe « Si tu écoutes, j'annule tout » n'annulent rien et reprennent du service pour secouer de l'info. L'actualité n'a qu'à bien se tenir !</itunes:summary>
    <itunes:new-feed-url>https://radiofrance-podcast.net/podcast09/35099478-7c72-4f9e-a6de-1b928400e9e5/rss_18153.xml</itunes:new-feed-url>
    <pa:new-feed-url>https://radiofrance-podcast.net/podcast09/d4463877-caa3-4507-9399-f5eb00fde027/rss_18153.xml</pa:new-feed-url>
    <podcastRF:originStation>1</podcastRF:originStation>
    <googleplay:block>yes</googleplay:block>
    <?php
    foreach($xml->channel->item as $item) {
        $ns_itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
        $ns_podcastRF = $item->children('http://radiofrance.fr/Lancelot/Podcast#');

        $duration = $ns_itunes->duration;
        $duration = (strtotime($duration) - strtotime('TODAY')) / 60;

        if($duration < 10)
            continue;

        echo('
        <item>
            <title>'. $item->title .'</title>
            <link>'. $item->link .'</link>
            <description>'. $item->description .'</description>
            <author>'. $item->author .'</author>
            <category>'. $item->category .'</category>
            <enclosure url="'. $item->enclosure['url'] .'" length="'. $item->enclosure['length'] .'" type="'. $item->enclosure['type'] .'"/>
            <guid isPermalink="false">'. $item->guid .'</guid>
            <pubDate>'. $item->pubDate .'</pubDate>');
        
        foreach($ns_podcastRF as $k=>$v) {
            echo('
            <podcastRF:'. $k .'>'. $v .'</podcastRF:'. $k .'>');
        }

        foreach($ns_itunes as $k=>$v) {
            echo('
            <itunes:'. $k .'>'. $v .'</itunes:'. $k .'>');
        }

        echo('
            <googleplay:block>yes</googleplay:block>
        </item>');

    } ?>

  </channel>
</rss>