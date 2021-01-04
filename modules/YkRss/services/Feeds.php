<?php
namespace modules\YkRss\services;
use Craft;
use craft\base\Component;
use Laminas\Feed\Reader;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Laminas\Feed\Reader\Http\ClientInterface as FeedReaderHttpClientInterface;
use Laminas\Feed\Reader\Http\Psr7ResponseDecorator;

class GuzzleClient implements FeedReaderHttpClientInterface {
  private $client;

  public function __construct(GuzzleClientInterface $client = null){
    $this->client = $client ?: new Client();
  }

  public function get($uri){
    return new Psr7ResponseDecorator( $this->client->request('GET', $uri) );
  }
}

class Feeds extends Component {
  public function parseFeedService($feed_url){
    Reader\Reader::setHttpClient( new GuzzleClient() );
    $rss_feed = Reader\Reader::import($feed_url);
    $pod_feed = $rss_feed->getExtension('Podcast');

    $feed_data = [
        'title'        => $rss_feed->getTitle(),
        'author'       => $pod_feed->getCastAuthor(),
        'summary'      => $pod_feed->getSummary(),
        'dateModified' => $rss_feed->getDateModified(),
        'episodes'  => [],
    ];

    foreach ($rss_feed as $entry) {
      $feed_ep = $entry->getExtension('Podcast');
      $feed_ep_data = [
          'title'        => $entry->getTitle(),
          'summary'      => $feed_ep->getSummary(),
          'audio'        => $entry->getEnclosure(),
          'img'          => $feed_ep->getItunesImage(),
          'duration'     => $feed_ep->getDuration(),
          'episode'      => $feed_ep->getEpisode(),
          'dateModified' => $entry->getDateModified(),
      ];
      $feed_data['episodes'][] = $feed_ep_data;
    }

    return $feed_data;
  }
}
