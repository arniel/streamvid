<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

function ordinal_to_datetime($ordinal){
    $begin = new DateTime('0001-01-00');
    $date = new DateInterval("P".$ordinal."D");
    $begin->add($date);
    return $begin;
}
?>
<div id="subHeader">
    <h1><img src="images/TvTitle.png" /></h1>
    <ul id="sort">
        <?php
        function setStarred(){
            if(isset($_GET['sort'])){
                if($_GET['sort'] == 'starred'){
                    echo '<li><img id="star" src="images/icon_star_active.png" /></li>';
                }else{
                    echo '<li><img id="star" src="images/icon_star.png" onmouseover="this.src=\'images/icon_star_hover.png\'" onmouseout="this.src=\'images/icon_star.png\'" /></li>';
                }
            }else{
                echo '<li><img id="star" src="images/icon_star.png" onmouseover="this.src=\'images/icon_star_hover.png\'" onmouseout="this.src=\'images/icon_star.png\'" /></li>';
            }
        }

        function setFullSort(){
            if(isset($_GET['sort'])){
                if($_GET['sort'] == 'alphabetize'){
                    echo '<li><img id="alphabetize" src="images/icon_alphabetize_active.png" /></li>';
                }else{
                    echo '<li><img id="alphabetize" src="images/icon_alphabetize.png" onmouseover="this.src=\'images/icon_alphabetize_hover.png\'" onmouseout="this.src=\'images/icon_alphabetize.png\'" /></li>';
                }

                if($_GET['sort'] == 'shuffle'){
                    echo '<li><img id="shuffle" src="images/icon_shuffle_active.png" /></li>';
                }else{
                    echo '<li><img id="shuffle" src="images/icon_shuffle.png" onmouseover="this.src=\'images/icon_shuffle_hover.png\'" onmouseout="this.src=\'images/icon_shuffle.png\'" /></li>';
                }

                if($_GET['sort'] == 'starred'){
                    echo '<li><img id="star" src="images/icon_star_active.png" /></li>';
                }else{
                    echo '<li><img id="star" src="images/icon_star.png" onmouseover="this.src=\'images/icon_star_hover.png\'" onmouseout="this.src=\'images/icon_star.png\'" /></li>';
                }
            }else{
                if($_SESSION['prefs']['defaultTV'] == 'alphabetize'){
                    echo '<li><img id="alphabetize" src="images/icon_alphabetize_active.png" /></li>';
                }else{
                    echo '<li><img id="alphabetize" src="images/icon_alphabetize.png" onmouseover="this.src=\'images/icon_alphabetize_hover.png\'" onmouseout="this.src=\'images/icon_alphabetize.png\'" /></li>';
                }

                if($_SESSION['prefs']['defaultTV'] == 'shuffle'){
                    echo '<li><img id="shuffle" src="images/icon_shuffle_active.png" /></li>';
                }else{
                    echo '<li><img id="shuffle" src="images/icon_shuffle.png" onmouseover="this.src=\'images/icon_shuffle_hover.png\'" onmouseout="this.src=\'images/icon_shuffle.png\'" /></li>';
                }

                if($_SESSION['prefs']['defaultTV'] == 'starred'){
                    echo '<li><img id="star" src="images/icon_star_active.png" /></li>';
                }else{
                    echo '<li><img id="star" src="images/icon_star.png" onmouseover="this.src=\'images/icon_star_hover.png\'" onmouseout="this.src=\'images/icon_star.png\'" /></li>';
                }
            }
        }

        if(isset($_GET['o'])){
            if($_GET['o'] == 'latest'){
                setStarred();
            }else if($_GET['o'] == 'upcoming'){
                setStarred();
            }else{
                setFullSort();
            }
        }else{
            setFullSort();
        }
        ?>
    </ul>
