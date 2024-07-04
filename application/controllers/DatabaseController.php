<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatabaseController extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
}

public function reset_database() {
    // Liste de toutes les tables et vues
    $tables_and_views = $this->db->list_tables();

    // Supprimer tous les profils sauf les administrateurs
    // $this->db->where('role !=', 'admin');
    // $this->db->delete('profils');
    
    foreach ($tables_and_views as $name) {
        // Vérifiez si l'objet est une table
        $is_table_query = $this->db->query("SELECT relkind FROM pg_class WHERE relname = ? AND relkind = 'r'", array($name));
        $is_table_result = $is_table_query->row();
        
        if ($is_table_result && $is_table_result->relkind == 'r') {
            // Exécute TRUNCATE
            $this->db->query("TRUNCATE TABLE $name CASCADE");
            
            // Récupère le nom de la séquence pour la colonne 'id'
            $seq_query = $this->db->query("SELECT pg_get_serial_sequence(?, 'id') as seq_name", array($name));
            $seq_result = $seq_query->row();
            
            if ($seq_result && $seq_result->seq_name) {
                // Réinitialise la séquence
                $this->db->query("ALTER SEQUENCE {$seq_result->seq_name} RESTART WITH 1");
            }
        }
    }
    
    redirect('Welcome/admin');
}


}
