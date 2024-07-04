<h2 style="text-decoration: underline;">Importation de données</h2>
        <form action="<?php echo site_url('CSV_Controller/process_biens') ?>" method="post" enctype="multipart/form-data">
            <h5>Biens </h5>
            <input type="file" name="csv_file_biens" accept=".csv">
            <button type="submit">Importer</button>

        </form>

        <form action="<?php echo site_url('CSV_Controller/process_locations') ?>" method="post" enctype="multipart/form-data">
            <h5>Locations </h5>
            <input type="file" name="csv_file_locs" accept=".csv">
            <button type="submit">Importer</button>

        </form>

        <form action="<?php echo site_url('CSV_Controller/process_typebiens') ?>" method="post" enctype="multipart/form-data">
            <h5>Type de bien </h5>
            <input type="file" name="csv_file_typebien" accept=".csv">
            <button type="submit">Importer</button>

        </form>