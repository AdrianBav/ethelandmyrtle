<?php
class ControllerInformationReps extends Controller
{
    public function index()
    {
        $this->language->load('information/reps');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['heading_title'] = $this->language->get('heading_title');

        // Breadcrumbs
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false,
          );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('information/reps'),
            'separator' => $this->language->get('text_separator'),
          );

        // Add graphics libraries
        $this->document->addScript('catalog/view/javascript/raphael.js');
        $this->document->addScript('catalog/view/javascript/jquery/jquery.usmap.js');

        // Load view
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/reps.tpl')) {
            $this->template = $this->config->get('config_template').'/template/information/reps.tpl';
        } else {
            $this->template = 'default/template/information/reps.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header',
        );

        $this->response->setOutput($this->render());
    }
}
