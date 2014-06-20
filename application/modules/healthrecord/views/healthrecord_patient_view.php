          <ul class="list-group">
            <li class="list-group-item">
                <input id="customer_search" class="customer_id_input" type="text" name="query" placeholder="Search patient" data-url="<?php echo site_url('healthrecord/search_customer') ?>" autocomplete="off"/><br />
            </li>
            <li class="list-group-item">
                <select id="customer_list" class="customer_select" size="8" autocomplete="off">
                <?php foreach ($new_customers as $customer): ?>
                  <option value="<?php echo $customer->id?>">
                    <?php echo $customer->id ?>  |  <?php echo $customer->name ?>
                  </option>
                <?php endforeach ?>
                </select>
            </li>
            <li class="list-group-item">
              <form action="<?php echo site_url('healthrecord/get_print') ?>" method="GET">
                <input type="hidden" class="customer_id_input" name="customer_id">
                <button class="btn btn-primary save-button" type="submit">Print</button>
                <a class="btn btn-warning" target="_blank" href="<?php echo site_url('customer/add') ?>">New Patient</a>
              </form>
            </li>
          </ul>
