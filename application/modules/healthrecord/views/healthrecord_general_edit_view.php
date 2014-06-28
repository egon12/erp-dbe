<div class="modal-dialog">
<div class="modal-content">
<div class="modal-body">
<form class="form-horizontal" action="<?php echo site_url('healthrecord/update/general/'.$row->id) ?>" method="POST" role="form" autocomplete="off">
  <input type="hidden" name="id" value="<?php echo $row->id?>">
  <input type="hidden" name="customer_id" value="<?php echo $row->customer_id?>">
  <br>
  <div class="row">
    <div class="col-md-6">
      <label>Nama : </label> <span><?php echo $row->customer_id . " | " . $customer_name ?></span>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="date" class="col-sm-3">Date : </label> 
        <div class="col-sm-7">
          <input class="form-control datepicker_input" type="text" name="date" value="<?php echo $row->date ?>">
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label class="col-sm-12">Amnanesa :</label>
        <div class="col-sm-12">
          <textarea name="amnanesa" class="form-control"><?php echo $row->amnanesa ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-5">Diagnostic :</label>
        <div class="col-sm-7">
          <input type="text" name="diagnostic" class="form-control" value="<?php echo $row->diagnostic ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-5">Systolic/Diastolic :</label>
        <div class="col-sm-7">
          <input type="number" name="systolic" value="<?php echo $row->systolic ?>"> 
          <span class="satuan">mmHg</span>
          / <input type="number" name="diastolic" value="<?php echo $row->diastolic ?>">
          <span class="satuan">mmHg</span>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-5">Kolesterol :</label>
        <div class="col-sm-7">
          <input type="number" name="kolesterol" value="<?php echo $row->kolesterol ?>">
          <span class="satuan">mg/dl</span>
        </div>
      </div>

      <hr>
      <div class="form-group">
        <label for="guladarah_puasa" class="col-sm-5">Gula Darah Puasa :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_puasa" value="<?php echo $row->guladarah_puasa ?>">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <div class="form-group">
        <label for="guladarah_sewaktu" class="col-sm-5">Gula Darah Sewaktu :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_sewaktu" value="<?php echo $row->guladarah_sewaktu ?>">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <div class="form-group">
        <label for="guladarah_sesudah" class="col-sm-5">Gula Darah 2 jam pp :</label>
        <div class="col-sm-7">
          <input type="number" name="guladarah_sesudah" value="<?php echo $row->guladarah_sesudah ?>">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label for="asam_urat_input" class="col-sm-5">Asam Urat :</label>
        <div class="col-sm-7">
          <input type="number" name="asam_urat" step="0.10" value="<?php echo $row->asam_urat ?>">
          <span class="satuan">mg/dl</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label class="col-sm-12" for="sugestion_note">Sugestion :</label>
        <div class="col-sm-12">
          <textarea name="sugestion" class="form-control"><?php echo $row->sugestion ?></textarea>      
        </div>
      </div>
        <button class="btn btn-primary save-button" type="submit">Save</button>
        <a class="btn btn-danger save-button" data-dismiss="modal" href="<?php echo site_url('healthrecord')?>">Cancel</a>
    </div>
  </div>
</form>
</div>
</div>
</div>
