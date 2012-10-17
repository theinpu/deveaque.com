<?php

require_once 'Page.php';
require_once 'htdocs/hybridauth/Hybrid/Auth.php';

class AuthHandler extends Page {

    private $configFile = '';
    private $adapter;
    private $userProfile = null;

    public function __construct($slim) {
        parent::__construct($slim);
        $this->configFile = dirname(__FILE__).'/../../htdocs/hybridauth/config.php';
    }

    public function login() {
        if(isset($_GET["auth"])) {
            try {
                $hybridauth = new Hybrid_Auth($this->configFile);
                $this->adapter = $hybridauth->authenticate("Vkontakte");
                $this->userProfile = $this->adapter->getUserProfile();
            }
            catch(Exception $e) {
                die("<b>got an error!</b> ".$e->getMessage());
            }
        }
        if(is_null($this->userProfile)) {
            ?>
        <p>Нажмите Войти для авторизации.</p>
        <h2><a href="/login?auth=1">Войти</a></h2>
        <?php
        }
        else {
            ?>
        <fieldset>
            <legend>twitter данные</legend>
            <b>Привет <?php echo $this->userProfile->displayName; ?></b>
            <hr/>
            <b>Hybridauth access tokens for twitter:</b>
            <pre><?php print_r($this->adapter->getAccessToken()); ?></pre>
        </fieldset>
        <?php
        }
    }

}
