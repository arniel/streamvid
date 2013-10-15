<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

require('security/mysqli-streamvid_db.php');
?>
<div id="subHeader">
    <h1><img src="images/AdminTitle.png" /></h1>
</div>
<div id="displayListAdmin">
    <?php
    if($result2 = $mysqli->query('SELECT * FROM  invite_keys')){
        echo '<h3>Invite Keys</h3><table id="invitesAdmin"><tr><th>Invite Key</th><th>Privilege</th><th>Registration</th><th>Delete?</th></tr>';

        if($result2->num_rows > 0){
            while($row = $result2->fetch_row()){
                echo '<tr>';
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td><a href="create_user.php?rk=' . $row[0] . '">Link</a></td>';
                echo '<td><input class="deleteKey" type="button" value="Delete" /></td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    }else{
        echo $mysqli->error;
    }
    ?>
    <h3>Generate User Accounts</h3>
    <div id="accountCreator">
        <select id="useraccounts">
            <option value="USER">User</option>
            <option value="ADMN">Admin</option>
        </select>
        <input id="generateKey" type="button" value="Generate User Account Key"/>
    </div>
    <h3>New Site Notification</h3>
    <div id="newNotification">
        <table cellspacing="10">
            <tr>
                <td>Title (<span id="titleCharCount">25</span>)</td>
                <td>
                    <input id="messageTitle" type="text" />
                </td>
            </tr>
            <tr>
                <td>Text (<span id="textCharCount">200</span>)</td>
                <td>
                    <textarea id="messageBody" rows="10" cols="20"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input id="submitNewMessage" type="button" value="Submit Notification"/>
                </td>
            </tr>
        </table>
    </div>
    <?php
    if($result5 = $mysqli_movies->query('SELECT * FROM  metadata ORDER BY title')){
        echo '<h3>Movies</h3><div id="addNewMovieWrapper"><input type="button" id="addNewMovie" value="Add New Movie"/></div><table id="moviesAdmin"><tr><th>Title</th><th>IMDb ID</th><th>Poster Path</th><th>Filename</th><th>Delete?</th></tr>';

        if($result5->num_rows > 0){
            while($row = $result5->fetch_row()){
                echo '<tr>';
                echo '<td>' . $row[2] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td>' . $row[6] . '</td>';
                echo '<td>' . $row[7] . '</td>';
                echo '<td><input class="deleteMovieKey" type="button" value="Delete" data-movieid="' . $row[0] . '" /></td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    }else{
        echo $mysqli->error;
    }
    ?>
    <h3>Stats JSON</h3>
    <div id="jsonText">
        <textarea><? echo file_get_contents($config['StreamVidLoc'] . 'stats.json') ?></textarea>
        <input type="button" id="runstats" value="Run Stat Script"/>
    </div>
    <h3>Featured TV</h3>
    <div id="submitStaffPicksTV">
        <input id="default" type="button" value="Reset Checkboxes" />
        <input id="submit" type="button" value="Submit Picks" />
    </div>
    <table id="staffPicksTV" cellspacing="10">
        <?php
        $shows = $db->get_shows();
        $staffPicksTV = $mysqli->query('SELECT * FROM staff_picks');
        $staffPicksTVArr = array();

        foreach($staffPicksTV as $tvdbid){
            $staffPicksTVArr[(string)$tvdbid['tvdbid']] = 'true';
        }

        foreach($shows as $i=>$show){
            $checked = false;

            if(sizeof($staffPicksTVArr) != 0){
                if(isset($staffPicksTVArr[$show['tvdb_id']])){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }
            }

            if($i % 4 == 0){
                if($i == 0){
                    echo '<tr><td><input type="checkbox" name="staffPick" value="' . $show['tvdb_id'] . '" ' . $checked . '>' . $show['show_name'] . '</td>';
                }else{
                    echo '</tr><tr><td><input type="checkbox" name="staffPick" value="' . $show['tvdb_id'] . '" ' . $checked . '>' . $show['show_name'] . '</td>';
                }
            }else{
                echo '<td><input type="checkbox" name="staffPick" value="' . $show['tvdb_id'] . '" ' . $checked . '>' . $show['show_name'] . '</td>';
            }
        }
        ?>
    </table>
    <h3>Featured Movies</h3>
    <div id="submitStaffPicksMovies">
        <input id="default" type="button" value="Reset Checkboxes" />
        <input id="submit" type="button" value="Submit Picks" />
    </div>
    <table id="staffPicksMovies" cellspacing="10">
        <?php
        $movies = $mysqli_movies->query('SELECT * FROM metadata ORDER BY title');
        $staffPicksMovies = $mysqli_movies->query('SELECT * FROM staff_picks');
        $staffPicksMoviesArr = array();

        foreach($staffPicksMovies as $staffPick){
            $staffPicksMoviesArr[(string)$staffPick['movieid']] = 'true';
        }

        foreach($movies as $i=>$movie){
            $checked = false;

            if(sizeof($staffPicksMoviesArr) != 0){
                if(isset($staffPicksMoviesArr[$movie['id']])){
                    $checked = 'checked';
                }else{
                    $checked = '';
                }
            }

            if($i % 4 == 0){
                if($i == 0){
                    echo '<tr><td><input type="checkbox" name="staffPick" data-title="' . $movie['title'] . '" data-poster="' . $movie['poster'] . '" value="' . $movie['id'] . '" ' . $checked . '>' . $movie['title'] . '</td>';
                }else{
                    echo '</tr><tr><td><input type="checkbox" name="staffPick" data-title="' . $movie['title'] . '" data-poster="' . $movie['poster'] . '"  value="' . $movie['id'] . '" ' . $checked . '>' . $movie['title'] . '</td>';
                }
            }else{
                echo '<td><input type="checkbox" name="staffPick" data-title="' . $movie['title'] . '" data-poster="' . $movie['poster'] . '"  value="' . $movie['id'] . '" ' . $checked . '>' . $movie['title'] . '</td>';
            }
        }
        ?>
    </table>
    <?php
    if($result1 = $mysqli->query('SELECT * FROM registered_users')){
        echo '<h3>Users</h3><table id="usersAdmin"><tr><th>ID</th><th>Username</th><th>Privilege</th><th>Invite ID</th><th>Password</th><th>Delete?</th></tr>';
        if($result1->num_rows > 0){
            while($row = $result1->fetch_row()){
                echo '<tr>';
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td>' . $row[3] . '</td>';
                echo '<td>' . $row[4] . '</td>';
                echo '<td>' . $row[2] . '</td>';
                echo '<td><input class="deleteUser" type="button" value="Delete" /></td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    }else{
        echo $mysqli->error;
    }
    ?>
</div>

<script type="text/javascript">
    $('input#generateKey').click(function(){
        window.location.href = 'index.php?view=admincommand&newKey=' + $('select#useraccounts').val();
    });

    $('input#messageTitle').keydown(function(){
        if($(this).val().length > 25){
            $(this).val($(this).val().substring(0, 25));
        }else{
            $('span#titleCharCount').html(25 - $(this).val().length);
        }
    });

    $('textarea#messageBody').keydown(function(){
        if($(this).val().length > 200){
            $(this).val($(this).val().substring(0, 200));
        }else{
            $('span#textCharCount').html(200 - $(this).val().length);
        }
    });

    $('input#submitNewMessage').click(function(){
        if(confirm('Are you sure? You can\'t edit once you click OK')){
            var messageTitle = $('#newNotification #messageTitle').val().substring(0, 25);
            var messageBody = $('#newNotification #messageBody').val().substring(0, 200);

            $.post('index.php', {
                command: 'submitNewMessage',
                title: messageTitle,
                body: messageBody
            });

            $('#newNotification #messageTitle').val('');
            $('#newNotification #messageBody').val('');
        }
    });

    $('input#addNewMovie').click(function(){
        window.location.href = 'index.php?view=admin&addmovie=true';
    });

    $('input.deleteMovieKey').click(function(){
        if(confirm('Are you sure you want to delete this movie?')){
            $.post('index.php', {
                command: 'deleteMovie',
                movieid: $(this).data('movieid')
            }, function(){
                window.location.href = 'index.php?view=admin';
            });
        }
    });

    $('input#runstats').click(function(){
        window.location.href = 'index.php?view=admincommand&runstats=true';
    });

    $('#submitStaffPicksTV input#default').click(function(){
        $('table#staffPicksTV td input').attr('checked', false);
    });

    $('#submitStaffPicksTV input#submit').click(function(){
        var staffPicksArr = [];

        $('table#staffPicksTV td input').each(function(){
            if($(this).is(":checked")){
                staffPicksArr.push($(this).val());
            }
        });

        var postObj = {
            command: 'submitStaffPicksTV',
            staffPicks: JSON.stringify(staffPicksArr)
        }

        console.log(postObj);

        $.post('index.php', postObj);

        alert('Staff TV Picks Submitted');
    });

    $('table#staffPicksTV td input').change(function(){
        var refCount = 0;
        $('table#staffPicksTV td input').each(function(){
            if($(this).is(":checked")){
                refCount++;
            }
        });

        if(refCount > 6){
            $(this).attr('checked', false);
        }
    });

    $('#submitStaffPicksMovies input#default').click(function(){
        $('table#staffPicksMovies td input').attr('checked', false);
    });

    $('#submitStaffPicksMovies input#submit').click(function(){
        var staffPicksArr = [];

        $('table#staffPicksMovies td input').each(function(){
            if($(this).is(":checked")){
                staffPicksArr.push({ movieid: $(this).val(), poster: $(this).data('poster'), title: $(this).data('title') });
            }
        });

        var postObj = {
            command: 'submitStaffPicksMovies',
            staffPicks: staffPicksArr
        }

        console.log(postObj);

        $.post('index.php', postObj);

        alert('Staff Movie Picks Submitted');
    });

    $('table#staffPicksMovies td input').change(function(){
        var refCount = 0;
        $('table#staffPicksMovies td input').each(function(){
            if($(this).is(":checked")){
                refCount++;
            }
        });

        if(refCount > 4){
            $(this).attr('checked', false);
        }
    });

    $('input.deleteKey').click(function(){
        var key = $(this).parent().parent().find('td:first-child').html();

        window.location.href = 'index.php?view=admincommand&deleteKey=' + key;
    });

    $('input.deleteUser').click(function(){
        if(confirm('Are you sure you want to do this?')){
            var key = $(this).parent().parent().find('td:first-child').html();

            window.location.href = 'index.php?view=admincommand&deleteUser=' + key;
        }
    });
</script>