<?php

namespace common\models\storage;

use yii\web\Session;

class SessionStorage implements StorageInterface
{
    private $key;
    private $session;

    public function __construct($key, Session $session)
    {
        $this->key = $key;
        $this->session = $session;
    }

    public function load()
    {
        return $this->session->get($this->key, []);
    }

    public function save(array $items)
    {
        $this->session->set($this->key, $items);
    }
} 