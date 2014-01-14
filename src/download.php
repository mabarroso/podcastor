<?php
require_once 'Collector/Download.php';

define('DATA_PATH', './data/');
define('MAX_DOWNLOAD', 1);

$downloaded = download();

echo "Download $downloaded files\n";

function download() {
    $download = new Download();

    $podcasts = array_filter(glob(DATA_PATH.'/*'), 'is_dir');

    $downloaded = 0;
    foreach ($podcasts as $podcast) {
        $download->setPath($podcast);
        $downloaded += $download->downloadPends(MAX_DOWNLOAD);

        if ($downloaded >= MAX_DOWNLOAD) return $downloaded;
    }

    return $downloaded;
}
