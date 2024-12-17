
<nav class="navbar" style="background-color: #e3f2fd; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false" style="border-color: #90caf9;">
        <span class="icon-bar" style="background-color: #42a5f5;"></span>
        <span class="icon-bar" style="background-color: #42a5f5;"></span>
        <span class="icon-bar" style="background-color: #42a5f5;"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php" style="color: #0d47a1; font-weight: bold; font-size: 1.5rem;">
        <?php echo lang('HOME_ADMIN') ?>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li><a href="categories.php" style="color: #0d47a1; font-weight: 600;"><?php echo lang('CATEGORIES') ?></a></li>
        <li><a href="items.php" style="color: #0d47a1; font-weight: 600;"><?php echo lang('ITEMS') ?></a></li>
        <li><a href="members.php" style="color: #0d47a1; font-weight: 600;"><?php echo lang('MEMBERS') ?></a></li>
        <li><a href="comments.php" style="color: #0d47a1; font-weight: 600;"><?php echo lang('FEEDBACKS') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: #0d47a1; font-weight: 600;">
            Admin <span class="caret" style="border-top: 5px solid #42a5f5; border-right: 5px solid transparent; border-left: 5px solid transparent;"></span>
          </a>
          <ul class="dropdown-menu" style="background-color: #e3f2fd;">
            <li><a href="../index.php" style="color: #0d47a1; font-weight: 600;">Visit Shop</a></li>
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>" style="color: #0d47a1; font-weight: 600;">Edit Profile</a></li>
            <li><a href="logout.php" style="color: #d32f2f; font-weight: 600;">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
