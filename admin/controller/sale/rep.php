<?php
class ControllerSaleRep extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('sale/rep');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/rep');

        $this->getList();
    }

    public function insert()
    {
        $this->load->language('sale/rep');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/rep');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_sale_rep->addRep($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function update()
    {
        $this->load->language('sale/rep');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/rep');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_sale_rep->editRep($this->request->get['rep_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('sale/rep');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('sale/rep');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $rep_id) {
                $this->model_sale_rep->deleteRep($rep_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort='.$this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order='.$this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page='.$this->request->get['page'];
            }

            $this->redirect($this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL'));
        }

        $this->getList();
    }

    private function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'long_title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
              'separator' => false,
        );

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL'),
              'separator' => ' :: ',
        );

        $this->data['insert'] = $this->url->link('sale/rep/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        $this->data['delete'] = $this->url->link('sale/rep/delete', 'token='.$this->session->data['token'].$url, 'SSL');

        $this->data['reps'] = array();

        $data = array(
            'sort'        => $sort,
            'order'     => $order,
            'start'     => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit'     => $this->config->get('config_admin_limit'),
        );

        $reps_total = $this->model_sale_rep->getTotalReps($data);

        $results = $this->model_sale_rep->getReps($data);

        foreach ($results as $result) {
            $action = array();

            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('sale/rep/update', 'token='.$this->session->data['token'].'&rep_id='.$result['rep_id'].$url, 'SSL'),
            );

            $this->data['reps'][] = array(
                'rep_id'          => $result['rep_id'],
                'long_title'     => $result['long_title'],
                'telephone'      => $result['telephone_1'],
                'email'             => $result['email'],
                'website'         => $result['website'],
                'selected'       => isset($this->request->post['selected']) && in_array($result['rep_id'], $this->request->post['selected']),
                'action'         => $action,
            );
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['column_long_title'] = $this->language->get('column_long_title');
        $this->data['column_telephone'] = $this->language->get('column_telephone');
        $this->data['column_email'] = $this->language->get('column_email');
        $this->data['column_website'] = $this->language->get('column_website');
        $this->data['column_action'] = $this->language->get('column_action');

        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $reps_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('sale/rep', 'token='.$this->session->data['token'].$url.'&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;

        $this->template = 'sale/rep_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function getForm()
    {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_wait'] = $this->language->get('text_wait');
        $this->data['text_no_results'] = $this->language->get('text_no_results');

        $this->data['entry_short_title'] = $this->language->get('entry_short_title');
        $this->data['entry_long_title'] = $this->language->get('entry_long_title');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
        $this->data['entry_city'] = $this->language->get('entry_city');
        $this->data['entry_state'] = $this->language->get('entry_state');
        $this->data['entry_country'] = $this->language->get('entry_country');
        $this->data['entry_zipcode'] = $this->language->get('entry_zipcode');
        $this->data['entry_telephone_1'] = $this->language->get('entry_telephone_1');
        $this->data['entry_telephone_2'] = $this->language->get('entry_telephone_2');
        $this->data['entry_fax'] = $this->language->get('entry_fax');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_website'] = $this->language->get('entry_website');
        $this->data['entry_notes'] = $this->language->get('entry_notes');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_territory'] = $this->language->get('entry_territory');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_remove'] = $this->language->get('button_remove');
        $this->data['button_add_territory'] = $this->language->get('button_add_territory');

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->request->get['rep_id'])) {
            $this->data['rep_id'] = $this->request->get['rep_id'];
        } else {
            $this->data['rep_id'] = 0;
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['short_title'])) {
            $this->data['error_short_title'] = $this->error['short_title'];
        } else {
            $this->data['error_short_title'] = '';
        }

        if (isset($this->error['long_title'])) {
            $this->data['error_long_title'] = $this->error['long_title'];
        } else {
            $this->data['error_long_title'] = '';
        }

        if (isset($this->error['telephone_1'])) {
            $this->data['error_telephone_1'] = $this->error['telephone_1'];
        } else {
            $this->data['error_telephone_1'] = '';
        }

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort='.$this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order='.$this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page='.$this->request->get['page'];
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
              'separator' => false,
        );

        $this->data['breadcrumbs'][] = array(
               'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL'),
              'separator' => ' :: ',
        );

        if (!isset($this->request->get['rep_id'])) {
            $this->data['action'] = $this->url->link('sale/rep/insert', 'token='.$this->session->data['token'].$url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('sale/rep/update', 'token='.$this->session->data['token'].'&rep_id='.$this->request->get['rep_id'].$url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('sale/rep', 'token='.$this->session->data['token'].$url, 'SSL');

        if (isset($this->request->get['rep_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $rep_info = $this->model_sale_rep->getRep($this->request->get['rep_id']);
        }

        if (isset($this->request->post['short_title'])) {
            $this->data['short_title'] = $this->request->post['short_title'];
        } elseif (!empty($rep_info)) {
            $this->data['short_title'] = $rep_info['short_title'];
        } else {
            $this->data['short_title'] = '';
        }

        if (isset($this->request->post['long_title'])) {
            $this->data['long_title'] = $this->request->post['long_title'];
        } elseif (!empty($rep_info)) {
            $this->data['long_title'] = $rep_info['long_title'];
        } else {
            $this->data['long_title'] = '';
        }

        if (isset($this->request->post['address_1'])) {
            $this->data['address_1'] = $this->request->post['address_1'];
        } elseif (!empty($rep_info)) {
            $this->data['address_1'] = $rep_info['address_1'];
        } else {
            $this->data['address_1'] = '';
        }

        if (isset($this->request->post['address_2'])) {
            $this->data['address_2'] = $this->request->post['address_2'];
        } elseif (!empty($rep_info)) {
            $this->data['address_2'] = $rep_info['address_2'];
        } else {
            $this->data['address_2'] = '';
        }

        if (isset($this->request->post['city'])) {
            $this->data['city'] = $this->request->post['city'];
        } elseif (!empty($rep_info)) {
            $this->data['city'] = $rep_info['city'];
        } else {
            $this->data['city'] = '';
        }

        if (isset($this->request->post['state'])) {
            $this->data['state_code'] = $this->request->post['state'];
        } elseif (!empty($rep_info)) {
            $this->data['state_code'] = $rep_info['state'];
        } else {
            $this->data['state_code'] = '';
        }

        if (isset($this->request->post['country'])) {
            $this->data['country'] = $this->request->post['country'];
        } elseif (!empty($rep_info)) {
            $this->data['country'] = $rep_info['country'];
        } else {
            $this->data['country'] = '';
        }

        if (isset($this->request->post['zipcode'])) {
            $this->data['zipcode'] = $this->request->post['zipcode'];
        } elseif (!empty($rep_info)) {
            $this->data['zipcode'] = $rep_info['zipcode'];
        } else {
            $this->data['zipcode'] = '';
        }

        if (isset($this->request->post['telephone_1'])) {
            $this->data['telephone_1'] = $this->request->post['telephone_1'];
        } elseif (!empty($rep_info)) {
            $this->data['telephone_1'] = $rep_info['telephone_1'];
        } else {
            $this->data['telephone_1'] = '';
        }

        if (isset($this->request->post['telephone_2'])) {
            $this->data['telephone_2'] = $this->request->post['telephone_2'];
        } elseif (!empty($rep_info)) {
            $this->data['telephone_2'] = $rep_info['telephone_2'];
        } else {
            $this->data['telephone_2'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $this->data['fax'] = $this->request->post['fax'];
        } elseif (!empty($rep_info)) {
            $this->data['fax'] = $rep_info['fax'];
        } else {
            $this->data['fax'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } elseif (!empty($rep_info)) {
            $this->data['email'] = $rep_info['email'];
        } else {
            $this->data['email'] = '';
        }

        if (isset($this->request->post['website'])) {
            $this->data['website'] = $this->request->post['website'];
        } elseif (!empty($rep_info)) {
            $this->data['website'] = $rep_info['website'];
        } else {
            $this->data['website'] = '';
        }

        if (isset($this->request->post['notes'])) {
            $this->data['notes'] = $this->request->post['notes'];
        } elseif (!empty($rep_info)) {
            $this->data['notes'] = $rep_info['notes'];
        } else {
            $this->data['notes'] = '';
        }

        if (isset($this->request->post['territory'])) {
            $this->data['territorys'] = $this->request->post['territory'];
        } elseif (isset($this->request->get['rep_id'])) {
            $this->data['territories'] = $this->model_sale_rep->getTerritories($this->request->get['rep_id']);
        } else {
            $this->data['territories'] = array();
        }

        $this->data['countries'] = array('223' => 'United States', '38' => 'Canada');

        // Generate a list of all states in the US and add Canada as a whole
        $this->load->model('localisation/zone');
        $zones = $this->model_localisation_zone->getZonesByCountryId(223);
        $zones[] = array('zone_id' => '38', 'name' => 'Canada');
        $this->data['zones'] = $zones;

        $this->template = 'sale/rep_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'sale/rep')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['short_title']) < 2) || (utf8_strlen($this->request->post['short_title']) > 64)) {
            $this->error['short_title'] = $this->language->get('error_short_title');
        }

        if ((utf8_strlen($this->request->post['long_title']) < 2) || (utf8_strlen($this->request->post['long_title']) > 255)) {
            $this->error['long_title'] = $this->language->get('error_long_title');
        }

        if ((utf8_strlen($this->request->post['telephone_1']) < 3) || (utf8_strlen($this->request->post['telephone_1']) > 32)) {
            $this->error['telephone_1'] = $this->language->get('error_telephone_1');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'sale/rep')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function zone()
    {
        $output = '<option value="">'.$this->language->get('text_select').'</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country']);

        foreach ($results as $result) {
            $output .= '<option value="'.$result['zone_id'].'"';

            if (isset($this->request->get['state_code']) && ($this->request->get['state_code'] == $result['zone_id'])) {
                $output .= ' selected="selected"';
            }

            $output .= '>'.$result['name'].'</option>';
        }

        if (!$results) {
            $output .= '<option value="0">'.$this->language->get('text_none').'</option>';
        }

        $this->response->setOutput($output);
    }
}
