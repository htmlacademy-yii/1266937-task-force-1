<?php

namespace app\components;

use yii\widgets\InputWidget;
use yii\helpers\Html;

class UserRatingWidget extends InputWidget
{
  public $sizeClass = '';
  public $readOnly = false;
  public $showValue = false;

  public function run()
  {
    $value = $this->model->{$this->attribute};
    $rating = $value ?? 0;

    $starsToFill = (int) round($rating);
    $id = $this->options['id'] ?? Html::getInputId($this->model, $this->attribute);

    $starsHtml = '';
    for ($i = 1; $i <= 5; $i++) {
      $options = ($i <= $starsToFill) ? ['class' => 'fill-star'] : [];
      if (!$this->readOnly) {
        $options['data-rating'] = $i;
      }
      $starsHtml .= Html::tag('span', '&nbsp;', $options);
    }

    $content = $starsHtml;

    if (!$this->readOnly) {
      $content .= Html::activeHiddenInput($this->model, $this->attribute, [
        'id' => "{$id}",
        'value' => $value ?: ''
      ]);
      $this->registerClientScript($id);
    } elseif ($this->showValue) {
      $content .= Html::tag('b', number_format($rating, 2), [
        'class' => 'rating-number-plain',
        'style' => 'display: inline-block; font-weight: bold; margin-left: 10px; color: inherit; vertical-align: middle;'
      ]);
    }

    return Html::tag('div', $content, [
      'class' => "stars-rating {$this->sizeClass} " . ($this->readOnly ? '' : 'active-stars'),
      'id' => "stars-rating-" . ($id ?? 'readonly'),
      'style' => 'display: flex; align-items: center;'
    ]);
  }

  protected function registerClientScript($id)
  {
    $js = <<<JS
            (function() {
                const container = document.querySelector('#stars-rating-$id');
                if (!container) return;
                const stars = container.querySelectorAll('span');
                const input = document.getElementById('$id');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = parseInt(this.getAttribute('data-rating'));
                        if (input) {
                            input.value = val;
                            input.dispatchEvent(new Event('change')); 
                            input.dispatchEvent(new Event('input'));
                        }
                        stars.forEach((s, i) => s.classList.toggle('fill-star', (i + 1) <= val));
                    });
                });
            })();
JS;
    $this->view->registerJs($js);
  }
}