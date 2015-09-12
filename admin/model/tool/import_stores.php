<?php

static $config = null;
static $log = null;

// Error Handler
function error_handler_for_export($errno, $errstr, $errfile, $errline)
{
    global $config;
    global $log;

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $errors = "Notice";
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $errors = "Warning";
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $errors = "Fatal Error";
            break;
        default:
            $errors = "Unknown";
            break;
    }

    if (($errors == 'Warning') || ($errors == 'Unknown')) {
        return true;
    }

    if ($config->get('config_error_display')) {
        echo '<b>'.$errors.'</b>: '.$errstr.' in <b>'.$errfile.'</b> on line <b>'.$errline.'</b>';
    }

    if ($config->get('config_error_log')) {
        $log->write('PHP '.$errors.':  '.$errstr.' in '.$errfile.' on line '.$errline);
    }

    return true;
}

function fatal_error_shutdown_handler_for_export()
{
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        // fatal error
        error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

class ModelToolImportStores extends Model
{
    public function clean(&$str, $allowBlanks = false)
    {
        $result = "";
        $n = strlen($str);
        for ($m = 0; $m<$n; $m++) {
            $ch = substr($str, $m, 1);
            if (($ch == " ") && (!$allowBlanks) || ($ch == "\n") || ($ch == "\r") || ($ch == "\t") || ($ch == "\0") || ($ch == "\x0B")) {
                continue;
            }
            $result .= $ch;
        }

        return $result;
    }

    public function import(&$database, $sql)
    {
        foreach (explode(";\n", $sql) as $sql) {
            $sql = trim($sql);
            if ($sql) {
                $database->query($sql);
            }
        }
    }

    protected function detect_encoding($str)
    {
        // auto detect the character encoding of a string
        return mb_detect_encoding($str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R');
    }

    public function storeStoresIntoDatabase(&$database, &$stores)
    {
        // start transaction, remove categories
        $sql = "START TRANSACTION;\n";
        $sql .= "DELETE FROM `".DB_PREFIX."store_locator_spreadsheet`;\n";
        $this->import($database, $sql);

        // generate and execute SQL for inserting the categories
        foreach ($stores as $store) {
            $customer_id = $store['customer_id'];
            $customer_name = $store['customer_name'];
            $address_1 = $store['address_1'];
            $address_2 = $store['address_2'];
            $city = $store['city'];
            $state = $store['state'];
            $zip = $store['zip'];
            $sort_key = $store['sort_key'];
            $phone = $store['phone'];
            $fax = $store['fax'];
            $contact = $store['contact'];
            $customer_hold = $store['customer_hold'];
            $customer_status = $store['customer_status'];
            $date_acct_opened = ($store['date_acct_opened']) ? date('Y-m-d', $store['date_acct_opened']) : '';
            $date_last_ordered = ($store['date_last_ordered']) ? date('Y-m-d', $store['date_last_ordered']) : '';
            $date_last_payment = ($store['date_last_payment']) ? date('Y-m-d', $store['date_last_payment']) : '';
            $ytd_sales = $store['ytd_sales'];
            $prior_year_sales = $store['prior_year_sales'];
            $orders_in_house = $store['orders_in_house'];
            $credit_limit = $store['credit_limit'];
            $terms_code = $store['terms_code'];
            $email = $store['email'];
            $tax_id = $store['tax_id'];

            $sql2 = "INSERT INTO `".DB_PREFIX."store_locator_spreadsheet` (`customer_id`, `customer_name`, `address_1`, `address_2`, `city`, `state`, `zip`, `sort_key`, `phone`, `fax`, `contact`, `customer_hold`, `customer_status`, `date_acct_opened`, `date_last_ordered`, `date_last_payment`, `ytd_sales`, `prior_year_sales`, `orders_in_house`, `credit_limit`, `terms_code`, `email`, `tax_id`) VALUES ";
            $sql2 .= "( '{$customer_id}', '{$customer_name}', '{$address_1}', '{$address_2}', '{$city}', '{$state}', '{$zip}', '{$sort_key}', '{$phone}', '{$fax}', '{$contact}', '{$customer_hold}', '{$customer_status}', '{$date_acct_opened}', '{$date_last_ordered}', '{$date_last_payment}', '{$ytd_sales}', '{$prior_year_sales}', '{$orders_in_house}', '{$credit_limit}', '{$terms_code}', '{$email}', '{$tax_id}' );";
            $database->query($sql2);
        }

        // final commit
        $database->query("COMMIT;");

        return true;
    }

    public function uploadStores(&$reader, &$database)
    {
        $data = $reader->getSheet(0);
        $stores = array();
        $isFirstRow = true;

        $i = 0;
        $k = $data->getHighestRow();

        for ($i = 0; $i<$k; $i += 1) {
            $j = 1;

            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            /* A. Skip 'Company' */
            $j++;

            /* B. 'Customer Number' */
            $customer_id = $this->getCell($data, $i, $j++);

            if ($customer_id == "") {
                continue;
            }

            /* C. 'Customer Name' */
            $customer_name = $this->getCell($data, $i, $j++);

            /* D. 'Address 1' */
            $address_1 = $this->getCell($data, $i, $j++);

            /* E. 'Address 2' */
            $address_2 = $this->getCell($data, $i, $j++);

            /* F. 'City' */
            $city = $this->getCell($data, $i, $j++);

            /* G. 'State' */
            $state = $this->getCell($data, $i, $j++);

            /* H. 'Zip' */
            $zip = $this->getCell($data, $i, $j++);

            /* I. 'Sort Key' */
            $sort_key = $this->getCell($data, $i, $j++);

            /* J. 'Phone' */
            $phone = $this->getCell($data, $i, $j++);

            /* K. 'Fax' */
            $fax = $this->getCell($data, $i, $j++);

            /* L. 'Contact' */
            $contact = $this->getCell($data, $i, $j++);

            /* M. 'Customer Hold' */
            $customer_hold = $this->getCell($data, $i, $j++);

            /* N. 'Customer Status' */
            $customer_status = $this->getCell($data, $i, $j++);

            /* O. 'Date Acct Opened' */
            $date_acct_opened_excel = $this->getCell($data, $i, $j++);
            $date_acct_opened = PHPExcel_Shared_Date::ExcelToPHP($date_acct_opened_excel);

            /* P. 'Date Last Ordered' */
            $date_last_ordered_excel = $this->getCell($data, $i, $j++);
            $date_last_ordered = PHPExcel_Shared_Date::ExcelToPHP($date_last_ordered_excel);

            /* Q. 'Date Last Payment' */
            $date_last_payment_excel = $this->getCell($data, $i, $j++);
            $date_last_payment = PHPExcel_Shared_Date::ExcelToPHP($date_last_payment_excel);

            /* R. 'YTD Sales' */
            $ytd_sales = $this->getCell($data, $i, $j++);

            /* S. 'Prior Year Sales' */
            $prior_year_sales = $this->getCell($data, $i, $j++);

            /* T. 'Orders in House' */
            $orders_in_house = $this->getCell($data, $i, $j++);

            /* U. 'Credit Limit' */
            $credit_limit = $this->getCell($data, $i, $j++);

            /* V. 'Terms Code' */
            $terms_code = $this->getCell($data, $i, $j++);

            /* W. 'Email' */
            $email = $this->getCell($data, $i, $j++);

            /* X. 'Tax ID' */
            $tax_id = $this->getCell($data, $i, $j++);

            /* Y. Skip '' */
            $j++;

            /* Store the data in an array */
            $store = array();
            $store['customer_id'] = $customer_id;
            $store['customer_name'] = $customer_name;
            $store['address_1'] = $address_1;
            $store['address_2'] = $address_2;
            $store['city'] = $city;
            $store['state'] = $state;
            $store['zip'] = $zip;
            $store['sort_key'] = $sort_key;
            $store['phone'] = $phone;
            $store['fax'] = $fax;
            $store['contact'] = $contact;
            $store['customer_hold'] = $customer_hold;
            $store['customer_status'] = $customer_status;
            $store['date_acct_opened'] = $date_acct_opened;
            $store['date_last_ordered'] = $date_last_ordered;
            $store['date_last_payment'] = $date_last_payment;
            $store['ytd_sales'] = $ytd_sales;
            $store['prior_year_sales'] = $prior_year_sales;
            $store['orders_in_house'] = $orders_in_house;
            $store['credit_limit'] = $credit_limit;
            $store['terms_code'] = $terms_code;
            $store['email'] = $email;
            $store['tax_id'] = $tax_id;

            $stores[$customer_id] = $store;
        }

        return $this->storeStoresIntoDatabase($database, $stores);
    }

    public function getCell(&$worksheet, $row, $col, $default_val = '')
    {
        $col -= 1; // we use 1-based, PHPExcel uses 0-based column index
        $row += 1; // we use 0-based, PHPExcel used 1-based row index
        return ($worksheet->cellExistsByColumnAndRow($col, $row)) ? trim($worksheet->getCellByColumnAndRow($col, $row)->getValue()) : $default_val;
    }

    public function validateHeading(&$data, &$expected)
    {
        $heading = array();
        $k = PHPExcel_Cell::columnIndexFromString($data->getHighestColumn());
        if ($k != count($expected)) {
            return false;
        }
        $i = 0;
        for ($j = 1; $j <= $k; $j += 1) {
            $heading[] = $this->getCell($data, $i, $j);
        }
        $valid = true;
        for ($i = 0; $i < count($expected); $i += 1) {
            if (!isset($heading[$i])) {
                $valid = false;
                break;
            }
            if (trim(strtolower($heading[$i])) != trim(strtolower($expected[$i]))) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    public function validateSheet(&$reader)
    {
        $expectedSheetHeading = array( "Company", "Customer Number", "Customer Name", "Address 1", "Address 2", "City", "State", "Zip", "Sort Key", "Phone", "Fax", "Contact", "Customer Hold", "Customer Status", "Date Acct Opened", "Date Last Ordered", "Date Last Payment", "YTD Sales", "Prior Year Sales", "Orders in House", "Credit Limit", "Terms Code", "Email", "Tax ID", "" );
        $data = & $reader->getSheet(0);

        return $this->validateHeading($data, $expectedSheetHeading);
    }

    public function validateUpload(&$reader)
    {
        if ($reader->getSheetCount() != 1) {
            error_log(date('Y-m-d H:i:s - ', time()).$this->language->get('error_sheet_count')."\n", 3, DIR_LOGS."error.txt");

            return false;
        }
        if (!$this->validateSheet($reader)) {
            error_log(date('Y-m-d H:i:s - ', time()).$this->language->get('error_sheet_header')."\n", 3, DIR_LOGS."error.txt");

            return false;
        }

        return true;
    }

    public function clearCache()
    {
        $this->cache->delete('store_locator_spreadsheet');
    }

    public function upload($filename)
    {
        global $config;
        global $log;
        $config = $this->config;
        $log = $this->log;

        set_error_handler('error_handler_for_export', E_ALL);
        register_shutdown_function('fatal_error_shutdown_handler_for_export');
        $database = & $this->db;
        $options_buffer = array();
        ini_set("memory_limit", "512M");
        ini_set("max_execution_time", 180);
        //set_time_limit( 60 );
        //chdir( '../system/PHPExcel' );

        chdir(DIR_SYSTEM.'PHPExcel/');
        require_once 'Classes/PHPExcel.php';
        //chdir( '../../admin' );
        chdir(DIR_APPLICATION);

        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $reader = $objReader->load($filename);

        /* Validate Spreadsheet */
        $ok = $this->validateUpload($reader);
        if (!$ok) {
            return false;
        }

        $this->clearCache();

        /* Import Store Data */
        $ok = $this->uploadStores($reader, $database);
        if (!$ok) {
            return false;
        }

        chdir('../../..');

        return $ok;
    }

    public function getStores()
    {
        $query = $this->db->query("
            SELECT
                CONCAT (
                    CASE WHEN address_1 = '' THEN '' ELSE CONCAT(address_1, ' ') END,
                    CASE WHEN address_2 = '' THEN '' ELSE CONCAT(address_2, ' ') END,
                    city,
                    ' ',
                    state,
                    ' ',
                    zip
                ) AS address,
                customer_name as name,
                phone as telephone
            FROM
                store_locator_spreadsheet
            WHERE
                ytd_sales + prior_year_sales >= 1000.00
        ");

        return $query->rows;
    }

    public function deleteStoreMarkers()
    {
        $this->db->query("DELETE FROM `".DB_PREFIX."store_locator_markers`");

        return true;
    }

    public function addStoreMarker($data)
    {
        $this->db->query("
            INSERT INTO ".DB_PREFIX."store_locator_markers
            SET name = '".$data['name']."', address = '".$data['address']."', phone = '".$data['phone']."', lat = '".(float) $data['lat']."', lng = '".(float) $data['lng']."'
        ");
    }
}
