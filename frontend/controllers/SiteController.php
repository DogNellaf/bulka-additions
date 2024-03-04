<?php
namespace frontend\controllers;

use common\entities\Contacts;
use common\entities\Galleries;
use common\entities\MainSlider;
use common\entities\Modules;
use common\entities\Orders;
use common\entities\Products;
use common\entities\Restaurants;
use common\entities\Stories;
use common\models\SberBank;
use frontend\forms\CallbackForm;
use frontend\forms\ReserveForm;
use frontend\models\SiteSearch;
use Yii;
use common\forms\LoginForm;
use frontend\components\FrontendController;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class SiteController extends FrontendController
{
    public function actionIndex()
    {
        $this->findSeoAndSetMeta('index');
        $mainSlider = MainSlider::getDb()->cache(function () {
            return MainSlider::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        $module_1 = Modules::getDb()->cache(function () {
            return Modules::findOne(1);
        }, Yii::$app->params['cacheTime']);
        $module_2 = Modules::getDb()->cache(function () {
            return Modules::findOne(2);
        }, Yii::$app->params['cacheTime']);
        $module_3 = Modules::getDb()->cache(function () {
            return Modules::findOne(3);
        }, Yii::$app->params['cacheTime']);
        $module_4 = Modules::getDb()->cache(function () {
            return Modules::findOne(4);
        }, Yii::$app->params['cacheTime']);
        $module_4_2 = Modules::getDb()->cache(function () {
            return Modules::findOne(11);
        }, Yii::$app->params['cacheTime']);
        $module_5 = Modules::getDb()->cache(function () {
            return Modules::findOne(5);
        }, Yii::$app->params['cacheTime']);
        $productsSlider = Products::getDb()->cache(function () {
            return Products::find()->andWhere(['status' => 1, 'mainpage_status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        $stories = Stories::getDb()->cache(function () {
            return Stories::find()->andWhere(['status' => 1])->orderBy(['sort' => SORT_DESC])->limit(6)->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('index', [
            'mainSlider' => $mainSlider,
            'module_1' => $module_1,
            'module_2' => $module_2,
            'module_3' => $module_3,
            'module_4' => $module_4,
            'module_4_2' => $module_4_2,
            'module_5' => $module_5,
            'productsSlider' => $productsSlider,
            'stories' => $stories,
        ]);
    }

    public function actionRestaurants()
    {
        $this->findSeoAndSetMeta('restaurants');

        $module = Modules::getDb()->cache(function () {
            return Modules::findOne(6);
        }, Yii::$app->params['cacheTime']);
        $restaurants = Restaurants::getDb()->cache(function () {
            return Restaurants::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('restaurants', [
            'module' => $module,
            'restaurants' => $restaurants,
        ]);
    }

    public function actionRestaurant($slug)
    {
        if (!$restaurant = Restaurants::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'Запрошенная вами страница не существует.'));
        }
        $this->setMeta($restaurant->meta_title, $restaurant->meta_description, $restaurant->meta_keywords);

        $relRestaurant = Restaurants::getDb()->cache(function () use ($restaurant) {
            return Restaurants::find()->andWhere(['status' => 1])->andWhere(['<>','id', $restaurant->id])->orderBy(new Expression('rand()'))->one();
        }, Yii::$app->params['cacheTime']);

        return $this->render('restaurant', [
            'restaurant' => $restaurant,
            'relRestaurant' => $relRestaurant,
        ]);
    }

    public function actionGalleries()
    {
        $this->findSeoAndSetMeta('galleries');

        $galleries = Galleries::getDb()->cache(function () {
            return Galleries::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('galleries', [
            'galleries' => $galleries,
        ]);
    }

    public function actionGallery($slug)
    {
        if (!$gallery = Galleries::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'Запрошенная вами страница не существует.'));
        }
        $this->setMeta($gallery->meta_title, $gallery->meta_description, $gallery->meta_keywords);

        $galleries = Galleries::getDb()->cache(function () {
            return Galleries::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('gallery', [
            'gallery' => $gallery,
            'galleries' => $galleries,
        ]);
    }

    public function actionStories()
    {
        $this->findSeoAndSetMeta('stories');

        $stories = Stories::getDb()->cache(function () {
            return Stories::find()->andWhere(['status' => 1])->orderBy(['sort' => SORT_DESC])->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('stories', [
            'stories' => $stories,
        ]);
    }

    public function actionStory($slug)
    {
        if (!$story = Stories::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException(Yii::t('app', 'Запрошенная вами страница не существует.'));
        }
        $this->setMeta($story->meta_title, $story->meta_description, $story->meta_keywords);

        return $this->render('story', [
            'story' => $story,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionContacts()
    {
        $this->setMeta('Контакты');

        $restaurants = Restaurants::getDb()->cache(function () {
            return Restaurants::find()->andWhere(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);

        return $this->render('contacts', [
            'restaurants' => $restaurants,
        ]);
    }

    public function actionGetCallback()
    {
        $model = new CallbackForm();
        if ($model->load(Yii::$app->request->post())) {
            $respond = $model->create();
            if ($respond) {
                try {
                    $model->mail($respond);
                } catch (\RuntimeException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Спасибо! Ваше сообщение принято'));
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Произошла ошибка.'));
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetReserve()
    {
        $model = new ReserveForm();
        if ($model->load(Yii::$app->request->post())) {
            $respond = $model->create();
            if ($respond) {
                try {
                    $model->mail($respond);
                } catch (\RuntimeException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Спасибо! Ваше сообщение принято'));
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Произошла ошибка.'));
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSearch($str='')
    {
        $this->setMeta('Поиск');
        $str = htmlspecialchars(trim($str));
        $model = new SiteSearch($str);
        $result = $model->search();
        return $this->render('search_result', [
            'result' => $result,
            'param' => $str,
        ]);
    }

    public function actionPolicy()
    {
        $this->setMeta('Политика конфиденциальности');
        $policy = Modules::findOne(8);

        return $this->render('policy', [
            'policy' => $policy,
        ]);
    }

    public function actionBonus()
    {
        $this->setMeta('Бонусная программа');
        $bonus = Modules::findOne(14);

        return $this->render('bonus', [
            'bonus' => $bonus,
        ]);
    }

    public function actionDeliveryTerms()
    {
        $this->setMeta('Условия доставки');
        $description = Modules::findOne(12);

        return $this->render('delivery_terms', [
            'description' => $description,
        ]);
    }

    //todo del
    public function actionTest()
    {
        $result = null;
        $userId = null;
        if (!Yii::$app->user->isGuest) {
            /* @var $user \common\entities\User */
            $user = Yii::$app->user->identity;
            $userId = $user->id;
        }
        if ($userId != 7001) {
            return null;
        }

//        $acquirer = new SberBank();
//        $result = $acquirer->register(164);
//        $acquirer = new SberBank();
//        $result = $acquirer->orderStatus('3c97a787-f34f-7ba6-b5f9-8a090014c498');


        return $this->render('test', [
            'result' => $result,
        ]);
    }

    public function actionTestEmail($order_id)
    {
        $order = Orders::findOne($order_id);

        return $this->renderAjax('/layouts/emails/order_customer_html', [
            'order' => $order,
        ]);
    }

    #######################################################################

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
}
