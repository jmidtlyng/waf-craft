<?php
namespace modules\YkRss\fields;

use Craft;
use craft\fields\Dropdown;

class PodcastEpisodeField extends Dropdown {
  public $rssFeedUrl = "";

  public function init(){
    $this->setPodcastFeedOptions();
    parent::init();
  }

  public static function displayName(): string {
    return Craft::t('app', 'Pocast Feed');
  }

  public function getSettingsHtml():? string {
    return Craft::$app->getView()->renderTemplate(
      'ykrss/_components/fields/PodcastFeed_settings',
      ['field' => $this,]
    );
  }

  protected function setPodcastFeedOptions(): void {
    $this->options = [
      [ 'label' => Craft::t('site', 'Choose your Episode'),
        'value' => '',
        'disabled' => true, ]
    ];

    if($this->rssFeedUrl != ''){
      $pod_feed = \modules\YkRss\YkRss::$instance->feeds->parseFeedService($this->rssFeedUrl);

      foreach ($pod_feed['episodes'] as $episode) {
        $this->options[] = [
          'label' => Craft::t('site', $episode['title']),
          'value' => $episode['audio']->url
        ];
      }
    }
  }
}
