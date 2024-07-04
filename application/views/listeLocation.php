<h2>La liste des locations</h2>
<table>
    <thead>
        <tr>
            <th>Designation</th>
            <th>Duree</th>
        </tr>
        <?php foreach($locations as $lo): ?>
        <tr>
            <td><?php echo $lo->nom; ?></td>
            <td><?php echo $lo->duree; ?></td>
            <td><form action="<?php echo site_url('Traitement/locationDetails'); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $lo->id; ?>">
                <input type="hidden" name="duree" value="<?php echo $lo->duree ; ?>">
                    <button type="submit">Details</button></td>
            </form>
        </tr>
            <?php endforeach; ?>
    </thead>
</table>