<?php
namespace modules\YkRss;

use Craft;
use yii\base\Module;
use yii\base\Event;

use craft\web\View;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\web\twig\variables\CraftVariable;

use modules\YkRss\services\Feeds;
use modules\YkRss\variables\YkRssVariable;
use modules\YkRss\fields\PodcastEpisodeField;

class YkRss extends Module{
  /**
   * @var Module Self-referential module property.
   */
  public static $instance;

  public function init(){
    self::$instance = $this;
    Craft::setAlias('@ykrss', __DIR__);
    parent::init();

    $this->setComponents([ 'feeds' => Feeds::class ]);

    Event::on(
      CraftVariable::class,
      CraftVariable::EVENT_INIT,
      function (Event $event) {
        $variable = $event->sender;
        $variable->set('ykRss', YkRssVariable::class);
      }
    );

    Event::on(
      View::class,
      View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
      function(RegisterTemplateRootsEvent $event) {
        $event->roots['ykrss'] = __DIR__ . '/templates';
      }
    );

    Event::on(
      Fields::class,
      Fields::EVENT_REGISTER_FIELD_TYPES,
      function (RegisterComponentTypesEvent $event) {
        Craft::debug(
          'Fields::EVENT_REGISTER_FIELD_TYPES',
          __METHOD__
        );
        $event->types[] = PodcastEpisodeField::class;
      }
    );
  }
}
