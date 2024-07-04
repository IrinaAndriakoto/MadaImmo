import pandas as pd
from sqlalchemy import create_engine, text

import logging

# Configurer la journalisation
logging.basicConfig(filename='etl_script.log', level=logging.INFO, 
                    format='%(asctime)s %(levelname)s:%(message)s')

# Connexions aux bases de données
source_engine = create_engine('postgresql://postgres:irina@localhost:5432/madaimmo')
dw_engine = create_engine('postgresql://postgres:irina@localhost:5432/test_datawarehouse')

# Extraction des données de la source

def drop_tables():
    logging.info("Suppression des tables existantes dans le data warehouse")
    with dw_engine.connect() as connection:
        # connection.execute(text("DROP TABLE IF EXISTS dw_paiement CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_location_details CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_location CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_biens_img CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_biens CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_typebien CASCADE"))
        connection.execute(text("DROP TABLE IF EXISTS dw_profils CASCADE"))
    logging.info("Tables supprimées")

def extract_data():
    profils_df = pd.read_sql('SELECT * FROM profils', source_engine)
    typebien_df = pd.read_sql('SELECT * FROM typebien', source_engine)
    biens_df = pd.read_sql('SELECT * FROM biens', source_engine)
    biens_img_df = pd.read_sql('SELECT * FROM biens_img', source_engine)
    location_df = pd.read_sql('SELECT * FROM location', source_engine)
    location_details_df = pd.read_sql('SELECT * FROM location_details', source_engine)
    # paiement_df = pd.read_sql('SELECT * FROM paiement', source_engine)
    logging.info("Extraction des données terminée")
    
    return profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df

# Transformation des données
def transform_data(profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df):
    # Ajoutez des transformations spécifiques si nécessaire
    logging.info("Début de la transformation des données")
    # Ajouter ici les transformations nécessaires
    logging.info("Transformation des données terminée")
    
    return profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df

# Chargement des données dans le Data Warehouse
def load_data(profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df):
    logging.info("Début du chargement des données")
    profils_df.to_sql('dw_profils', dw_engine, if_exists='replace', index=False)
    typebien_df.to_sql('dw_typebien', dw_engine, if_exists='replace', index=False)
    biens_df.to_sql('dw_biens', dw_engine, if_exists='replace', index=False)
    biens_img_df.to_sql('dw_biens_img', dw_engine, if_exists='replace', index=False)
    location_df.to_sql('dw_location', dw_engine, if_exists='replace', index=False)
    location_details_df.to_sql('dw_location_details', dw_engine, if_exists='replace', index=False)
    logging.info("Chargement des données terminé")
    # paiement_df.to_sql('dw_paiement', dw_engine, if_exists='replace', index=False)

# Processus ETL
def etl_process():
    try:
        logging.info("Début du processus ETL")
        
        # Extraction
        profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df = extract_data()
        logging.info("Extraction terminée.")
        
        # Transformation
        profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df = transform_data(
            profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df)
        logging.info("Transformation terminée.")
        
        # Chargement
        load_data(profils_df, typebien_df, biens_df, biens_img_df, location_df, location_details_df)
        logging.info("Chargement terminé.")
        
        logging.info("Processus ETL terminé avec succès")
    except Exception as e:
        logging.error("Erreur dans le processus ETL: %s", str(e))

if __name__ == "__main__":
    etl_process()
