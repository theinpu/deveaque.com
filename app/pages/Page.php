<?php

abstract class Page {

    /**
     * @var Slim
     */
    private $slim;

    public function __construct($slim) {
        $this->slim = $slim;
    }

    protected final function getSlim() {
        return $this->slim;
    }

    protected final function displayTemplate($template) {
        //$template = $this->parseTemplateName($template);
        $this->getSlim()->view()->display($template);
    }

    private function parseTemplateName($template) {
        if($this->slim->request()->isAjax()) {
            return $this->ajaxTemplate($template);
        }

        return $template;
    }

    private function ajaxTemplate($template) {
        $templateName = basename($template, '.twig');
        $template = str_replace($templateName, $templateName.'.ajax', $template);

        return $template;
    }

    protected final function appendDataToTemplate($data) {
        $this->slim->view()->appendData($data);
    }

    protected final function checkAjaxPermissions() {
        if(!$this->getSlim()->request()->isAjax() || !Application::isAdmin()) {
            $this->getSlim()->halt(404);
        }
    }

}
