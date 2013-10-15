<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

require('security/mysqli-streamvid_db.php');
?>
<div id="subHeader">
    <h1><img src="images/AddMovieTitle.png" /></h1>
</div>
<div id="displayListAddMovie">
    <div id="addMovieBox">
        <div class="box" id="movieSearchBox">
            <span >Search: </span><input id="movieSearchText" type="text"/>
            <input type="button" id="goButton" value="Go"/>
        </div>
        <div class="box" id="resultsBox"></div>
        <div class="box" id="confirmBox">
            <form></form>
            <input type="button" id="backButton" value="< Back"/>
            <input type="button" id="confirmButton" value="Confirm"/>
        </div>
        <div class="box" id="loadingBox">
            <img src="images/loading.gif"/>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#goButton').click(function(){
        $('.box#movieSearchBox').hide();
        $('.box#loadingBox').show();
        $.get('http://api.themoviedb.org/3/search/movie?api_key=8debf1870067ceed95542988bb1414ce&query=' + $('#movieSearchBox #movieSearchText').val(), function(data) {
            $('.box#resultsBox').html('');


            var resultsHTML = '<form>';

            if(data.results.length != 0){
                for(var i in data.results){
                    resultsHTML += '<input class="movieSelection" type="radio" name="movieChoice" data-movieid="' + data.results[i].id + '" ';

                    if(i == 0){
                        resultsHTML += 'checked="checked" ';
                    }

                    resultsHTML += '/><span>' + data.results[i].title;

                    if(data.results[i].release_date){
                        resultsHTML += ' (Released ' + data.results[i].release_date + ')';
                    }

                    resultsHTML += '</span><br/>';
                }
            }else{
                resultsHTML += 'No results to display'
            }

            resultsHTML += '</form><input type="button" id="backButton" value="< Back"/><input type="button" id="forwardButton" value="Next >"/>';

            $('.box#resultsBox').html($(resultsHTML));

            $('.box#resultsBox #backButton').click(function(){
                $('.box#resultsBox').hide();
                $('.box#movieSearchBox').show();
            });

            $('.box#resultsBox #forwardButton').click(function(){
                var movieid = $('.box#resultsBox input.movieSelection:checked').data('movieid');

                $('.box#resultsBox').hide();
                $('.box#loadingBox').show();

                $.get('http://api.themoviedb.org/3/movie/' + movieid + '?api_key=8debf1870067ceed95542988bb1414ce', function(movie) {
                    $('.box#confirmBox form').html('');

                    if(movie.id == movieid){
                        var formHTML = '<table>';
                        formHTML += '<tr><td>Title</td><td><input type="text" id="movieTitle" value="' + movie.title + '" /></td></tr>';
                        formHTML += '<tr><td>Release Date</td><td><input type="text" id="movieDate"  value="' + movie.release_date + '" /></td></tr>';
                        formHTML += '<tr><td>IMDb ID</td><td><input type="text" id="movieIMDb"  value="' + movie.imdb_id + '" /></td></tr>';
                        formHTML += '<tr><td>Runtime</td><td><input type="text" id="movieRuntime"  value="' + movie.runtime + '" /></td></tr>';
                        formHTML += '<tr><td>Overview</td><td><textarea  id="movieOverview">' + movie.overview + '</textarea></td></tr>';

                        var imageHTML = 'http://d3gtl9l2a4fn1j.cloudfront.net/t/p/w300' + movie.poster_path;
                        formHTML += '<tr><td>Poster Preview</td><td><img src="' + imageHTML + '" /></td></tr>';
                        formHTML += '<tr><td>Poster URL</td><td><input type="text" id="moviePoster"  value="' + imageHTML + '" /></td></tr>';
                        formHTML += '<tr><td>Filename</td><td><input type="text" id="movieFilename" /></td></tr>';

                        formHTML += '</table>';

                        $('.box#confirmBox form').html($(formHTML));

                        $('.box#confirmBox #backButton').click(function(){
                            $('.box#confirmBox').hide();
                            $('.box#resultsBox').show();
                        });

                        $('.box#confirmBox #confirmButton').click(function(){

                            if(confirm('Are you sure you want to submit new movie?')){
                                $.post('index.php', {
                                    command: 'submitNewMovie',
                                    title: $('.box#confirmBox #movieTitle').val(),
                                    release_date: $('.box#confirmBox #movieDate').val(),
                                    imdb_id: $('.box#confirmBox #movieIMDb').val(),
                                    runtime: $('.box#confirmBox #movieRuntime').val(),
                                    overview: $('.box#confirmBox #movieOverview').val(),
                                    poster: $('.box#confirmBox #moviePoster').val(),
                                    filename: $('.box#confirmBox #movieFilename').val()
                                });

                                $('.box#confirmBox').hide();
                                $('.box#loadingBox').show();

                                setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000)
                            }
                        });
                    }

                    $('.box#loadingBox').hide();
                    $('.box#confirmBox').show();
                });
            });

            $('.box#loadingBox').hide();
            $('.box#resultsBox').show();
        });
    });
</script>