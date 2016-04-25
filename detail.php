<?php
$query = isset($_GET['q']) && is_string($_GET['q']) ? $_GET['q'] : '';
$query = $_GET['q'];
$words = explode(' ', $query);
$query = urlencode($query);

$year = isset($_GET['y']) ? $_GET['y'] : 1800;
$next_year = $year + 1;

$min_words = isset($_GET['mw']) && in_array($_GET['mw'], array(1,2,3,4,5,10,15)) ? $_GET['mw'] : 1;

$url = "http://solr.kbresearch.nl/solr/DDD_artikel_research/select?";
$url .= "q=(spatial:Landelijk*+spatial:Regionaal*)+AND+date:[".$year."-01-01T00:00:00Z+TO+".$next_year."-01-01T00:00:00Z}+".$query;
$url .= "&defType=edismax&mm=".$min_words."&rows=100";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$response = new SimpleXMLElement($response);
$docs = $response->result->doc;
?>

<!DOCTYPE html>
<html xmlns:xlink="http://www.w3.org/1999/xlink">
    <head>
        <meta content="text/html" charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Dictionary viewer</title>
        <link rel="stylesheet" type="text/css" href="stylesheets/style.css" />
    </head>
    <body>
        
        <div id="wrapper">

            <div id="logo">
                <h1>Results for <?=$year?></h1>
            </div>
            <div id="top_articles">
                <ul>
                    <? foreach($docs as $doc) {
                        $id = $doc->xpath('str[@name="identifier"]')[0];
                        $id = substr($id, 0, strlen($id) - 4); ?>
                    <li><a href="<?=$id?>" target="_blank"><?=$id?></a></li>
                    <? } ?>
                </ul>
            </div>

            <div id="footer"></div>

        </div>

   </body>
</html>

