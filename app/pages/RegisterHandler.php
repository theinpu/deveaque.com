<?php

class RegisterHandler extends Page {

    public function showRegister() {
        $this->displayTemplate('forms/register.twig');
    }

}
