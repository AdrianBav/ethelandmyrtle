<?php
class ControllerToolImportStores extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('tool/import_stores');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('tool/import_stores');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
                $file = $this->request->files['upload']['tmp_name'];

                if ($this->model_tool_import_stores->upload($file)) {
                    $this->session->data['success'] = $this->language->get('text_success');
                    $this->redirect($this->url->link('tool/import_stores', 'token='.$this->session->data['token'], 'SSL'));
                } else {
                    $this->error['warning'] = $this->language->get('error_upload');
                }
            }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_markers_description'] = $this->language->get('entry_markers_description');
        $this->data['entry_import'] = $this->language->get('entry_import');
        $this->data['entry_markers'] = $this->language->get('entry_markers');
        $this->data['waiting_message'] = $this->language->get('waiting_message');
        $this->data['button_import'] = $this->language->get('button_import');

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

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false,
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('tool/import_stores', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        );

        $this->data['action'] = $this->url->link('tool/import_stores', 'token='.$this->session->data['token'], 'SSL');
        $this->data['markers_action'] = $this->url->link('tool/import_stores/generate_markers', 'token='.$this->session->data['token'], 'SSL');

        $this->template = 'tool/import_stores.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );
        $this->response->setOutput($this->render());
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'tool/import_stores')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Generate google map markers.
     *
     * The purpose of this function is to produce a database table of geocoded addresses from
     * a larger database table of non-geocoded addresses.
     * This function should be run everytime the large database table of addresses is uploaded from a spreadsheet.
     *
     * WARNING: This function makes around 450 API calls, and there is a limit of 2,500 requests per 24 hour period.
     *
     * (https://developers.google.com/maps/documentation/geocoding/)
     */
    public function generate_markers()
    {
        $this->load->model('tool/import_stores');

        // Get an array of stores from the database
        // Stores must match a pre-set criteria as defined in the model
        $stores = $this->model_tool_import_stores->getStores();
        $store_count = count($stores);

        if ($store_count < 1) {
            $this->session->data['success'] = 'Warning: Failed to find any stores matching criteria.';
            $this->redirect($this->url->link('tool/import_stores', 'token='.$this->session->data['token'], 'SSL'));
        }

        // Delete any existing store markers
        $this->model_tool_import_stores->deleteStoreMarkers();

        // Generate a marker for each store
        foreach ($stores as $store) {
            $address = urlencode($store['address']);
            $sensor = 'false';
            $key = 'AIzaSyArIaSX56fdGsa87kxZsApeVWXykAh_v2Q';

            // Use the google Geocoding API to get address co-ordinates.
            $parameters = "address={$address}&sensor={$sensor}&key={$key}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?{$parameters}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($response);

            // Handle errors
            if ($json->status != 'OK') {
                $this->log->write("Error: Bad geocoding response for {$store['name']}.");
                //$this->log->write(print_r($json, true));
                continue;
            }

            // Store the data in the marker table
            $data = array();

            $data['name'] = $store['name'];
            $data['address'] = $store['address'];
            $data['phone'] = $store['telephone'];
            $data['lat'] = $json->results[0]->geometry->location->lat;
            $data['lng'] = $json->results[0]->geometry->location->lng;

            $this->model_tool_import_stores->addStoreMarker($data);
        }

        // Feedback
        $this->session->data['success'] = "Successfully generated map markers for {$store_count} stores.";
        $this->redirect($this->url->link('tool/import_stores', 'token='.$this->session->data['token'], 'SSL'));
    }
}
