<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\services\actions\AbstractAction;

class ButtonActionWidget extends Widget
{
  public $task;
  public $type;

  public $response = null;

  public function run()
  {
    $actions = $this->task->getAvailableActions(Yii::$app->user->id, $this->type);

    $content = '';

    foreach ($actions as $action) {
      $url = Url::to($action->getUrl($this->task->id, $this->response?->id));

      $buttonColorClass = match ($action->getCodeName()) {
        'act_response' => 'blue',
        'refusal' => 'orange',
        'completion' => 'pink',
        'cancel' => 'yellow',
        'refuse-contractor' => 'orange',
        'accept' => 'blue',
        default => 'blue'
      };

      $buttonSizeClass = ($this->type === AbstractAction::TYPE_RESPONSE) ? 'button--small' : '';

      $href = $action->isModal() ? '#' : $url;

      $content .= Html::a($action->getName(), $href, [
        'class' => "button button--{$buttonColorClass} {$buttonSizeClass} action-btn",
        'data-action' => $action->getCodeName(),
        'data-url' => $url,
      ]);
    }

    return $content;
  }
}