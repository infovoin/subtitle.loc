<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
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
        <h3 class="text-muted" align="center">Video Library</h3>
    </div>


    <div id="div_table_all_video" class="bs-example" style="overflow: auto;"
         data-example-id="contextual-table">
        <table id="table_offset" class="table table-striped">
            <thead>
            <tr class="text-muted">
                <th>Название</th>
                <th>Если чего то нехватает</th>
                <th>Ред</th>
                <th>Слов</th>
                <th>Фраз</th>
                <th>Просмотров</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($videos as $video): ?>

                <?php
                //Выясняем есть ли неуказанные поля. путь до файла и субтитры если есть хоть один то доступ давать только на редактирование.
                $error = '';
                if (empty($video->path_to_file)) {
                    $error .= ' [Не указан путь до файла] <br>';
                }

                if (empty($video->ready_eng_sub)) {
                    $error .= ' [Не заполнены субтитры] ';
                }

                $video->video_name = $video->video_name ?? ' Без имени';
                ?>

                <tr>
                    <td style="vertical-align: middle">
                        <?php if (empty($error)): ?>
                            <a href="/video/learning/?id=<?php echo $video->id ?>">
                                <?php echo $video->video_name ?>
                            </a>
                        <?php else: ?>
                            <?php echo $video->video_name ?>
                        <?php endif; ?>
                    </td>

                    <td style="vertical-align: middle"><span class="text-danger"><?php echo $error ?></span></td>
                    <td style="vertical-align: middle">
                        <?php $class = (empty($error)) ? 'btn-info' : 'btn-danger' ?>
                        <a href="/video/edit/?id=<?php echo $video->id ?>">
                            <button class="btn <?php echo $class ?> btn-sm" type="button" aria-label="Right Align">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </button>
                        </a>
                    </td>

                    <td style="vertical-align: middle"><?php
                        echo $dictionary_info->it_is_time_repeat[$video->id] ?? '0';
                        echo ' / ';
                        echo $dictionary_info->total_word[$video->id] ?? '0' ?></td>
                    <td style="vertical-align: middle"><?php ?></td>
                    <td style="vertical-align: middle"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="row marketing">
        <div class="col-lg-6">
            <h4>Где брать фильмы в Английской озвучке?</h4>
            <p>
            <ul>
                <li href="http://www.subsmovies.com">subsmovies.com</li>
                <li href="http://123movies.to/">123movies.to</li>
                <li href="http://fmovies.to/movies">fmovies.to</li>
            </ul>
            </p>

            <h4>Где взять субтитры?</h4>
            <p>
            <ul>
                <li><a href="http://www.moviesubtitles.org/">moviesubtitles.org</a></li>
                <li><a href="https://subscene.com/">subscene.com</a></li>
                <li><a href="http://subsmax.com/">subsmax.com</a></li>
            </ul>
            </p>


            <h4>Скачанные субтитры нужно обработать</h4>
            <p>Устранить расхождения звука и текста субтитров. Проще всего это сделать в програме <a
                    href="http://www.aegisub.org/">aegisub.</a> <br>
                Небольшая <a
                    href="https://docs.google.com/document/d/1PEBrGNj8z-gnvmWYcMrMbI5cfp4Q9hau_DHfqf3P4yA/edit?usp=sharing">инструкция
                    по исопльзованию</a></p>


        </div>

        <div class="col-lg-6">
            <h4>Горячие клавиши Y , U , R</h4>
            <p>
            <ul>
                <li>
                    Y - предыдущий субтитр
                </li>
                <li>
                    U - следующий субтитр
                </li>
                <li>
                    R - повторить текущий субтитр
                </li>
            </ul>
            </p>

            <h4>Горячие клавиши Q , W, E</h4>
            <p>
            <ul>
                <li>
                    Q - предыдущее слово
                </li>
                <li>
                    W - следующее слово
                </li>
                <li>
                    E - повторить текущий слово
                </li>
            </ul>
            </p>

        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y") ?> Subtitle, Inc.</p>
    </footer>

</div> <!-- /container -->


</body>
</html>