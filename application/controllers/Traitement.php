<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traitement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('traitement_md');
        $this->load->library('session');
        $this->load->helper('url');
        date_default_timezone_set('UTC');
    }


    public function chiffre_admin() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if (!$start_date || !$end_date) {
            $start_date = date('Y-01-01'); // Default to the first day of the current month
            $end_date = date('Y-12-31'); // Default to the last day of the current month
        }

        $revenues = $this->traitement_md->get_monthly_revenue($start_date, $end_date);
        $total_loyer_sum = 0;
        $total_gain_sum=0;
            foreach ($revenues as $revenue) {
                $total_loyer_sum += $revenue['total_loyer'];
                $total_gain_sum += $revenue['total_gain'];
            }

    $data = [
        'revenues' => $revenues,
        'total_loyer_sum' => $total_loyer_sum,
        'total_gain_sum' => $total_gain_sum
    ];
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $this->load->view('chiffre_admin', $data);
    }

    public function listeBiens(){
        $data['biens'] = $this->traitement_md->getAllBiensDispo();
        $this->load->view('listeBiens',$data);
    }

    public function listeBiensProprio(){
		$login = $this->session->userdata('login');
        $data['biens'] = $this->traitement_md->getBiensById($login);
        $this->load->view('listeBiens',$data);
    }

    public function listeBiensClient(){
		$login = $this->session->userdata('login');
        $data['locs'] = $this->traitement_md->getBiensByClient($login);
        $this->load->view('listeBiensClient',$data);
        
    }

    public function chiffre_proprio(){
        $this->load->view('chiffre_proprio');
    }
    
    public function get_revenue_by_dates() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $owner_id = $this->session->userdata('id'); // Assuming the owner_id is stored in session
        
        if (!$start_date || !$end_date) {
            $start_date = date('Y-01-01'); // Default to the first day of the current month
            $end_date = date('Y-12-31'); // Default to the last day of the current month
        }

        $revenues = $this->traitement_md->get_revenue_by_dates($owner_id, $start_date, $end_date);
        
        if (empty($revenues)) {
            $data['message'] = "Aucune location trouvée pour cette période.";
        } else {
            $total_loyer_sum = 0;
                $argent_encaisse=0;
                    foreach ($revenues as $revenue) {
                        $total_loyer_sum += $revenue['total_loyer'];
                        $argent_encaisse += $revenue['argent_encaisse'];
                    }

            $data = [
                'revenues' => $revenues,
                'total_loyer_sum' => $total_loyer_sum,
                'argent_encaisse' => $argent_encaisse
            ];
        }
        
        $this->load->view('chiffre_proprio_date', $data);
    }

   
    public function loyer() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $client_id = $this->session->userdata('id'); // Assurez-vous que l'ID du client est transmis
    
        if (!$start_date || !$end_date) {
            $start_date = date('Y-01-01'); // Default to the first day of the current year
            $end_date = date('Y-12-31'); // Default to the last day of the current year
        }
    
        $revenues = $this->traitement_md->get_client_rent_status($client_id, $start_date, $end_date);
        $total_loyer_sum = 0;
                foreach ($revenues as $revenue) {
                    $total_loyer_sum += $revenue['total_loyer'];
                }

        $data = [
            'revenues' => $revenues,
            'total_loyer_sum' => $total_loyer_sum,
        ];
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $this->load->view('loyer', $data);
    }

    public function getRentalStats() {
        $stats = $this->traitement_md->getMonthlyRentalStats();
        echo json_encode($stats);
    }

    public function importations(){
        $this->load->view('import');
    }

    public function location(){
        $data['locations'] = $this->traitement_md->getClient();
        $data['biens'] = $this->traitement_md->getAllBiens();
        $this->load->view('location',$data);
    }

    public function insertLocation() {
        $idclient = $this->input->post('client');
        $idbien = $this->input->post('nom');
        $duree = $this->input->post('duree');
        $datedebut = $this->input->post('datedebut');
    
        $result = $this->traitement_md->insertLocation($idclient, $idbien, $duree, $datedebut);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Location insérée']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur date déjà réservée']);
        }
    }
    

    public function populate_details() {
        // Appeler la fonction du modèle pour peupler la table location_details
        $this->traitement_md->populate_location_details();
        echo "La table location_details a été peuplée avec succès.";
    }

    public function getAllLocations(){
        $data['locations'] = $this->traitement_md->getAllLocations();
        $this->load->view('listeLocation',$data);
    }

    public function locationDetails(){
        $id = $this->input->post('id');
        $duree = $this->input->post('duree');

        $data['details'] = $this->traitement_md->getDetailsLocations($id,$duree);
        $data['test'] = $this->traitement_md->getTest();
        
        $this->load->view('details',$data);
    }

    // public function Test(){
    //     $data['test'] = $this->traitement_md->getTest();
    //     $this-
    // }
}