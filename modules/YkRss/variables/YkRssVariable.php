<?php
namespace modules\YkRss\variables;

class YkRssVariable{
  public function parseFeed($feed_url){
    return \modules\YkRss\YkRss::$instance->feeds->parseFeedService($feed_url);
  }
}
