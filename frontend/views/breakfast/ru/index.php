<?php


use yii\helpers\Url;


/* @var $categories \common\entities\MenuCategories[] */
/* @var $chefs \common\entities\Chef[] */
/* @var $this yii\web\View */
\frontend\assets\AppAsset::register($this);

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">

    <title>Bulka</title>

    <script src="/js/menu/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="/js/menu/script.js?v=<?=rand()?>" type="text/javascript"></script>

    <!--CSS-->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ledger&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/menu/style.css?v=<?=rand()?>" type="text/css" />


</head>
<body>


    <div class="menu-page">

        <div class="page-wrapper">
                    <div class="logo-block">
                        <a href="#">
                            <img src="/files/logo.svg" alt="logo">
                        </a>
                    </div>

            <div class="sticky-block">
                <h1 class="title">
                    УТРО
                </h1>

                <nav>
                    <div class="nav-container swiper">
                        <ul class="swiper-wrapper">
                            <?php foreach ($categories as $i => $category) { ?>
                                <li class="swiper-slide">
                                    <a href="#block_<?= $category->id ?>"<?if($i == 0):?> class="active"<?endif;?>>
                                        <?= $category->title_ru ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </nav>
            </div>

        <?php foreach ($chefs as $chef){ ?>
            <div class="chief-block">
                <h2 class="menu-title">
                    ШЕФ-ПОВАР
                </h2>

                <div class="name">
                    <?=$chef->name_ru?>
                </div>

                <div class="photo">
                    <img src="<?=$chef->image?>" alt="<?=$chef->image?>">
                </div>

                <div class="chief-descr">

                    <div class="descr">
                        <?=$chef->description_ru?>
                    </div>

                    <a target="_blank" href="<?=$chef->href_ru?>" class="chief-link">
                        <?=$chef->link_ru?>

                        <div class="icon">
                            <img src="/files/icons/arrow.svg" alt="#">
                        </div>
                    </a>

                </div>

            </div>

        <?php } ?>

            <div class="menu-type-block">
                <a href="#">
                <span class="ico">
                    <img src="/files/icons/season.svg" alt="#">
                </span>
                    Сезонные блюда
                </a>
                <a href="#">
                <span class="ico">
                    <img src="/files/icons/vegetarian.svg" alt="#">
                </span>
                    Вегетарианские блюда
                </a>
            </div>

            <div class="menu-container">
                <?php foreach ($categories as $category) { ?>
                    <div id="block_<?= $category->id ?>" class="menu-block">
                        <h2 class="menu-title">
                            <?= $category->title_ru ?>
                        </h2>
                        <?php foreach ($category->products as $product) { ?>
                            <?php if ($product->status === 1) { ?>
                                <div class="item-row">
                                    <div class="title-block">
                                        <h4>
                                            <img src="<?= $product->iconTitle->image ?>" alt="<?=$product->iconTitle->image ?>">
                                            <?= $product->title_ru ?>
                                        </h4>
                                        <div style="margin-left: 8px;" class="descr">
                                            <?= $product->title_desc_ru?>
                                        </div>
                                    </div>

                                    <div class="price">
                                        <?= $product->price ?>
                                    </div>
                                </div>

                                <?php if ($product->description_ru) { ?>
                                    <div class="image-text-block">
                                        <?php if (!$product->image_name) { ?>
                                            <div class="ico">
                                                <img src="<?= $product->iconDesc->image ?>" alt="<?=$product->iconDesc->image?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="image">
                                                <img src="<?= $product->image ?>" alt="<?=$product->image?>">
                                            </div>
                                        <?php } ?>
                                        <div class="descr">
                                            <?= $product->description_ru ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($product->additional_ru){ ?>
                                    <div class="tip-block">
                                        <?=$product->additional_ru?>
                                    </div>
                                <?php } ?>

                                <?php if ($product->link_ru) { ?>
                                    <a target="_blank" href="<?= $product->href_ru ?>" class="link-block">
                                        <h4>
                                            <div class="ico-block">
                                                <img src="<?= $product->iconLink->image ?>" alt="#">
                                            </div>
                                            <?= $product->link_ru ?>
                                        </h4>

                                        <div class="icon">
                                            <img src="/files/icons/arrow.svg" alt="#">
                                        </div>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

        </div>


    </div>

</body>
</html>