</div>
<div id="displayListTV">
    <div id="options">
        <?php
        if(isset($_GET['o'])){
            if($_GET['o'] == 'latest'){
                echo '<a href="index.php?view=tv&o=all">All</a>  -  <a href="#" class="active">Latest</a>  -  <a href="index.php?view=tv&o=upcoming">Upcoming</a>';
            }else if($_GET['o'] == 'upcoming'){
                echo '<a href="index.php?view=tv&o=all">All</a>  -  <a href="index.php?view=tv&o=latest">Latest</a>  -  <a href="#" class="active">Upcoming</a>';
            }else{
                echo '<a href="#" class="active">All</a>  -  <a href="index.php?view=tv&o=latest">Latest</a>  -  <a href="index.php?view=tv&o=upcoming">Upcoming</a>';
            }
        }else{
            echo '<a href="#" class="active">All</a>  -  <a href="index.php?view=tv&o=latest">Latest</a>  -  <a href="index.php?view=tv&o=upcoming">Upcoming</a>';
        }
        ?>
    </div>
    <?php
    function sortStarredEps($eps){
        $starredEps = array();

        foreach($eps as $row){
            foreach($_SESSION['prefs']['myStarredShows'] as $showid){
                if($row['showid'] == $showid){
                    array_push($starredEps, $row);
                    break;
                }
            }
        }

        return $starredEps;
    }

    function sortStarredShows($shows){
        $starredShows = array();

        foreach($shows as $row){
            foreach($_SESSION['prefs']['myStarredShows'] as $showid){
                if($row['tvdb_id'] == $showid){
                    array_push($starredShows, $row);
                    break;
                }
            }
        }

        return $starredShows;
    }

    function normalShowDisplay($shows){
        if(isset($_GET['sort'])){
            if($_GET['sort'] == 'shuffle'){
                shuffle($shows);
            }else if($_GET['sort'] == 'starred'){
                $shows = sortStarredShows($shows);
            }
        }else if($_SESSION['prefs']['defaultTV'] == 'shuffle'){
            shuffle($shows);
        }else if($_SESSION['prefs']['defaultTV'] == 'starred'){
            $shows = sortStarredShows($shows);
        }

        echo '<table>';

        foreach($shows as $i=>$row){
            if(($i%2)==0){
                echo '<tr><td class="imageCol"><img class="showBanner" data-showid="' . $row['tvdb_id'] . '" src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /></td>';
            }else{
                echo '<td class="imageCol"><img class="showBanner" data-showid="' . $row['tvdb_id'] . '" src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /></td></tr>';
            }
        }

        if((sizeof($shows)%2)==1){
            echo '</tr>';
        }

        echo '</table>';
    }

    function latestShowDisplay($newEps){
        if(isset($_GET['sort'])){
            if($_GET['sort'] == 'starred'){
                $newEps = sortStarredEps($newEps);
            }
        }

        echo '<table id="latest" cellspacing="25">';

        foreach ($newEps as $i=>$row){
            if(($i%2)==0){
                echo '<tr class="latestEpRow"><td class="latestEpCol" data-episodeid="' . $row['episode_id'] . '"><h3 class="airdate">' . ordinal_to_datetime($row['airdate'])->format('Y-m-d') . '</h3><img src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /><h3 class="title">' . $row['show_name'] . ', Season ' . $row['season'] . ', Episode ' . $row['episode'] . ' - "' . $row['name'] . '"</h3></td>';
            }else{
                echo '<td class="latestEpCol" data-episodeid="' . $row['episode_id'] . '"><h3 class="airdate">' . ordinal_to_datetime($row['airdate'])->format('Y-m-d') . '</h3><img src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /><h3 class="title">' . $row['show_name'] . ', Season ' . $row['season'] . ', Episode ' . $row['episode'] . ' - "' . $row['name'] . '"</h3></td></tr>';
            }
        }

        if((sizeof($newEps)%2)==1){
            echo '</tr>';
        }

        echo '</table>';
    }

    function upcomingShowDisplay($upcomingEps){
        if(isset($_GET['sort'])){
            if($_GET['sort'] == 'starred'){
                $upcomingEps = sortStarredEps($upcomingEps);
            }
        }

        echo '<table id="upcoming" cellspacing="25">';

        if(sizeof($upcomingEps)==0){
            echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
        }else{
            foreach ($upcomingEps as $i=>$row){
                if(($i%2)==0){
                    echo '<tr class="latestEpRow"><td class="latestEpCol"><h3 class="airdate">' . ordinal_to_datetime($row['airdate'])->format('Y-m-d') . '</h3><img src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /><h3 class="title">' . $row['show_name'] . ', Season ' . $row['season'] . ', Episode ' . $row['episode'] . ' - "' . $row['name'] . '"</h3></td>';
                }else{
                    echo '<td class="latestEpCol"><h3 class="airdate">' . ordinal_to_datetime($row['airdate'])->format('Y-m-d') . '</h3><img src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /><h3 class="title">' . $row['show_name'] . ', Season ' . $row['season'] . ', Episode ' . $row['episode'] . ' - "' . $row['name'] . '"</h3></td></tr>';
                }
            }

            if((sizeof($upcomingEps)%2)==1){
                echo '</tr>';
            }
        }

        echo '</table>';
    }

    if(isset($_GET['o'])){
        if($_GET['o'] == 'latest'){
            latestShowDisplay($db->new_episodes());
        }else if($_GET['o'] == 'upcoming'){
            upcomingShowDisplay($db->upcoming_episodes());
        }else{
            normalShowDisplay($db->get_shows());
        }
    }else{
        normalShowDisplay($db->get_shows());
    }
    ?>
