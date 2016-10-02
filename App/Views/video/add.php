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
    <script src="/App/Views/js/addOrEditVideo.js"></script>
</head>

<body>

<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="/">All videos</a></li>
                <li role="presentation"><a href="/video/add">add</a></li>
            </ul>
        </nav>
        <h3 class="text-muted"></h3>
    </div>

    <div id="step1-1" class="jumbotron">

        <h3>1 из 4 Шагов</h3>
        <ul class="text-left">
            <p>
                <lable>Для начала впишите название фильма или сериала.</lable>
            </p>
            <input type="text" id="video_title" class="form-control"
                   placeholder="{Фильм} или {Сериал: Сезон: № серии}">
        </ul>
    </div>
    <div id="step1-2" class="jumbotron" style="display: none">

        <h3>2 из 4 Шагов, инструкция:</h3>
        <ol class="text-left">
            <li>Скопируйте фильм в папку проекта 'video_files'</li>
            <li>Найдите его <a href="http://subtitle.loc/video_files/" target="_blank">среди файлов на сервере</a>.
                Откройте, и убедитесь что файл воспроизводится браузером, скопируйте ссылку из адресной строки в поле
                ниже
            </li>

            <div id="div_for_path_to_video_file" class="">
                <!--<input type="text" id="video_title" class="form-control" placeholder="введине название {Фильма} или {Сериал: Сезон: № серии}">-->
                <label id="label_path_to_video_file" class="control-label"></label>
                <input type="text" id="path_to_video_file" class="form-control" placeholder="http://..."
                       value="">
                <span id="span_path_to_video_file" class="help-block"></span>
            </div>
            <li>Если путь указан верно появится видео плеер и станет доступен следующий шаг</li>
        </ol>
    </div>

    <div id="step2-1">
        <!--    poster="/Views/img/video-marketing.jpg"-->
        <video id="current_video" controls="" style="width: 700px; display: none"
        ></video>

        <div>
            <p id="place_for_subtitle" class="text-center"
               style="height: 70px; display: none; font-family: 'Calibri Light'; font-size: 28px">...</p>
        </div>
    </div>


    <br>
    <div id="step2-2" class="jumbotron" style="display: none;">
        <h3>3 из 4 Шагов, инструкция:</h3>
        <ol class="text-left">
            <li>Скачайте английские субтитры к вашему видео</li>
            <li>Если вы скачали архив разорхивируйте по файлу с субтитрами нажмите "правой кнопкой" выберете -> "открыть
                с помощью" -> "блокнот"
            </li>
            <li>Вставьте содержимое файла в это поле (Что бы выделить и скопировать всё содержимое файла, поставьте
                курсор на любое место в тексе файла субтитров и нажмите Ctrl+A затем скопируйте выделенное)
                <textarea id="text_eng_subs" class="form-control" rows="5" placeholder="Короткий пример того что содержит в себе файл субтитров:

1
00:01:23,956 --> 00:01:25,355
Hello? Who!?

2
00:01:25,725 --> 00:01:27,488
No. I'm sorry, I don't know anyone by that name


3
00:01:27,693 --> 00:01:29,752
No, listen, I get about a hundred customers a night

4
00:01:30,062 --> 00:01:31,324
I can't keep track of them all.
"></textarea></li>
            <br>
            <li>
                <button id="add_eng_sub" type="button" class="btn btn-warning ">Наложить субтитры на видео</button>
            </li>
        </ol>
    </div>

    <div id="step3"></div>
    <div id="div_table_subtitles" class="bs-example" style="display: none; overflow: auto; height: 180px;"
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

    <div class="modal fade" id="addSentence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Добавить в копилку</h4></div>
                <div class="modal-body">
                    <form>
                        <div class="form-group"><label for="recipient-name" class="control-label">Иностранное
                                слово:</label>
                            <input type="text" class="form-control" id="original_word"></div>
                        <div class="form-group"><label for="message-text" class="control-label">Перевод:</label>
                            <input class="form-control" id="translate_word"></div>
                    </form>
                    <div id="word_desctiption">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>

    <!--<script>$('#addSentence').modal('show');</script>-->

    <footer class="footer">
        <p>&copy; <?php echo date("Y") ?> Subtitle, Inc.</p>
    </footer>

</div> <!-- /container -->


</body>
</html>