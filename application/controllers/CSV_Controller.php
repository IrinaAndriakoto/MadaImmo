 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CSV_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('csvimport'); 
        $this->load->helper('url');
        date_default_timezone_set('UTC');
        
    }

    public function process_biens() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv'; // Changer pour 'csv' pour plus de sécurité

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('csv_file_biens')) {
            $error = $this->upload->display_errors();
            echo "Erreur lors du téléchargement du fichier CSV : $error";
        } else {
            $data = $this->upload->data();
            $file_path = $data['full_path'];

            $import_result = $this->csvimport->import_data_biens($file_path); 

            if (!$import_result) {
                echo "Erreur lors de l'importation des données CSV.";
            } else {
                echo "Importation reussie.";
                redirect('Welcome/admin'); 
            }
        }
    }

    public function process_locations() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv'; // Changer pour 'csv' pour plus de sécurité

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('csv_file_locs')) {
            $error = $this->upload->display_errors();
            echo "Erreur lors du téléchargement du fichier CSV : $error";
        } else {
            $data = $this->upload->data();
            $file_path = $data['full_path'];

            $import_result = $this->csvimport->import_data_locs($file_path); 

            if (!$import_result) {
                echo "Erreur lors de l'importation des données CSV.";
            } else {
                echo
                redirect('Welcome/admin'); 
            }
        }
    }
    
    
    
    public function process_typebiens() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('csv_file_typebien')) {
            $error = $this->upload->display_errors();
            echo "Erreur lors du téléchargement du fichier CSV : $error";
        } else {
            $data = $this->upload->data();
            $file_path = $data['full_path'];

            $import_result = $this->csvimport->import_data_typebiens($file_path); 

            if (!$import_result) {
                echo "Erreur lors de l'importation des données CSV.";
            } else {
                echo
                redirect('Welcome/admin'); 
            }
        }
    }

    // public function devis_csv() {
    //     $config['upload_path'] = './uploads/';
    //     $config['allowed_types'] = 'csv';
    //     $this->load->library('upload', $config);

    //     if (!$this->upload->do_upload('csv_file_devis')) {
    //         $error = $this->upload->display_errors();
    //         echo "Erreur lors du téléchargement du fichier CSV : $error";
    //     } else {
    //         $data = $this->upload->data();
    //         $file_path = $data['full_path'];

    //         $import_result = $this->csvimport->import_data_devis($file_path); 

    //         if (!$import_result) {
    //             echo "Erreur lors de l'importation des données CSV.";
    //         } else {
    //             echo
    //             redirect('Admin/dashboard');
    //         }
    //     }
    // }

    // public function import_data_etape($file_path) {
    //     if (!file_exists($file_path)) {
    //         return false;
    //     }

    //     $file_content = file_get_contents($file_path);

    //     if (empty($file_content)) {
    //         return false;
    //     }

    //     $csv_lines = explode(PHP_EOL, $file_content);

    //     for ($i = 1; $i < count($csv_lines); $i++) {
    //         $line = $csv_lines[$i];

    //         if (!empty($line)) {
    //             $csv_values = str_getcsv($line);

    //             $devis_data = array(
    //                 'id' => $i,
    //                 'nom' => $csv_values[0],
    //                 'longueurkm' => $csv_values[1],
    //                 'nbcoureurparequipe' => $csv_values[2],
    //                 'rangetape' => $csv_values[3],
    //                 'datedepart' => $csv_values[4],
    //                 'heuredepart' => $csv_values[5]
    //             );

    //             $this->db->insert('etapes', $devis_data);
    //         }
    //     }
    //     return true;
    // }

    // public function import_data_result($file_path) {
    //     if (!file_exists($file_path)) {
    //         return false;
    //     }

    //     $file_content = file_get_contents($file_path);

    //     if (empty($file_content)) {
    //         return false;
    //     }

    //     $csv_lines = explode(PHP_EOL, $file_content);

    //     for ($i = 1; $i < count($csv_lines); $i++) {
    //         $line = $csv_lines[$i];

    //         if (!empty($line)) {
    //             $csv_values = str_getcsv($line);

    //             $detail_travaux_data = array(
    //                 'rangetape' => $csv_values[0],
    //                 'numero' => $csv_values[1],
    //                 'coureur' => $csv_values[2],
    //                 'genre' => $csv_values[3],
    //                 'datedenaissance' => $csv_values[4],
    //                 'equipe' => $csv_values[5],
    //                 'arrivee' => $csv_values[6]
    //             );

    //             $this->db->insert('resultats', $detail_travaux_data);
    //         }
    //     }
    //     return true;
    // }

//     public function paiement_csv() {
//     $config['upload_path'] = './uploads/';
//     $config['allowed_types'] = 'csv';
//     $this->load->library('upload', $config);

//     if (!$this->upload->do_upload('csv_file_paiement')) {
//         $error = $this->upload->display_errors();
//         echo "Erreur lors du téléchargement du fichier CSV : $error";
//     } else {
//         $data = $this->upload->data();
//         $file_path_paiement = $data['full_path'];

//         $import_result_paiement = $this->csvimport->import_paiement($file_path_paiement);

//         if (!$import_result_paiement) {
//             echo "Erreur lors de l'importation des données CSV pour les paiements.";
//         } else {
//             echo "Importation des données CSV pour les paiements réussie.";
//             redirect('Admin/dashboard');
//         }
//     }
// }

}


