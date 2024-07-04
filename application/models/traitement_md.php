<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class traitement_md extends CI_Model {

    public function get_monthly_revenue($start_date, $end_date) {
        $query = $this->db->query("
            SELECT
                date_trunc('month', datefin)::date AS month,
                COUNT(DISTINCT idlocation) AS total_locations,
                SUM(CASE 
                    WHEN rang=1 then loyer*2
                    ELSE loyer
                END ) AS total_loyer,
                SUM(CASE 
                    WHEN rang = 1 THEN loyer 
                    ELSE loyer * commission / 100 
                END) AS total_gain
            FROM location_details
            WHERE datedebut >= ?::date AND datefin <= ?::date
            GROUP BY date_trunc('month', datefin)::date
            ORDER BY month;
        ", array($start_date, $end_date));
        
        return $query->result_array();
    }
    
    

    public function getBiens($nom){
        $this->db->where('login', $nom);
        $query=$this->db->get('v_biens');
        return $query->result();
    }
    
    public function getAllBiensDispo() {
        $this->db->select('b.*,ty.type, bi.url,(max(ld.datefin)+31) as datefin');
        $this->db->from('location_details ld');
        $this->db->join('biens b', 'b.id = ld.idbien', 'left');
        $this->db->join('biens_img bi', 'b.id = bi.idbien', 'left');
        $this->db->join('typebien ty', 'ty.commission = ld.commission', 'left');
        $this->db->group_by('b.id, ty.type, bi.id');
        $this->db->order_by('b.id');
        $query = $this->db->get();
        return $query->result();
    }
    public function getAllBiens(){
        $this->db->select('id,nom');
        $this->db->from('biens b');
        // $this->db->join('biens_img bi', 'b.id = bi.idbien', 'left');
        // $this->db->join('typebien ty', 'ty.id = b.idtype', 'left');
        // $this->db->group_by('b.id, ty.type, bi.id');
        // $this->db->order_by('b.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getBiensById($nom){
        $this->db->select('b.*,ty.type, bi.url,p.login,(max(ld.datefin)+31) as datefin');
        $this->db->from('location_details ld');
        $this->db->join('biens b', 'b.id = ld.idbien', 'left');
        $this->db->join('biens_img bi', 'b.id = bi.idbien', 'left');
        $this->db->join('typebien ty', 'ty.commission = ld.commission', 'left');
        $this->db->join('profils p', 'p.id = b.idproprio', 'left');
        $this->db->group_by('b.id, ty.type, bi.id,p.login');
        $this->db->where('login',$nom);
        $this->db->order_by('b.id');
        $query = $this->db->get();
        return $query->result();
        
    }

    public function getBiensByClient($nom){
        $this->db->where('login', $nom);
        $query = $this->db->get('v_location_client');
        return $query->result();
    }

    public function getLocations(){
        $query= $this->db->get('v_location_client');
        return $query->result();
    }

    public function getClient(){
        $rol = 'client';
        $this->db->where('role',$rol);
        $query = $this->db->get('profils');
        return $query->result();

    }

    public function get_revenue_by_dates($owner_id, $start_date, $end_date) {
        $query = $this->db->query("
            SELECT
                date_trunc('month', datefin)::date AS month,
                COUNT(DISTINCT idlocation) AS total_locations,
                SUM(lo.loyer) AS total_loyer,
                SUM(CASE 
                    WHEN rang = 1 THEN lo.loyer 
                    ELSE (lo.loyer - (lo.loyer * commission / 100 ))
                END) AS argent_encaisse
            FROM location_details lo
            JOIN biens b on b.id = lo.idbien
            WHERE b.idproprio= ? AND datedebut >= ?::date AND datefin <= ?::date
            GROUP BY date_trunc('month', datefin)::date
            ORDER BY month;
        ", array($owner_id,$start_date, $end_date));
        
        return $query->result_array();
    }
    
    public function get_client_rent_status($client_id, $start_date, $end_date) {
        $query = $this->db->query("
            WITH date_range AS (
                SELECT generate_series(
                    date_trunc('month', ?::date),
                    date_trunc('month', ?::date),
                    '1 month'::interval
                )::date AS month
            ),
            monthly_data AS (
                SELECT
                    date_trunc('month', lo.datedebut)::date AS month,
                    COUNT(DISTINCT lo.idlocation) AS total_locations,
                    lo.rang as rang,
                    lo.loyer as loyer
                FROM location_details lo
                JOIN location l ON l.id = lo.idlocation
                WHERE l.idclient = ? 
                  AND lo.datedebut <= ?::date
                  AND lo.datefin >= ?::date
                GROUP BY date_trunc('month', lo.datedebut)::date, lo.rang, lo.loyer
            )
            SELECT 
                dr.month,
                COALESCE(md.total_locations, 0) AS total_locations,
                COALESCE(CASE 
                    WHEN md.rang = 1 THEN md.loyer * 2
                    ELSE md.loyer 
                END, 0) AS total_loyer,
                CASE 
                    WHEN dr.month < date_trunc('month', current_date)::date AND md.rang >0 THEN 'Payé'
                    ELSE 'Non payé'
                END AS status
            FROM date_range dr
            LEFT JOIN monthly_data md ON dr.month = md.month
            ORDER BY dr.month;
        ", array($start_date, $end_date, $client_id, $end_date, $start_date));
        
        return $query->result_array();
    }
    

public function getMonthlyRentalStats() {
    $allMonths = array(
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    );
    
    $results = array_fill_keys($allMonths, ['count' => 0, 'gain' => 0]);

    $currentYear = date('Y');
    $startDate = $currentYear . '-01-01';
    $endDate = ($currentYear + 1) . '-01-01';

    $query = $this->db->query("
        WITH RECURSIVE date_range AS (
            SELECT ?::date AS date
            UNION ALL
            SELECT (date + interval '1 month')::date
            FROM date_range
            WHERE date < ?::date
        ),
        expanded_locations AS (
            SELECT 
                dr.date,
                lo.id AS location_id,
                b.loyer,
                ty.commission,
                CASE
                    WHEN date_trunc('month', dr.date) = date_trunc('month', lo.datedebut) THEN b.loyer
                    ELSE (b.loyer * ty.commission / 100)
                END AS revenue
            FROM date_range dr
            CROSS JOIN location lo
            JOIN biens b ON lo.idbien = b.id
            JOIN typebien ty ON b.idtype = ty.id
            WHERE 
                dr.date >= date_trunc('month', lo.datedebut)::date
                AND dr.date <= date_trunc('month', lo.datedebut + (INTERVAL '1 month' * (lo.duree - 1)))::date + INTERVAL '1 month' - INTERVAL '1 day'
        )
        SELECT
            TO_CHAR(date_trunc('month', date)::date, 'Mon') AS month,
            COUNT(DISTINCT location_id) AS total_locations,
            SUM(revenue) AS monthly_revenue
        FROM expanded_locations
        GROUP BY date_trunc('month', date)::date, TO_CHAR(date_trunc('month', date)::date, 'Mon')
        ORDER BY date_trunc('month', date)::date
    ", array($startDate, $endDate));

    foreach ($query->result_array() as $row) {
        $results[$row['month']] = [
            'count' => (int)$row['total_locations'],
            'gain' => (float)$row['monthly_revenue']
        ];
    }
    
    $labels = array_keys($results);
    $counts = array_column($results, 'count');
    $gains = array_column($results, 'gain');
    
    return array('labels' => $labels, 'counts' => $counts, 'gains' => $gains);
}

public function insertLocation($idclient, $idbien, $duree, $datedebut) {
    // Vérifier si la période est disponible
    if (!$this->checkAvailability($idbien, $datedebut, $duree)) {
        log_message('info', 'Tentative d\'insertion d\'une location non disponible pour le bien ' . $idbien);
        return false;
    }

    $this->db->trans_start();

        // Insérer la nouvelle location
        $locationData = array(
            'idclient' => $idclient,
            'idbien' => $idbien,
            'duree' => $duree,
            'datedebut' => $datedebut
        );
        $this->db->insert('location', $locationData);
        $idlocation = $this->db->insert_id();

        // Insérer les détails de location
        // $startDate = new DateTime($datedebut);
        // for ($i = 0; $i < $duree; $i++) {
        //     $endDate = clone $startDate;
        //     $endDate->modify('last day of this month');

        //     $detailsData = array(
        //         'idbien' => $idbien,
        //         'idlocation' => $idlocation,
        //         'loyer' => $this->getBienLoyer($idbien), // Vous devez implémenter cette fonction
        //         'commission' => $this->getBienCommission($idbien), // Vous devez implémenter cette fonction
        //         'datedebut' => $startDate->format('Y-m-d'),
        //         'datefin' => $endDate->format('Y-m-d'),
        //         'rang' => $i + 1,
        //         'duree' => 1 // Un mois pour chaque entrée
        //     );
        //     $this->db->insert('location_details', $detailsData);

        //     $startDate->modify('+1 month');
        // }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Échec de l\'insertion de la location.');
            return false;
        }
    
        return true;
    }

    private function checkAvailability($idbien, $datedebut, $duree) {
        $datefin = date('Y-m-d', strtotime($datedebut . ' + ' . $duree . ' months - 1 day'));
    
        $query = $this->db->query("
            SELECT COUNT(*) AS count 
            FROM location l
            JOIN location_details ld ON l.id = ld.idlocation
            WHERE ld.idbien = ?
            AND (
                (ld.datedebut <= ? AND ld.datefin >= ?)
                OR (ld.datedebut <= ? AND ld.datefin >= ?)
                OR (? <= ld.datedebut AND ? >= ld.datedebut)
            )
        ", array($idbien, $datedebut, $datedebut, $datefin, $datefin, $datedebut, $datefin));
    
        $result = $query->row_array();
        
        if ($result['count'] > 0) {
            log_message('debug', 'Chevauchement détecté pour le bien ' . $idbien . ' du ' . $datedebut . ' au ' . $datefin);
            return false;
        }
        return true;
    }

// Vous devez implémenter ces fonctions
private function getBienLoyer($idbien) {
    $this->db->select('b.loyer');
        $this->db->from('location lo');
        $this->db->join('biens b', 'b.id = lo.idbien', 'left');
        $query = $this->db->get();
        return $query->row()->loyer;
}

private function getBienCommission($idbien) {
    $this->db->select('tb.commission');
    $this->db->from('typebien tb');
        $this->db->join('biens b', 'b.idtype = tb.id', 'left');
        $query = $this->db->get();
        return $query->row()->commission;
}



public function populate_location_details()
{
    // Clear the table before populating it
    $this->db->query("TRUNCATE TABLE location_details RESTART IDENTITY");

    // Fetch all location data
    $locations = $this->db->query("
        SELECT lo.id AS location_id, lo.idbien, lo.datedebut, lo.duree, b.loyer, ty.commission
        FROM location lo
        JOIN biens b ON lo.idbien = b.id
        JOIN typebien ty ON b.idtype = ty.id
        ORDER BY lo.datedebut
    ")->result_array();

    // Prepare data for insertion
    $dataToInsert = [];

    foreach ($locations as $location) {
        $locationStart = new DateTime($location['datedebut']);
        $locationEnd = clone $locationStart;
        $locationEnd->modify('+' . ($location['duree'] - 1) . ' months')->modify('last day of this month');

        // Calculate number of months in the rental period
        $numberOfMonths = $locationStart->diff($locationEnd)->m + 1;

        // Initialize counter for the rang index
        $rangCounter = 1;

        // Generate rows for each month in the rental period
        $currentMonth = clone $locationStart;
        while ($currentMonth <= $locationEnd) {
            $revenue = ($currentMonth == $locationStart) ? $location['loyer'] : ($location['loyer'] * $location['commission'] / 100);

            $dataToInsert[] = [
                'idbien' => $location['idbien'],
                'idlocation' => $location['location_id'],
                'loyer' => $location['loyer'],
                'commission' => $location['commission'],
                'datedebut' => $locationStart->format('Y-m-d'),
                'datefin' => $currentMonth->format('Y-m-d'),
                'rang' => $rangCounter,
                'duree' => $location['duree']
            ];

            // Increment the rang counter and reset to 1 if it reaches the duration
            $rangCounter++;
            if ($rangCounter > $location['duree']) {
                $rangCounter = 1;
            }
            $locationStart = clone $currentMonth;
            $currentMonth->modify('+1 month');
        }
    }

    // Insert data into location_details table
    if (!empty($dataToInsert)) {
        $this->db->insert_batch('location_details', $dataToInsert);
    }


}

    public function getAllLocations(){
        $this->db->select('id,nom,duree');
        $this->db->from('v_final');
        $this->db->group_by('id,nom,duree');
        $this->db->order_by('id');

        $query = $this->db->get();
        return $query->result();
    }

    public function getDetailsLocations($id,$duree){
        $query = $this->db->query("
            SELECT * from v_final where id=? AND duree= ? order by rang_mois;
        ", array($id, $duree));
        
        return $query->result();
    }
}