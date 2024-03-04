<?php

use common\entities\Contacts;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\entities\Socials;
use common\entities\BonusDocs;
use common\entities\Modules;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$socials = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);
$phone = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1])->andWhere(['type' => 'phone'])->one();
}, Yii::$app->params['cacheTime']);
$email = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1])->andWhere(['type' => 'envelope'])->one();
}, Yii::$app->params['cacheTime']);
$bonus_docs = BonusDocs::getDb()->cache(function () {
    return BonusDocs::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);
$module_14 = Modules::getDb()->cache(function () {
    return Modules::findOne(14);
}, Yii::$app->params['cacheTime']);


$menuItems1 = [
    ['label' => Yii::t('app', 'Меню доставки'), 'url' => ['catalog/index']],
    ['label' => Yii::t('app', 'Рестораны'), 'url' => ['site/restaurants']],
    ['label' => Yii::t('app', 'Галерея'), 'url' => ['site/galleries']],
//    ['label' => Yii::t('app', 'О нас'), 'url' => ['site/about']],
];
$menuItems2 = [
    ['label' => Yii::t('app', 'Истории'), 'url' => ['site/stories']],
    ['label' => Yii::t('app', 'Контакты'), 'url' => ['site/contacts']],
];
if (Yii::$app->user->isGuest) {
    $menuItems2[] = ['label' => Yii::t('app', 'Вход'), 'url' => ['site/login']];
} else {
    $menuItems2[] = ['label' => Yii::t('app', 'Личный кабинет'), 'url' => ['/account/index']];
}
$menuItems2[] = [
    'label' => 'Корзина (<span class="cart-qty">' . Yii::$container->get('common\models\Cart')->getTotalAmount() . '</span>)',
    'url' => ['/cart/index']
];

if (!Yii::$app->session['orig_ref']) {
    Yii::$app->session['orig_ref'] = $_SERVER["HTTP_REFERER"];
}

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!--Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">




    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '180402310476083');
        fbq('track', 'PageView');
    </script>

    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=180402310476083&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(75908143, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/75908143" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <meta name="facebook-domain-verification" content="qo1vl84m2fmey0ktf8kl9h03vuxvgc" />

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="start"><?= Yii::$app->name; ?></div>

<?= $this->render('mainNav', [
    'menuItems1' => $menuItems1,
    'menuItems2' => $menuItems2,
    'phone' => $phone,
    'socials' => $socials,
]); ?>

<!--MAIN-->
<div id="main">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<?= $this->render('footer', [
    'phone' => $phone,
    'email' => $email,
    'socials' => $socials,
    'bonus_docs' => $bonus_docs,
    'module_14' => $module_14,
]); ?>

<?= $this->render('_reserve_popup'); ?>

<?= $this->render('_search_popup'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
