<?php

class TestModule implements CronModule {

    public function exec($params = array()) {
        var_dump($params);
    }
}
