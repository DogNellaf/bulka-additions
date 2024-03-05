<?php

namespace common\entities;

use yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $identificator_type
 * @property bool $is_register
 * @property bool $blocked
 * @property string $phone
 * @property string $name
 * @property string $gender
 * @property string $birth_date
 * @property string $email
 * @property string $groip_id
 * @property string $group_name
 * @property float $balance
 * @property float $balance_bonus_accumulated
 * @property float $balance_bonus_present
 * @property float $balance_bonus_action
 * @property float $bonus_inactive
 * @property string $bonus_next_activation_text
 * @property bool $write_off_confirmation_required
 * @property bool $registration_confirmation_required
 * @property bool $is_allowed_change_card
 * @property bool $phone_checked
 * @property string $additional_info
 * @property bool $is_refused_receive_messages
 */
class BuyerInfo {

}