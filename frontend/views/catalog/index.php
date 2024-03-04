 <?php

use yii\helpers\Url;

/* @var $products \common\entities\Products[] */
/* @var $category \common\entities\ProductCategories|null */
/* @var $sections \common\entities\ProductSections[] */
?>

<div id="catalog" class="catalog page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Меню доставки'); ?>
            </div>
        </div>
    </div>

    <div class="catalog_block_wrap">
        <div class="wrapper">
            <div class="catalog_block">
                <div class="catalog_nav animated">
                    <ul class="catalog_nav_list">
                        <li class="catalog_nav_item <?= (!$category) ? 'active' : ''; ?>">
                            <a href="<?= Url::to(['catalog/index']); ?>" class="catalog_nav_item_link">
                                Все блюда
                            </a>
                        </li>
                        <?php foreach ($sections as $key => $section) : ?>
                            <li class="catalog_nav_item <?= ($category->target == $section->id) ? 'active' : ''; ?>">
                                <a href="<?= Url::to(['catalog/index', 'section' => $section->slug]); ?>" class="catalog_nav_item_link <?= ($section->productCategories) ? 'child_link' : ''; ?>">
                                    <?= $section->title; ?>
                                    <?php if ($section->productCategories) : ?>
                                        <i></i>
                                    <?php endif; ?>
                                </a>
                                <?php if ($section->productCategories) : ?>
                                    <ul class="catalog_nav_child_list">
                                        <?php foreach ($section->productCategories as $productCategory) : ?>
                                            <li class="catalog_nav_child_item <?= ($productCategory->id == $category->id) ? 'active' : ''; ?>">
                                                <a href="<?= Url::to(['catalog/index', 'slug' => $productCategory->slug]); ?>" class="catalog_nav_child_item_link">
                                                    <?= $productCategory->title; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= Url::to(['site/delivery-terms']); ?>" class="catalog_delivery_terms_btn lined">
                        Условия доставки
                    </a>
                </div>
                <div class="catalog_list_block">
                    <div class="catalog_list product_list">
                        <?php foreach ($products as $product): ?>
                            <?= $this->render('/catalog/_product', [
                                'product' => $product,
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

