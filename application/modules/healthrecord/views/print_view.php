<!Doctype html>
<html>
  <meta charset="UTF-8" />
  <style>
    

  </style>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Pemeriksaan</th>
        <th>Tindakan</th>
        <th>TTD</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_data as $date => $data): ?>
      <tr>
        <td><?php echo $date ?></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php endforeach?>
    </tbody>
  </table>

  <pre>
  <?php var_dump($all_data); ?>
</html>
