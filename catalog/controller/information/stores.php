<?php
class ControllerInformationStores extends Controller
{
    public function index()
    {
        $this->language->load('information/stores');

        $this->document->setTitle($this->language->get('heading_title'));

        // Breadcrumbs
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
        'text'      => $this->language->get('text_home'),
        'href'      => $this->url->link('common/home'),
        'separator' => false,
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('information/stores'),
            'separator' => $this->language->get('text_separator'),
        );

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['entry_address_input'] = $this->language->get('entry_address_input');
        $this->data['entry_radius_select'] = $this->language->get('entry_radius_select');
        $this->data['text_submit_button'] = $this->language->get('text_submit_button');

        // Load view
        if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/information/stores.tpl')) {
            $this->template = $this->config->get('config_template').'/template/information/stores.tpl';
        } else {
            $this->template = 'default/template/information/stores.tpl';
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

    /*
     * This public function is called from the template file via ajax.
     */
    public function markerxml()
    {
        $data = array();

        $data['center_lat'] = $this->request->get['lat'];
        $data['center_lng'] = $this->request->get['lng'];
        $data['radius'] = $this->request->get['radius'];

        // Search the rows in the markers table
        $this->load->model('information/stores');
        $results = $this->model_information_stores->searchMarkers($data);

        // Start XML file, create parent node
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // Iterate through the rows, adding XML nodes for each
        foreach ($results as $result) {
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name", $result['name']);
            $newnode->setAttribute("address", $result['address']);
            $newnode->setAttribute("phone", $result['phone']);
            $newnode->setAttribute("lat", $result['lat']);
            $newnode->setAttribute("lng", $result['lng']);
            $newnode->setAttribute("distance", $result['distance']);
        }

        $markers = $dom->saveXML();

        $this->response->addHeader('Content-type: text/xml');
        $this->response->setOutput($markers);
    }
}
