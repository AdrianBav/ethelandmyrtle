<?php
class ModelInformationReps extends Model
{
    public function getAllReps()
    {
        $query = $this->db->query("
			SELECT r.id, r.short_title, r.long_title
			FROM ".DB_PREFIX."rep r
			ORDER BY r.id ASC
		");

        return $query->rows;
    }

    public function getReps($state)
    {
        $rep_data = array();

        if ($state == 'CAN') {
            /* Look-up all reps that cover any teritory in Canada. */
            $query = $this->db->query("
				SELECT
					r.id AS id, r.short_title AS short_title, r.long_title AS long_title,
					r.address_1 AS address1, r.address_2 AS address2, r.city AS city, r.zip AS zip, r.zone_id AS zone_id,
					r.telephone_1 AS telephone1, r.telephone_2 AS telephone2, r.fax AS fax, r.email AS email, r.website AS website, r.notes AS notes
				FROM ".DB_PREFIX."rep_territory rt
				INNER JOIN ".DB_PREFIX."rep r ON (rt.rep_id = r.id)
				WHERE
					rt.zone_id = 38
				ORDER BY
					rt.sort_order ASC
			");
        } else {
            $query = $this->db->query("
				SELECT
					r.id AS id, r.short_title AS short_title, r.long_title AS long_title,
					r.address_1 AS address1, r.address_2 AS address2, r.city AS city, r.zip AS zip, r.zone_id AS zone_id,
					r.telephone_1 AS telephone1, r.telephone_2 AS telephone2, r.fax AS fax, r.email AS email, r.website AS website, r.notes AS notes
				FROM ".DB_PREFIX."rep_territory rt
				INNER JOIN ".DB_PREFIX."rep r ON (rt.rep_id = r.id)
				INNER JOIN ".DB_PREFIX."zone z ON (rt.zone_id = z.zone_id)
				WHERE
					z.country_id = 223 AND
					z.code = '".$state."'
				ORDER BY
					rt.sort_order ASC
			");
        }

        foreach ($query->rows as $result) {
            $rep_id = $result['id'];

            $zone = array();

            if ($result['zone_id']) {
                $this->load->model('localisation/zone');
                $zone = $this->model_localisation_zone->getZone($result['zone_id']);
            }

            $rep_data[$rep_id] = array(
                'short_title' => $result['short_title'],
                'long_title' => $result['long_title'],
                'address1' => $result['address1'],
                'address2' => $result['address2'],
                'city' => $result['city'],
                'state' => ($zone) ? $zone['code'] : '',
                'zip' => $result['zip'],
                'telephone1' => $result['telephone1'],
                'telephone2' => $result['telephone2'],
                'fax' => $result['fax'],
                'email' => $result['email'],
                'website' => $result['website'],
                'notes' => $result['notes'],
            );
        }

        return $rep_data;
    }

    public function getRep($rep_id)
    {
        $query = $this->db->query("
			SELECT r.short_title AS short_title, r.long_title AS long_title
			FROM ".DB_PREFIX."rep r
			WHERE r.id = '".$rep_id."'
		");

        return $query->rows;
    }
}
