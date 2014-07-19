<?php
/**
 * Kittencup Module
 *
 * @date 14-7-11 下午1:26
 * @copyright Copyright (c) 2014-2015 Kittencup. (http://www.kittencup.com)
 * @license   http://kittencup.com
 */

set_time_limit(0);

include __DIR__ . '/simple_html_dom.php';

$path = 'http://www.lagou.com/jobs/list_?pn=%d';

$dom = new \simple_html_dom();
$run = true;
$i = 1;
$zfList = [];

while ($run) {

    $content = file_get_contents(sprintf($path, $i));

    $dom->load($content);

    $nodes = $dom->find('.hot_pos_l .mb10 a');

    if (count($nodes) < 1) {
        $run = false;
    } else {
        /* @var $node \simple_html_dom_node */
        foreach ($nodes as $node) {

            $zfList[] = $node->getAttribute('href');
        }

        $i++;
    }
}


$data = [];

foreach ($zfList as $url) {

    $zpData = [];

    $content = file_get_contents($url);

    $dom->load($content);

    $node = $dom->find('.job_detail h1')[0];
    $zpData['url'] = $url;
    $zpData['title'] = $node->getAttribute('title');
    $zpData['yx'] = $dom->find('.job_request span')[0]->innertext;
    $zpData['dd'] = $dom->find('.job_request span')[1]->innertext;
    $zpData['jy'] = $dom->find('.job_request span')[2]->innertext;
    $zpData['xl'] = $dom->find('.job_request span')[3]->innertext;
    $zpData['xz'] = $dom->find('.job_request span')[4]->innertext;
    $zpData['content'] = $dom->find('.job_bt')[0]->innertext;
    $gs = $dom->find('.job_company h2')[0]->innertext;
    $zpData['gs'] = trim(str_replace('拉勾认证企业', '', strip_tags($gs)));

    $data[] = $zpData;
}

file_put_contents('job.txt', json_encode($data));




