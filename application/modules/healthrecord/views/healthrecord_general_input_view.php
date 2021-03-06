<form class="form-horizontal" action="<?php echo site_url('healthrecord/add/general') ?>" method="POST" role="form" autocomplete="off">
  <input type="hidden" class="customer_id_input" name="customer_id">
  <br>
  <div class="row">
    <div class="col-md-6">
      <label>Nama : </label> <span id="customer_name"></span>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="date" class="col-sm-3">Date : </label> 
        <div class="col-sm-7">
          <input id="date" class="form-control datepicker_input" type="text" name="date" value="<?php echo date('Y-m-d') ?>">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-sm-12">Amnanesa :</label>
        <div class="col-sm-12">
          <textarea id="patient_notes" name="amnanesa" class="form-control">kel(-)</textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-5">Diagnostic :</label>
        <div class="col-sm-7">
          <input id="diagnostic" type="text" name="diagnostic" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="systolic_input" class="col-sm-5">Systolic/Diastolic :</label>
        <div class="col-sm-7">
          <input type="number" name="systolic" id="systolic_input"> 
          <span class="satuan">mmHg</span>
          / <input type="number" name="diastolic">
          <span class="satuan">mmHg</span>
        </div>
      </div>
      <div class="form-group">
        <label for="kolesterol_input" class="col-sm-5">Kolesterol :</label>
        <div class="col-sm-7">
          <input type="number" name="kolesterol" id="kolesterol_input">
          <span class="satuan">mg/dl</span>
        </div>
      </div>

      <hr>
      <div class="form-group">
        <label for="guladarah_puasa" class="col-sm-5">Gula Darah Puasa :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_puasa" id="guladarah_puasa">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <div class="form-group">
        <label for="guladarah_sewaktu" class="col-sm-5">Gula Darah Sewaktu :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_sewaktu" id="guladarah_sewaktu">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <div class="form-group">
        <label for="guladarah_sesudah" class="col-sm-5">Gula Darah 2 jam pp :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_sesudah" id="guladarah_sesudah">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label for="asam_urat_input" class="col-sm-5">Asam Urat :</label>
        <div class="col-sm-7">
          <input type="number" name="asam_urat" id="asam_urat_input" step="0.10">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label class="col-sm-12">Sugestion Key :</label>
        <div class="col-sm-12">
          <input id="sugestion_key" type="text" class="form-control" data-url="<?php echo site_url('healthrecord/sugestion/get') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-12" for="sugestion_note">Sugestion :</label>
        <div class="col-sm-12">
          <textarea id="sugestion_note" name="sugestion" class="form-control"><?php echo $sugestion ?></textarea>      
        </div>
      </div>
        <button class="btn btn-primary save-button" type="submit">Save</button>
    </div>
  </div>
</form>
