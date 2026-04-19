<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var app\models\TaskSearch $model */
/** @var array $categories */

?>

<div class="search-form">
  <?php
  $form = ActiveForm::begin([
    'action' => ['tasks/index'],
    'method' => 'get',
  ]);
  ?>

  <h4 class="head-card"><?= $model->getAttributeLabel('category_id') ?></h4>

  <div class="form-group">
    <?= $form
      ->field($model, 'category_id', [
        'template' => '<div class="checkbox-wrapper">{input} </div>',
        'options' => [
          'tag' => false // Отключает стандартную обертку
        ],
      ])
      ->checkboxList($categories, [
        'tag' => false,
        'item' => fn($index, $label, $name, $checked, $value)
          => Html::checkbox($name, $checked, [
            'value' => $value,
            'id' => "category-{$value}",
            'label' => Html::encode($label), // Обернет в label
            'labelOptions' => [
              'class' => 'control-label',
            ],
          ])
      ])
      ->label(false);
    ?>
  </div>

  <h4 class="head-card">Дополнительно</h4>

  <div class="form-group">
    <div class="checkbox-wrapper">
      <?php foreach ($model->getAdditionalFields() as $attribute): ?>
        <?= $form
          ->field($model, $attribute, [
            'template' => '{input}',
            'options' => [
              'tag' => false,
            ],
          ])
          ->checkbox([
            'uncheck' => null,
            'id' => $attribute,
            'label' => $model->getAttributeLabel($attribute),
            'labelOptions' => [
              'class' => 'control-label',
            ],
          ]); ?>
      <?php endforeach; ?>
    </div>
  </div>

  <h4 class="head-card"><?= $model->getAttributeLabel('interval') ?></h4>

  <div class="form-group">
    <?= $form
      ->field($model, 'interval', [
        'template' => "{label}\n{input}", // Лейбл над селектом
      ])
      ->dropDownList($model->optsInterval(), [
        'id' => 'period-value',
        'prompt' => 'Выберите период',
      ])
      ->label(false);
    ?>
  </div>

  <?= Html::submitInput('Искать', [
    'class' => 'button button--blue',
  ]) ?>

  <?php ActiveForm::end(); ?>
</div>