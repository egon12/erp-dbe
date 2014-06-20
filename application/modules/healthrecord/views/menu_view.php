<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

  <div class="container-flud">
    <div class="navbar-brand">Health Record</div>

    <ul class="nav navbar-nav">
      <li>
        <a href="<?php echo site_url('healthrecord') ?>">Input</a>
      </li>
      <li>
        <a href="<?php echo site_url('healthrecord/sugestion') ?>">Sugestion</a>
      </li>
    </ul>

    <ul class="nav navbar-nav navbar-right">
      <li>
        <a href="#">
          <span>Hello, <?php echo $the_user->first_name.' '.$the_user->last_name; ?>!</span>
        </a>
      </li>
      <li>
        <a href="<?php echo site_url('auth/logout'); ?>" title="">
          <img src="<?php echo asset_url('img/icons/topnav/logout.png'); ?>" alt=""><span>Logout</span>
        </a>
      </li>
    </ul>
  </div>

</nav>
<br>
<!--
<div id="topNav">
  <div class="fixed">
    <div class="wrapper">
      <div class="welcome">
        <h4>Health Record</h4>
      </div>
      <div class="userNav">
        <ul>
          <li>
            <a href="#">
              <span>Hello, <?php echo $the_user->first_name.' '.$the_user->last_name; ?>!</span>
            </a>
          </li>
          <li>
            <a href="<?php echo site_url('auth/logout'); ?>" title="">
              <img src="<?php echo asset_url('img/icons/topnav/logout.png'); ?>" alt=""><span>Logout</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="clear"></div>
    </div> -->
    <!-- /wrapper -->
    <!-- </div> </div> -->
