<?php

class RegisterHandler extends Page {

    public function showRegister() {
        $this->displayTemplate('forms/register.twig');
    }

    public function register() {
        $email = $this->getSlim()->request()->post('email');
        $pass = $this->getSlim()->request()->post('pass');
        $pass1 = $this->getSlim()->request()->post('pass_second');

        if(empty($email) || empty($pass) || empty($pass1)) {
            $this->appendDataToTemplate(array('empty' => true));
            $this->displayTemplate('forms/register.twig');

            return;
        }

        if($pass != $pass1) {
            $this->appendDataToTemplate(array('pass' => true));
            $this->displayTemplate('forms/register.twig');

            return;
        }
        try {
            Users::registerUser($email, $pass);
        }
        catch(Exception $e) {
            var_dump($e->getMessage());
        }
        $this->getSlim()->redirect('/');
    }

}