</div>
<script type="text/javascript">
    Image8 = new Image();
    Image8.src = "images/icon_alphabetize_hover.png";
    Image9 = new Image();
    Image9.src = "images/icon_alphabetize_active.png";
    Image9 = new Image();
    Image9.src = "images/icon_shuffle_hover.png";
    Image10 = new Image();
    Image10.src = "images/icon_shuffle_active.png";
    Image11 = new Image();
    Image11.src = "images/icon_star_hover.png";
    Image12 = new Image();
    Image12.src = "images/icon_star_active.png";

    $('#displayList #options span').click(function(){
        $('#displayList #options span').removeClass('active');
        $(this).addClass('active');
    });
    $(document).ready(function(){
        $('img#alphabetize').click(function(){
            if(window.location.href.indexOf('&sort=alphabetize') < 0){
                window.location.href = 'index.php?view=tv&o=all&sort=alphabetize';
            }
        });

        $('img#shuffle').click(function(){
            window.location.href = 'index.php?view=tv&o=all&sort=shuffle';
        });

        $('img#star').click(function(){
            var url = window.location.href;
            if(url.indexOf('&o=') > -1){
                if(url.indexOf('&o=latest') > -1){
                    if(url.indexOf('&sort=starred') > -1){
                        window.location.href = 'index.php?view=tv&o=latest';
                    }else{
                        window.location.href = 'index.php?view=tv&o=latest&sort=starred';
                    }
                }else if(url.indexOf('&o=upcoming') > -1){
                    if(url.indexOf('&sort=starred') > -1){
                        window.location.href = 'index.php?view=tv&o=upcoming';
                    }else{
                        window.location.href = 'index.php?view=tv&o=upcoming&sort=starred';
                    }
                }else{
                    if(url.indexOf('&sort=starred') > -1){
                        window.location.href = 'index.php?view=tv';
                    }else{
                        window.location.href = 'index.php?view=tv&sort=starred';
                    }
                }
            }else{
                if(url.indexOf('&sort=starred') > -1){
                    window.location.href = 'index.php?view=tv';
                }else{
                    window.location.href = 'index.php?view=tv&sort=starred';
                }
            }
        });

        $('img.showBanner').click(function(){
            window.location.href = 'index.php?view=show&id=' + $(this).data('showid');
        });

        $('table#latest td.latestEpCol').click(function(){
            window.location.href = 'index.php?view=episode&id=' + $(this).data('episodeid');
        });
    });
</script>