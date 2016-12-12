<?php

require 'vendor/autoload.php';

$deleteOldComments = false;

$dir = __DIR__.'/data';

$config = new \JamesMoss\Flywheel\Config($dir, array(
    'formatter' => new \JamesMoss\Flywheel\Formatter\JSON,
));

$repo = new \JamesMoss\Flywheel\Repository('shouts', $config);

if($deleteOldComments) {
    
    $oldShouts = $repo->query()
                ->where('createdAt', '<', strtotime('-1 hour'))
                ->execute();

    foreach($oldShouts as $old) {
        $repo->delete($old->id);
    }
    
}

$shouts = $repo->query()
        ->orderBy('createdAt ASC')
        ->limit(40,0)
        ->execute();

$results = array();

$config = array(
    'language' => '\RelativeTime\Languages\English',
    'separator' => ', ',
    'suffix' => true,
    'truncate' => 1,
);

$relativeTime = new \RelativeTime\RelativeTime($config);
        
foreach($shouts as $shout) {
    $shout->timeAgo = $relativeTime->timeAgo($shout->createdAt);
    $results[] = $shout;
}

header('Content-type: application/json');
echo json_encode($results);