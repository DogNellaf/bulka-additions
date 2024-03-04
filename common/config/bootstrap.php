<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@files', dirname(dirname(__DIR__)) . '/files');
Yii::setAlias('@orders_xml', dirname(dirname(__DIR__)) . '/orders_xml');
Yii::setAlias('@orders_xml_local', dirname(dirname(__DIR__)) . '/orders_xml_local');

// Setting url aliases

Yii::setAlias('@storageUrl', '' . Yii::$app->homeUrl . '/files');