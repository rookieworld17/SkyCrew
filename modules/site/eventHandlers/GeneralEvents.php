<?php

namespace modules\site\eventHandlers;

use modules\site\base\BaseEventHandler;
use craft\web\twig\variables\CraftVariable;
use modules\site\web\twig\Variable;
use yii\base\Event;

class GeneralEvents extends BaseEventHandler
{
    public function attachEventHandlers()
    {
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            static function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('site', Variable::class);
            }
        );
    }
}