<?php
class ModelInformationStores extends Model
{
    public function searchMarkers($data)
    {
        $query = $this->db->query("
            SELECT address, name, phone, lat, lng, ( 3959 * acos( cos( radians('{$data['center_lat']}') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('{$data['center_lng']}') ) + sin( radians('{$data['center_lat']}') ) * sin( radians( lat ) ) ) ) AS distance
            FROM store_locator_markers
            HAVING distance < '{$data['radius']}'
            ORDER BY distance
            LIMIT 0, 20
        ");

        return $query->rows;
    }
}
