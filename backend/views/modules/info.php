<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Modules;

/* @var $this yii\web\View */
/* @var $model common\entities\Modules */

$this->title = $model->title ?: 'Модуль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modules-info">

    <style>
        h3 {
            font-size: 18px;
            font-weight: bold;
        }
        h4 {
            font-size: 16px;
            font-weight: bold;
        }

        .red {
            color: red;
        }

        .code_block {
            padding: 10px;
            background-color: white;
        }
    </style>

    <div class="box">
        <div class="box-body">
            <h3>
                Общее
            </h3>
            <p>
                Все ссылки рекомендуется вставлять относительными.
            </p>
            <p>
                Большинство списков поддерживает сортировку, в том числе перетягиванием.
            </p>
            <h3>
                Магазин
            </h3>
            <p>
                Разделы содержат Категории, Категории содержат Товары.
            </p>
            <p>
                Товары могут быть помечены "На главную" для отображения в блоке "Рекомендуемые".
            </p>
            <p>
                Для товара указываются его связки "Вес-цена" и "Опции".
            </p>
            <p>
                Для товара требуется минимум одна связка "Вес-цена", цена товара определяется соответствующим весом.
            </p>
            <p>
                Для веса задается также бизнес-цены, также могут быть заданы минимальное количество в заказе, наличие товара в поле "Остатки".
            </p>
            <p>
                При пустом поле "Остатки" остатки товара не учитываются, для указания нулевого остатка нужно явно указать "0".
            </p>
            <p>
                "Опции" для каждого товара также имеют цену и бизнес-цену.
            </p>
            <h4>
                Карта доставки
            </h4>
            <p>
                Состоит из произвольных зон-полигонов. Зоны не должны наслаиваться.
            </p>
            <p>
                Вверху карты присутствуют пиктограммы смены режимов взаимодействия: рисование или выделение зон.
            </p>
            <p>
                В выделенном состоянии можно изменять конфигурацию зоны, удалить ее, сместить, задать цвет.
            </p>
            <p>
                Также для каждой зоны может быть проставлена галочка "В пределах МКАД", что учитывается при расчете доставки. Текущая конфигурация расчета доставки поддерживает только дифференциацию между пределами МКАД и за пределами.
            </p>
            <p>
                Изменения в карте задействуются после сохранения.
            </p>
            <h4>
                Интервалы доставки
            </h4>
            <p>
                Указываются временные интервалы в формате ЧЧ:ММ и стоимость доставки.
            </p>
            <h4>
                Стоимость доставки
            </h4>
            <p>
                Указываются минимальная сумма заказа и суммы бесплатной доставки для соответствующих зон.
            </p>
            <h3>
                Пользователи
            </h3>
            <p>
                Пользователю может быть присвоен статус "Бизнес-клиент". У такого пользователя будут отображены соответствующие цены.
            </p>
            <h3>
                Размеры изображений
            </h3>
            <h4>
                Главная
            </h4>
            <p>
                Слайдер (и прочие верхние блоки на других страницах) - 1920*1080
            </p>
            <p>
                Текстовые блоки - 700
            </p>
            <p>
                Фото 1, Фото 2 - 1920*1080
            </p>
            <p>
                Продукт - 800
            </p>
            <h4>
                Ресторан
            </h4>
            <p>
                Тексты - 700
            </p>
            <p>
                Меню - 750
            </p>
            <h4>
                Галерея
            </h4>
            <p>
                Категории: высокий блок - 700*950, остальные - 700*450
            </p>
            <p>
                Внутри галереи: 1920 или 900, высота - в зависимости от порядка в списке фото.
            </p>
            <h4>
                Истории
            </h4>
            <p>
                Заглавное фото - 600*750, в тексте - 1100
            </p>

        </div>
    </div>
</div>
