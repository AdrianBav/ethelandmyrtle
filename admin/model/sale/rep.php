<?php
class ModelSaleRep extends Model
{
    public function addRep($data)
    {
        $this->db->query("
      		INSERT INTO ".DB_PREFIX."rep
      		SET
      			short_title = '".$this->db->escape($data['short_title'])."',
      			long_title = '".$this->db->escape($data['long_title'])."',
      			address_1 = '".$this->db->escape($data['address_1'])."',
      			address_2 = '".$this->db->escape($data['address_2'])."',
      			city = '".$this->db->escape($data['city'])."',
      			zone_id = '".(int) $data['state']."',
      			country_id = '".(int) $data['country']."',
      			zip = '".$this->db->escape($data['zipcode'])."',
      			telephone_1 = '".$this->db->escape($data['telephone_1'])."',
      			telephone_2 = '".$this->db->escape($data['telephone_2'])."',
      			fax = '".$this->db->escape($data['fax'])."',
      			email = '".$this->db->escape($data['email'])."',
      			website = '".$this->db->escape($data['website'])."',
      			notes = '".$this->db->escape($data['notes'])."'
      	");

        $rep_id = $this->db->getLastId();

        if (isset($data['territory'])) {
            foreach ($data['territory'] as $territory) {
                $this->db->query("
					INSERT INTO ".DB_PREFIX."rep_territory
					SET
						rep_id = '".(int) $rep_id."',
						zone_id = '".$this->db->escape($territory['territory'])."',
						sort_order = '".(int) $territory['sort_order']."',
						extra = '".$this->db->escape($territory['extra'])."'
				");
            }
        }
    }

    public function editRep($rep_id, $data)
    {
        $this->db->query("
			UPDATE ".DB_PREFIX."rep
			SET
      			short_title = '".$this->db->escape($data['short_title'])."',
      			long_title = '".$this->db->escape($data['long_title'])."',
      			address_1 = '".$this->db->escape($data['address_1'])."',
      			address_2 = '".$this->db->escape($data['address_2'])."',
      			city = '".$this->db->escape($data['city'])."',
      			zone_id = '".(int) $data['state']."',
      			country_id = '".(int) $data['country']."',
      			zip = '".$this->db->escape($data['zipcode'])."',
      			telephone_1 = '".$this->db->escape($data['telephone_1'])."',
      			telephone_2 = '".$this->db->escape($data['telephone_2'])."',
      			fax = '".$this->db->escape($data['fax'])."',
      			email = '".$this->db->escape($data['email'])."',
      			website = '".$this->db->escape($data['website'])."',
      			notes = '".$this->db->escape($data['notes'])."'
			WHERE
				id = '".(int) $rep_id."'
		");

        $this->db->query("DELETE FROM ".DB_PREFIX."rep_territory WHERE rep_id = '".(int) $rep_id."'");

        if (isset($data['territory'])) {
            foreach ($data['territory'] as $territory) {
                $this->db->query("
					INSERT INTO ".DB_PREFIX."rep_territory
					SET
						rep_id = '".(int) $rep_id."',
						zone_id = '".$this->db->escape($territory['territory'])."',
						sort_order = '".(int) $territory['sort_order']."',
						extra = '".$this->db->escape($territory['extra'])."'
				");
            }
        }
    }

    public function deleteRep($rep_id)
    {
        $this->db->query("DELETE FROM ".DB_PREFIX."rep WHERE id = '".(int) $rep_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."rep_territory WHERE rep_id = '".(int) $rep_id."'");
    }

    public function getRep($rep_id)
    {
        $query = $this->db->query("
			SELECT
				id AS rep_id,
				short_title AS short_title,
				long_title AS long_title,
				address_1 AS address_1,
				address_2 AS address_2,
				city AS city,
				zone_id AS state,
				country_id AS country,
				zip AS zipcode,
				telephone_1 AS telephone_1,
				telephone_2 AS telephone_2,
				fax AS fax,
				email AS email,
				website AS website,
				notes AS notes
			FROM ".DB_PREFIX."rep
			WHERE id = '".(int) $rep_id."'
		");

        return $query->row;
    }

    public function getReps($data = array())
    {
        $sql = "SELECT *,id AS rep_id FROM ".DB_PREFIX."rep ORDER BY long_title ASC";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT ".(int) $data['start'].",".(int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalReps($data = array())
    {
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."rep";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTerritories($rep_id)
    {
        $query = $this->db->query("
			SELECT zone_id as territory, sort_order AS sort_order, extra AS extra
			FROM ".DB_PREFIX."rep_territory
			WHERE rep_id = '".(int) $rep_id."'
			ORDER BY sort_order
		");

        return $query->rows;
    }
}
