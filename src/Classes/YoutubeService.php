<?php
namespace Bishopm\Church\Classes;

use Google\Client;
use Google\Service\YouTube;
use Google\Service\Exception;
use Google\Service\Slides\Thumbnail;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveBroadcastSnippet;
use Google\Service\YouTube\LiveBroadcastStatus;
use Google\Service\YouTube\Thumbnail as YouTubeThumbnail;
use Google\Service\YouTube\ThumbnailDetails;

class YoutubeService
{

    public function createStream(){
        $google_api = setting('services.google_api');
        $client = new Client();
        $client->setDeveloperKey($google_api);
        $service= new YouTube($client);
        
        $liveBroadcast = new LiveBroadcast();
        $liveBroadcastSnippet = new LiveBroadcastSnippet();
        $liveBroadcastSnippet->setChannelId(setting('website.youtube_channel_id'));
        $liveBroadcastSnippet->setDescription('Service streamed at WMC on that Sunday');
        $liveBroadcastSnippet->setScheduledStartTime('2025-03-18T11:50:00.000Z');
        $thumbnailDetails = new ThumbnailDetails();
        $thumbnail = new YouTubeThumbnail();
        $thumbnail->setUrl('https://westvillemethodist.co.za/storage/images/sermon/01JK830S14DW60P0NTBJKQ55TK.png');
        $thumbnailDetails->setDefault($thumbnail);
        $liveBroadcastSnippet->setThumbnails($thumbnailDetails);
        $liveBroadcastSnippet->setTitle('A test stream');
        $liveBroadcast->setSnippet($liveBroadcastSnippet);
        
        $liveBroadcastStatus = new LiveBroadcastStatus();
        $liveBroadcastStatus->setPrivacyStatus('public');
        $liveBroadcast->setStatus($liveBroadcastStatus);
        
        $response = $service->liveBroadcasts->insert('snippet,status', $liveBroadcast);
        return $response;
    }
}