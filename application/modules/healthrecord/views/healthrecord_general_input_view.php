<form class="form-horizontal" action="<?php echo site_url('healthrecord/add/general') ?>" method="POST" role="form" autocomplete="off">
  <div class="row">
    <div class="col-md-6">
      <input type="hidden" class="customer_id_input" name="customer_id">
      <div class="form-group">
        <label class="col-sm-12">Amnanesa:</label>
        <div class="col-sm-12">
          <textarea id="patient_notes" name="amnanesa" class="form-control">kel(-)</textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-12">Profesional's Diagnostic:</label>
        <div class="col-sm-12">
          <input id="diagnostic" type="text" name="diagnostic" class="form-control" data-url="<?php echo site_url('healthrecord/sugestion/get') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="systolic_input" class="col-sm-5">Systolic/Diastolic:</label>
        <div class="col-sm-7">
          <input type="number" name="systolic" id="systolic_input"> / <input type="number" name="diastolic">
        </div>
      </div>
      <div class="form-group">
        <label for="kolesterol_input" class="col-sm-5">Kolesterol:</label>
        <div class="col-sm-7"><input type="number" name="kolesterol" id="kolesterol_input"></div>
      </div>

      <div class="form-group">
        <label for="" class="col-sm-5">Gula Darah:</label>
      </div>
      <div class="form-group">
        <label for="guladarah_puasa" class="col-sm-4 col-sm-offset-1">Puasa:</label>
        <div class="col-sm-7"><input type="number" name="guladarah_puasa" id="guladarah_puasa"></div>
      </div>
      <div class="form-group">
        <label for="guladarah_sewaktu" class="col-sm-4 col-sm-offset-1">Sewaktu Makan:</label>
        <div class="col-sm-7"><input type="number" name="guladarah_sewaktu" id="guladarah_sewaktu"></div>
      </div>
      <div class="form-group">
        <label for="guladarah_sesudah" class="col-sm-4 col-sm-offset-1">Sesudah Makan:</label>
        <div class="col-sm-7"><input type="number" name="guladarah_sesudah" id="guladarah_sesudah"></div>
      </div>


      <div class="form-group">
        <label for="asam_urat_input" class="col-sm-5">Asam Urat:</label>
        <div class="col-sm-7"><input type="number" name="asam_urat" id="asam_urat_input" step="0.10"></div>
      </div>
    </div>
    <div class="col-md-6">
      <label for="sugestion_note">Sugestion:</label>
      <textarea id="sugestion_note" name="sugestion" class="form-control"><?php echo $sugestion ?></textarea>
      <br>
      <button class="btn btn-primary save-button" type="submit">Save</button>
    </div>
  </div>
</form>
