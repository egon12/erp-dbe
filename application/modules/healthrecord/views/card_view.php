  <table class="table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Pemeriksaan</th>
        <th>Tindakan</th>
        <th>Pemeriksa</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_data as $date => $data): ?>
      <tr>
        <td><?php echo $data->date ?></td>
        <td>
            <?php if ($data->amnanesa) {echo $data->amnanesa .'<br>';} ?>
            <?php if ($data->systolic) {echo 'Tekanan Darah: ' . $data->systolic .'/'. $data->diastolic .'<br>'; } ?>
            <?php if ($data->kolesterol) {echo 'Kolesterol : ' . $data->kolesterol .'<br>'; } ?>
            <?php if ($data->guladarah_puasa) {echo 'Gula Darah Puasa : ' . $data->guladarah_puasa .'<br>'; } ?>
            <?php if ($data->guladarah_sewaktu) {echo 'Gula Darah Sewaktu Makan : ' . $data->guladarah_sewaktu .'<br>'; } ?>
            <?php if ($data->guladarah_sesudah) {echo 'Gula Darah Sebelum Makan : ' . $data->guladarah_sesudah .'<br>'; } ?>
            <?php if ($data->asam_urat) {echo 'Asam Urat : ' . $data->asam_urat .'<br>'; } ?>

        </td>
        <td>
          <?php if ($data->sugestion) {echo nl2br($data->sugestion); } ?>
        </td>
        <td>          
          <?php echo $data->first_name; ?>
        </td>
        <td>
          <a href="<?php echo site_url('healthrecord/get_update_form/general/'.$data->id) ?>" class="btn btn-primary" data-toggle="modal" data-target="#modal">Edit</a>
          <a href="<?php echo site_url('healthrecord/delete/general/'.$data->id) ?>" class="btn btn-danger">Delete</a>
        </td>
      </tr>
      <?php endforeach?>
    </tbody>
  </table>
