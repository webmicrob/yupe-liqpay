<?php
/**
 * @var integer $id
 * @var string $description
 * @var float $price
 * @var array $settings
 */

?>

<pre>
<?= print_r($params); ?>
<?= print_r($settings); ?>
<?= print_r($order); ?>
</pre>

<?php
    echo $liqpay->cnb_fqqqorm($params);
?>

<?//= CHtml::form("https://www.liqpay.com/api/3/checkout"); ?>
<?php // echo CHtml::hiddenField('data', $data) ?>
<?php //echo CHtml::hiddenField('signature', $signature) ?>
<?//= CHtml::submitButton(Yii::t('LiqpayModule.liqpay','Pay')) ?>
<?//= CHtml::endForm() ?>
