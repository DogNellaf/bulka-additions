<?php

use common\entities\MenuCategories;
use common\entities\BreakfastCategories;
use common\entities\ProductCategories;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $user \common\entities\User */
/* @var $productCategory \common\entities\ProductCategories */
/* @var $productItems array */
/* @var $menuProductItems array */
/* @var $schoolItems array */

$user = Yii::$app->user->identity;

$productCategories = ProductCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();
foreach ($productCategories as $productCategory) {
    $ids = ArrayHelper::getColumn($productCategory->products, 'id');
    $productItems[] = ['label' => $productCategory->title,
        'icon' => (
            ($this->context->id == 'products' && Yii::$app->controller->actionParams['slug'] == $productCategory->slug) or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ) ? 'folder-open' : 'folder',
        'active' => (
            Yii::$app->controller->actionParams['slug'] == $productCategory->slug or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ),
        'url' => ['/products', 'slug' => $productCategory->slug]];
}


$menuCategories = MenuCategories::find()->andWhere(['status' => 1])->all();

foreach ($menuCategories as $menuCategory) {
    $ids = ArrayHelper::getColumn($menuCategory->products, 'id');
    $menuProductItems[] = ['label' => $menuCategory->title_ru,
        'icon' => (
            ($this->context->id == 'products' && Yii::$app->controller->actionParams['slug'] == $menuCategory->slug) or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ) ? 'folder-open' : 'folder',
        'active' => (
            Yii::$app->controller->actionParams['slug'] == $menuCategory->slug or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ),
        'url' => ['/menu-products', 'slug' => $menuCategory->slug]];
}

$breakfastCategories = BreakfastCategories::find()->andWhere(['status' => 1])->all();

