<nav>
<ul class="nav">
    <li class="dashboard">
        <a href="<?php echo site_url()?>"><span class="icon-dashboard nt"></span><span>Dashboard</span></a>
    </li>

    <?php if ('cashier' ==  $the_user_group || 'owner' == $the_user_group || 'administrator' == $the_user_group): ?>
    <li class="pos">
        <a href='<?php echo site_url('pos')?>'><span class="icos-cashreg nt"></span><span>Point of Sales</span></a>
    </li>
    <li class="customers">
        <a href='<?php echo site_url('customer')?>'><span class="icos-admin2 nt"></span><span>Customers</span></a>
    </li>
    <li class="recap">
        <a href='<?php echo site_url('pos/recap/')?>'><span class="icos-calc nt"></span><span>Recap</span></a>
    </li>
    <li class="report">
        <a href='<?php echo site_url('reportgen')?>'><span class="icos-stats nt"></span><span>Report</span></a>
    </li>
    <li class="History">
        <a href='<?php echo site_url('history')?>'><span class="icon-history"></span><span>History</span></a>
    </li>
    <?php endif ?>

    <?php if ('owner' == $the_user_group || 'administrator' == $the_user_group): ?>
    <li class="cancel">
        <a href='<?php echo site_url('pos/cancel/')?>'><span class="icos-trash nt"></span><span>Cancelation</span></a>
    </li>
    <li class="products">
        <a href='<?php echo site_url('product')?>'><span class="icos-basket2 nt"></span><span>Products</span></a>
    </li>
    <!-- 
    <li class="purchase">
        <a href='<?php echo site_url('purchasing')?>'><span class="icos-files nt"></span><span>Purchase Order</span></a>
    </li>
    <li class="vendor">
        <a href='<?php echo site_url('vendors')?>'><span class="icos-home nt"></span><span>Vendor</span></a>
    </li>
    -->
    <?php endif ?>

    <?php if ('warehouse' ==  $the_user_group || 'owner' == $the_user_group || 'administrator' == $the_user_group): ?>
    <li class="stock">
        <a href='<?php echo site_url('stocks')?>'><span class="icos-vault nt"></span><span>Stock List</span></a>
    </li>
    <?php endif ?>

    <?php if ('owner' == $the_user_group): ?>
    <li class="users">
        <a href='<?php echo site_url('users')?>'><span class="icos-users nt"></span><span>Users</span></a>
    </li>
    <?php endif ?>
</ul>
</nav>
