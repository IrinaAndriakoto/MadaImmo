
<h1>Entrez la plage de dates pour verifier votre chiffre d'affaire.</h1>
<form method="post" action="<?php echo site_url('Traitement/get_revenue_by_dates'); ?>">
    <p>Start date : <input type="date" class="form-control" id="start_date" name="start_date" value="2024-01-01" ></p>
            <p>End date : <input type="date" class="form-control" id="end_date" name="end_date" value="2024-12-31"></p>
            <button type="submit">Filter</button>
</form>

