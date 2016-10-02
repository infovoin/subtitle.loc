$(document).ready(function () {

    var player = new Video();
    var event_for_2_step = false;





//--------------- STEP 1 open STEP 2 ---------------
    //При открытии страницы /video/edit это поле вероятно будет заполнено тогда открываем второй шаг
    if ($('#video_title').val() != '') {
        showStep2();
    }

    $('#video_title').on('input', function () {
        showStep2();
    });

    //Отправляем AJAX в данном случаи как только заполнили имя
    $('#video_title').on('blur', function () {
        player.saveFieldByAjax('video_name' , $('#video_title').val());
    });

//--------------- STEP 2 open STEP 3 ---------------



    $('#path_to_video_file').on('input', function () {
        showStep3();
    });

    if($('#path_to_video_file').val()){
        showStep3();
    }



//--------------- STEP 3 open STEP 4 -------------------

    //Вешаем событие для второго шага
    $('body').on('click', '#add_eng_sub', function () {
        showStep4();
    });

    if ($('#add_eng_sub').val() != '') {
        showStep4();
    }



    //--------------- DELETE VIDEO ------------------
    $('body').on('click', '#delete_video',function(){
        player.deleteVideo();
    });


    //============= Функции STEP действия на каждый шаг в добавлении видео и в редактировании.

    function showStep2() {
        $('#step1-2').css('display', 'block');
    }

    function showStep3(){
        $('#current_video').attr('src', $('#path_to_video_file').val());
        $('#current_video').attr('autoplay', 'autoplay');
        console.log($('#current_video').val());
        setTimeout(function () {
            player.video.pause();
            if (player.video.duration > 0) {

                $('#current_video').css('display', 'block');
                $('#place_for_subtitle').css('display', 'block');
                $('#step2-2').css('display', 'block');
                $('html, body').animate({scrollTop: $('#current_video').position().top}, 1000);
                video_attach(true);
                //сохраняем путь до файла в базу
                player.path_to_video_file = $('#path_to_video_file').val();
                player.saveFieldByAjax('path_to_file', player.path_to_video_file);
            } else {
                video_attach(false);
            }
        }, 1500);
    }

    function showStep4() {
        player.add_sub('text_eng_subs');

        if (event_for_2_step == false) {
            event_for_2_step = true;
            // Перемещаем параграф из #myDiv1 в #myDiv2
            $('#step3').append($('#step2-1'));
            $('#div_table_subtitles').css('display', 'block');

        }
    }


    function video_attach(video_attach_status) {

        //Очищаем области в независимости от того что там было до этого.
        $('#div_for_path_to_video_file').removeClass();

        if (video_attach_status == true) {

            $('#div_for_path_to_video_file').addClass('form-group has-success');
            $('#label_path_to_video_file').text('Всё окей');
            $('#span_path_to_video_file').text('Теперь можете перейти к следующему шагу');

        } else {

            $('#div_for_path_to_video_file').addClass('form-group has-error');
            $('#label_path_to_video_file').text('Файл не подгружен');
            $('#span_path_to_video_file').text('Возможно не поддерживаемый браузером формат видео');

        }
    }

});