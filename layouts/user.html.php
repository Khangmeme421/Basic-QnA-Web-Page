</ul>
<div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?=$_SESSION['username']?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="profile.php?id=<?=$_SESSION['userid']?>">My Profile</a></li>
        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
    </ul>
</div>