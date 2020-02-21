<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="flex-center position-ref full-height">

    <div class="code"><?= Html::encode($this->title) ?></div>

    <div class="message" style="padding: 10px;">
        <?= nl2br(Html::encode($message)) ?>
    </div>

</div>
