<?php
use yii\helpers\Url;
?>

<div class="search_popup popup">
    <div class="popup_ins">
        <div class="popup_close"></div>
        <div class="popup_cont">
            <div class="wrapper">
                <form action="<?= Url::to(['/site/search']);?>" method="GET">
                    <div class="input">
                        <input type="text" placeholder="Поиск" name="str">
                    </div>
                    <div class="submit">
                        <button type="submit">
                            <i class="icon-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
