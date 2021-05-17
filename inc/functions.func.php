<?php

function str2Secs($strtime) {
    return strtotime($strtime) - strtotime('TODAY');
}


class PodcastFeedEditor {
    public $dom;
    public $root;
    public $channel;
    public $items;

    public function __construct($xml_file="") {
        $this->dom = new DOMDocument();
        if($xml_file)
            $this->load($xml_file);

    }

    public function load($xml_file) {
        $this->dom->load($xml_file);
        $this->root = $this->dom->documentElement; // This can differ (I am not sure, it can be only documentElement or documentElement->firstChild or only firstChild)

        $this->channel = $this->root->getElementsByTagName("channel")->item(0);
        $this->items = $this->root->getElementsByTagName('item');
    }

    public function changePodcastTitle($new_title) {
        $this->changeChannelElement("title", $new_title);
        $this->changeChannelElement("subtitle", $new_title, "*");
    }

    public function changeChannelElement($localName, $value, $namespace=null) {
        if($namespace)
            $elements = $this->channel->getElementsByTagNameNS($namespace, $localName);
        else
            $elements = $this->channel->getElementsByTagName($localName);
        
        foreach($elements as $el) {
            if($el->parentNode->tagName=="channel")
                return $el->textContent = $value;
        }
        return false;
    }

    public function filter($filterFunction=null) {

        $nodesToDelete = array();

        // Loop trough childNodes
        foreach ($this->items as $item) {

            $ret = $filterFunction($item);
    
            // To remove the marker you just add it to a list of nodes to delete
            // Si le résultat de la fonction est un boolean false, ou un tableau qui n'est pas rempli de true, on supprime le noeud
            if(!is_array($ret) && !$ret || is_array($ret) && $ret != array_filter($ret))
                $nodesToDelete[] = $item;  
        }
    
        // You delete the nodes
        foreach ($nodesToDelete as $node) $node->parentNode->removeChild($node);
    
            
    }
    
    public function save($output_file="") {
        if($output_file)
            $this->dom->save($output_file);
        return $this->dom->saveXML();
    }

    public function render($output_file="") {
        header('Content-Type: application/rss+xml; charset=utf-8');
        $lastPubDate = $this->items->item(0)->getElementsByTagName('pubDate')->item(0)->textContent;
        if($lastPubDate)
            header('Last-Modified: '. $lastPubDate);
        return $this->save($output_file);
    }

}


/**
 * filterXML : filtre les items d'un XML à partir de la fonction $filterFunction, qui doit renvoyer un tableau de booleans
 *
 * EXEMPLE :
 * $xml = filterXML("http://radiofrance-podcast.net/podcast09/rss_18153.xml", function($marker) {
 *     $minutes = str2Secs($marker->getElementsByTagNameNS('http://www.itunes.com/dtds/podcast-1.0.dtd', 'duration')->item(0)->textContent) / 60;
 * 
 *     return [
 *         "duration" => $minutes > 50,
 *         "title" => $marker->getElementsByTagName('title')->item(0) != "",
 *     ];
 * });

 * 
 * @param string $xml_file
 * @param string $itemName
 * @param function $filterFunction
 * @param string $xml_output_file
 * @return string $xml
 */
function filterXML($xml_file, $itemName='item', $filterFunction=null, $xmlModifierFunction=null, $xml_output_file="") {

    $dom = new DOMDocument();
    $dom->load($xml_file);

    $root = $dom->documentElement; // This can differ (I am not sure, it can be only documentElement or documentElement->firstChild or only firstChild)

    $nodesToDelete = array();

    if($xmlModifierFunction)
        $xmlModifierFunction($root, $dom);

    $markers = $root->getElementsByTagName($itemName);

    // Loop trough childNodes
    foreach ($markers as $marker) {
        $ret = $filterFunction($marker);

        // To remove the marker you just add it to a list of nodes to delete
        // Si le résultat de la fonction est un boolean false, ou un tableau qui n'est pas rempli de true, on supprime le noeud
        if(!is_array($ret) && !$ret || is_array($ret) && $ret != array_filter($ret))
            $nodesToDelete[] = $marker;  
    }

    // You delete the nodes
    foreach ($nodesToDelete as $node) $node->parentNode->removeChild($node);

    if($xml_output_file)
        $dom->save($xml_output_file);

    return $dom->saveXML();
}


?>