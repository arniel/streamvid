<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

$view = 'none';
if(isset($_GET['view'])){
    if($_GET['view'] == 'home')
        $view = 'home';
    else if($_GET['view'] == 'tv')
        $view = 'tv';
    else if($_GET['view'] == 'movies')
        $view = 'movies';
    else if($_GET['view'] == 'user')
        $view = 'user';
    else if($_GET['view'] == 'admin')
        $view = 'admin';
}else{
    $view = 'home';
}
?>
<ul>
    <li class="icon">
        <img id="home"
        <?php
        if($view != 'home')
            echo ' src="images/icon_home.png" onmouseover="this.src=\'images/icon_home_hover.png\'" onmouseout="this.src=\'images/icon_home.png\'" />';
        else
            echo ' src="images/icon_home_active.png" />'
        ?>
    </li>
    <li class="icon">
        <img id="tv"
        <?php
        if($view != 'tv')
            echo ' src="images/icon_tv.png" onmouseover="this.src=\'images/icon_tv_hover.png\'" onmouseout="this.src=\'images/icon_tv.png\'" />';
        else
            echo ' src="images/icon_tv_active.png" />'
        ?>
    </li>
    <li class="icon">
        <img id="movies"
        <?php
        if($view != 'movies')
            echo ' src="images/icon_movies.png" onmouseover="this.src=\'images/icon_movies_hover.png\'" onmouseout="this.src=\'images/icon_movies.png\'" />';
        else
            echo ' src="images/icon_movies_active.png" />'
        ?>
    </li>
    <li class="icon">
        <img id="user"
        <?php
        if($view != 'user')
            echo ' src="images/icon_user.png" onmouseover="this.src=\'images/icon_user_hover.png\'" onmouseout="this.src=\'images/icon_user.png\'" />';
        else
            echo ' src="images/icon_user_active.png" />'
        ?>
    </li>
    <? if($_SESSION['class'] == 'ADMN') { ?>
        <li class="icon">
            <img id="admin"
            <?php
            if($view != 'admin')
                echo ' src="images/icon_admin.png" onmouseover="this.src=\'images/icon_admin_hover.png\'" onmouseout="this.src=\'images/icon_admin.png\'" />';
            else
                echo ' src="images/icon_admin_active.png" />'
            ?>
        </li>
    <? } ?>
</ul>
<script type="text/javascript">
    Image1 = new Image();
    Image1.src = "images/icon_user_hover.png";
    Image3 = new Image();
    Image3.src = "images/icon_home_hover.png";
    Image5 = new Image();
    Image5.src = "images/icon_admin_hover.png";
    Image7 = new Image();
    Image7.src = "images/icon_tv_hover.png";
    Image7 = new Image();
    Image7.src = "images/icon_movies_hover.png";

    $(document).ready(function(){
        function resizeMenu(){
            var $contentblock = $('img#siteTitle');
            var num = $contentblock.offset().left +  ($contentblock.width()/2 - 60);
            $('#navigationPane ul').css({left: num + "px"});
        }

        resizeMenu();

        $(window).resize(resizeMenu);

        $('#navigationPane ul li img#home').click(function(){
            window.location.href = 'index.php?view=home';
        });

        $('#navigationPane ul li img#tv').click(function(){
            window.location.href = 'index.php?view=tv';
        });

        $('#navigationPane ul li img#movies').click(function(){
            window.location.href = 'index.php?view=movies';
        });

        $('#navigationPane ul li img#user').click(function(){
            window.location.href = 'index.php?view=user';
        });

        $('#navigationPane ul li img#admin').click(function(){
            window.location.href = 'index.php?view=admin';
        });
    });
</script>