foreach ($breakfastCategories as $breakfastCategory) {
    $ids = ArrayHelper::getColumn($breakfastCategory->products, 'id');
    $breakfastProductItems[] = ['label' => $breakfastCategory->title_ru,
        'icon' => (
            ($this->context->id == 'products' && Yii::$app->controller->actionParams['slug'] == $breakfastCategory->slug) or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ) ? 'folder-open' : 'folder',
        'active' => (
            Yii::$app->controller->actionParams['slug'] == $breakfastCategory->slug or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ),
        'url' => ['/breakfast-products', 'slug' => $breakfastCategory->slug]];
}

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->userProfile->getAvatar('/images/anonymous.jpg') ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->getPublicIdentity() ?></p>
                <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                    <i class="fa fa-circle text-success"></i>
                    <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                </a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Редактор', 'options' => ['class' => 'header']],
                    //['label' => 'Файл-менеджер', 'icon' => 'file-image-o', 'url' => ['/file-manager']],

                    ['label' => 'Главная',
                        'icon' => (
                            (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'index')
                            or ($this->context->id == 'main-slider')
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 1)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 2)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 3)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 4)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 5)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 11)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'index'),
                                'url' => ['/seo/view', 'page' => 'index']
                            ],
                            ['label' => 'Главный слайдер', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'main-slider'), 'url' => ['/main-slider']],
                            ['label' => 'Первый текстовый блок', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 1), 'url' => ['/modules/view', 'id' => 1]],
                            ['label' => 'Рекомендуемые блюда (описание)', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 2), 'url' => ['/modules/view', 'id' => 2]],
                            ['label' => 'Фото 1', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 3), 'url' => ['/modules/view', 'id' => 3]],
                            ['label' => 'Второй текстовый блок', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 4), 'url' => ['/modules/view', 'id' => 4]],
                            ['label' => 'Третий текстовый блок', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 11), 'url' => ['/modules/view', 'id' => 11]],
                            ['label' => 'Фото 2', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 5), 'url' => ['/modules/view', 'id' => 5]],
                        ]
                    ],
                    ['label' => 'Магазин',
                        'icon' => (
                            $this->context->id == 'product-sections'
                            or $this->context->id == 'product-categories'
                            or $this->context->id == 'products'
                            or $this->context->id == 'delivery-times'
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 7)
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 9)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Разделы', 'icon' => 'bars', 'active' => ($this->context->id == 'product-sections'), 'url' => ['/product-sections']],
                            ['label' => 'Категории', 'icon' => 'bars', 'active' => ($this->context->id == 'product-categories'), 'url' => ['/product-categories']],
                            ['label' => 'Товары', 'icon' => ($this->context->id == 'products') ? 'folder-open' : 'folder', 'url' => '#',
                                'items' => $productItems
                            ],
                            ['label' => 'Карта доставки', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 7), 'url' => ['/modules/update', 'id' => 7]],
                            ['label' => 'Интервалы доставки', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'delivery-times'), 'url' => ['/delivery-times']],
                            ['label' => 'Стоимость доставки', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 9), 'url' => ['/modules/view', 'id' => 9]],
                            ['label' => 'Заказы', 'icon' => 'exclamation-triangle', 'active' => ($this->context->id == 'orders'), 'url' => ['/orders']],
                        ]
                    ],
                    ['label' => 'Основное меню',
                        'icon' =>  'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Товары', 'icon' => ($this->context->id == 'menu-products') ? 'folder-open' : 'folder', 'url' => '#',
                                'items' => $menuProductItems
                            ],
                            ['label' => 'Категории', 'icon' => 'bars', 'active' => ($this->context->id == 'menu-categories'), 'url' => ['/menu-categories']],
                            ['label' => 'Иконки', 'icon' => 'bars', 'active' => ($this->context->id == 'menu-icons'), 'url' => ['/menu-icons']],
                            ['label' => 'Шеф повар', 'icon' => 'bars', 'active' => ($this->context->id == 'chef'), 'url' => ['/chef']],
                        ]
                    ],
                    ['label' => 'Завтраки',
                        'icon' =>  'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Товары', 'icon' => ($this->context->id == 'breakfast-products') ? 'folder-open' : 'folder', 'url' => '#',
                                'items' => $breakfastProductItems
                            ],
                            ['label' => 'Категории', 'icon' => 'bars', 'active' => ($this->context->id == 'breakfast-categories'), 'url' => ['/breakfast-categories']],

                        ]
                    ],
                    ['label' => 'Рестораны',
                        'icon' => (
                            (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'restaurants')
                            or ($this->context->id == 'restaurants')
                            or ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 6)
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'restaurants'),
                                'url' => ['/seo/view', 'page' => 'restaurants']
                            ],
                            ['label' => 'Заглавное фото', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 6), 'url' => ['/modules/view', 'id' => 6]],
                            ['label' => 'Рестораны', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'restaurants'), 'url' => ['/restaurants']],
                        ]
                    ],
                    ['label' => 'Галерея',
                        'icon' => (
                            (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'galleries')
                            or ($this->context->id == 'galleries')
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'galleries'),
                                'url' => ['/seo/view', 'page' => 'galleries']
                            ],
                            ['label' => 'Галерея', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'galleries'), 'url' => ['/galleries']],
                        ]
                    ],
                    ['label' => 'Истории',
                        'icon' => (
                            (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'stories')
                            or ($this->context->id == 'stories')
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'file-code-o',
                                'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'stories'),
                                'url' => ['/seo/view', 'page' => 'stories']
                            ],
                            ['label' => 'Истории', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'stories'), 'url' => ['/stories']],
                        ]
                    ],
                    ['label' => 'Бонусная программа',
                        'icon' => (
                            ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 14)
                            or ($this->context->id == 'bonus-docs')
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Условия программы', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 14), 'url' => ['/modules/view', 'id' => 14]],
                            ['label' => 'Документы', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'bonus-docs'), 'url' => ['/bonus-docs']],
                        ]
                    ],

                    ['label' => 'Пользователи', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'user'), 'url' => ['/user']],
                    ['label' => 'Резерв', 'icon' => 'exclamation-triangle', 'active' => ($this->context->id == 'reserves'), 'url' => ['/reserves']],
                    ['label' => 'Сообщения', 'icon' => 'exclamation-triangle', 'active' => ($this->context->id == 'callbacks'), 'url' => ['/callbacks']],
                    ['label' => 'Контакты', 'icon' => 'address-book-o', 'active' => ($this->context->id == 'contacts'), 'url' => ['/contacts']],
                    ['label' => 'Соцсети', 'icon' => 'facebook', 'active' => ($this->context->id == 'socials'), 'url' => ['/socials']],
                    ['label' => 'Условия доставки', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 12), 'url' => ['/modules/view', 'id' => 12]],
                    ['label' => 'Политика конфиденциальности', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 8), 'url' => ['/modules/view', 'id' => 8]],
                    ['label' => 'Инструкция', 'icon' => 'image', 'active' => ($this->context->id == 'modules' && Yii::$app->controller->actionParams['id'] == 10), 'url' => ['/modules/info', 'id' => 10]],

                    //['label' => 'Модули', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'modules'), 'url' => ['/modules']],
                    ['label' => 'Очистить кеш', 'icon' => 'exclamation-triangle ', 'url' => ['/site/clear-cache']],
                ]
            ]
        ) ?>
    </section>
</aside>
