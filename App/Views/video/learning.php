<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subtitles</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/App/Views/css/subtitle.css"/>
    <link rel="stylesheet" href="/App/Views/css/jumbotron-narrow.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>

    <script src="/App/Views/js/video.js"></script>
    <script src="/App/Views/js/LearningVideo.js"></script>
</head>

<body>
<style>

    .container {
        max-width: 100%;
    }

</style>
<div class="container" style="width: 100%">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="/">All videos</a></li>
                <li role="presentation"><a
                        href="/video/add">add</a></li>
            </ul>
        </nav>
        <h4 id="video_name"></h4>
    </div>

    <div class="episode_media_container" style="position: relative;">
        <video id="current_video" src="" controls style="width: 100%;"></video>

        <div id="information" style="white-space: nowrap; vertical-align: middle">
            <div class="input-group" style="width: 240px">
                <div class="input-group-addon"> words: <span class="currentRepeat_s__words"></span> / <span class="ready_repeat_s__words"></span> </div>
                <input type="text" id="next_time" class="form-control" placeholder="0" value="">
                <span class="input-group-btn">
            <button id="send_next_time" data-id_my_dictionary="" class="btn btn-default" type="button"><span class="glyphicon glyphicon-log-in"></span></button>
            </span>
            </div>
        </div>

        <div class="outer_layer_addSentence">
            <div id="addSentence" class="text-center" style="display: none">
                <form>
                    <div id="modal_place_for_subtitle" class="text-center"
                         style="height: 70px; font-family: 'Calibri Light'; font-size: 28px">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Иностранное слово:</label>
                        <input type="text" class="form-control" id="original_word" style="width">
                    </div>

                    <div id="div_for_input_translate_word" class="form-group" style="width">
                        <label id="lable_option_translate" for="message-text" class="control-label">Перевод:</label>
                        <input class="form-control" id="translate_word">
                        <span id="lower_entry" class="help-block"></span>
                    </div>
                </form>
                <div id="word_desctiption">

                </div>

            <button type="button" class="btn btn-default close_add_me" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary add_me">Add to Dictionary</button>

            </div>
        </div>

        <div id="place_for_subtitle" class="text-center">

        </div>

    </div>


    <div id="step3"></div>

    <!--<p id="place_for_subtitle" class="text-center"
       style="height: 70px; font-family: 'Calibri Light'; font-size: 28px">
    </p>-->

    <div id="div_table_subtitles" class="bs-example" style="overflow: auto; height: 335px;"
         data-example-id="contextual-table">

        <table id="table_offset" class="table table-striped">
            <thead>
            <tr>
                <th>Sound</th>
                <th>#</th>
                <th>start</th>
                <th>text</th>
            </tr>
            </thead>
            <tbody id="tbody_table_offset">

            </tbody>
        </table>
    </div>

    <!--<script>$('#addSentence').modal('show');</script>-->

    <footer class="footer">
        <p>&copy; <?php echo date("Y") ?> Subtitle, Inc.</p>
    </footer>

</div> <!-- /container -->


</body>
</html>