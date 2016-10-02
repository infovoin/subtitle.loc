function Video() {


//========== START: Описание всех "свойств" объекта и констант необходимых для работы объекта

    const YANDEX_DICTIONARY_API_KEY = "dict.1.1.20160607T212602Z.ba01c3b5d5234d8d.63907e476615958aa44a09c71b321263f3f4cb81";
    const COUNT_SUBTITLES_IN_ITERATION = 7;


    var self = this;
    this.path_to_video_file = null;

    this.ready_eng_sub = {}; //Здесь будут храниться уже обработанные Английские субтитры
    this.currentElementSubtitles = 0;

    //хранит массив слов которые пора повторить
    this.ready_repeat_s__words = {};
    this.currentRepeat_s__words = 0;

    this.current_word_yandex_dictionary_object = null;

    this.eventHotKey = '';

    //Флаг в значении true остановит субтитр после его кончания, например при повторе субтитра или при изучении слова.
    this.if_end_then_stop = false;

    this.video = document.getElementById('current_video');

    //Константы
    const NUMBER_SUBTITLE = 0; //номер субтитра из файла srt
    const START_SUBTITLE = 1; //время начала субтитра
    const END_SUBTITLE = 2; //время конца субтитра
    const TEXT_SUBTITLE = 3; //текст субтитра

    //HEARING
    const HEARING_DONT_TRY = 0;
    const HEARING_TRY = 1;


   const REGIM_SHOW = 0;
   const REGIM_REPEAT_SEEING = 1;
   const REGIM_REPEAT_HEARING = 2;
   this.regims_statys = REGIM_SHOW;

    //Хранит флаг выполнено ли добавление всех событий на плеер? операция должна выполниться не более 1 раза
    this.complete_add_all_events_on_player = false;
//========== END: Описание всех "свойств" объекта и констант необходимых для работы объекта


//========== START: Блок загрузки и обработки субтиров

    /**
     * Задача функции: принять субтитры и привести время в них к понятному для js формату и текст субтитров обернуть в <span></span>
     * @param id_textarea Передаём id элемента на странице который содержит текст субтитров
     */
    this.add_sub = function (id_textarea) {
        var regexp = /(\d+)\n([\d:,]+)\s+-{2}\>\s+([\d:,]+)\n([\s\S]*?(?=\n{2}|$))/ig;
        var one_subtitle;
        while (one_subtitle = regexp.exec($('#' + id_textarea).val())) {
            one_subtitle[2] = this.convert_time(one_subtitle[2]);
            one_subtitle[3] = this.convert_time(one_subtitle[3]);
            var span_one_subtitle = this.word_wrapper(one_subtitle[1], one_subtitle[4]);
            //эта строчка формирует массив с точно таким же порядком индексов как и субтитры, если 100 элемент взять получим 100 субтитр
            this.ready_eng_sub[one_subtitle[1]] = [one_subtitle[1], one_subtitle[2], one_subtitle[3], span_one_subtitle];
        }
        this.print_table_subtitles();
        this.add_all_events_on_player();

        this.saveFieldByAjax('original_eng_sub', $('#' + id_textarea).val());
        this.saveFieldByAjax('ready_eng_sub', JSON.stringify(self.ready_eng_sub));
    };

    //Преобразует время субтитра в милисекунды. Делим на тысячу что бы получить число секунд. 2.12312
    this.convert_time = function (time) {
        time = time.replace(',', '.');
        return Date.parse('1970-01-01T' + time + 'Z') / 1000;
    };

    /**
     * Функция разбивает строку на слова и оборачивает каждое в span class="word-wrapper"
     * @param text_subtitle
     * @returns {string}
     */
    this.word_wrapper = function (subtitle_number, text_subtitle) {
        var regexp = /([\w'])+/gi;

        var array_text_subtitle = text_subtitle.match(regexp);
        var future_text_subtitle = [];
        for (var w = 0; w < array_text_subtitle.length; w++) {
            future_text_subtitle[w] = '<span class="word-wrapper">' + array_text_subtitle[w] + '</span>';
        }
        return '<span data-subtitle_number="' + subtitle_number + '">' + future_text_subtitle.join(' ') + '</span>';
    };


    this.saveFieldByAjax = function (field, value) {
        $.ajax({
            type: "POST",
            url: 'http://subtitle.loc/Video/Save/' + window.location.search,
            data: {
                field: field,
                value: value
                /*video_name: $('#video_title').val(),  // text to translate
                 path_to_file: this.path_to_video_file,
                 ready_eng_sub: JSON.stringify(self.ready_eng_sub)*/
            }, success: function (data) {

            }, error: function (XMLHttpRequest, errorMsg, errorThrown) {
                console.log(errorMsg);
            }
        });
    };


    //Функция подгружает из базы уже обработанные субтитры
    this.load_subtitles = function () {
        $.ajax({
            type: "POST",
            url: 'http://subtitle.loc/Video/LoadSubtitles/' + window.location.search,
            success: function (data) {
                data = JSON.parse(data);
                //сохраняем данные в объект
                this.video_name = data.video_name;
                this.path_to_video_file = data.path_to_file;
                this.ready_eng_sub = JSON.parse(data.ready_eng_sub);

                //отображаем свойства объекта в вёрстке
                $('#video_name').text(this.video_name);
                $('#current_video').attr('src', this.path_to_video_file);

                self.print_table_subtitles();
                this.video.currentTime = data.bookmark;
                this.load_words();
                this.word_wrapper(1, 'Tell them, . .    . its , ,the,    wrong number and not to call again')
            }.bind(this), error: function () {

            }
        });
    };


//========== END: Блок загрузки и обработки субтиров


//========== END: Блок загрузки слов которые пора повторять

    //Функция подгружает из базы все слова которые пора повторить.
    this.load_words = function () {
        $.ajax({
            type: "POST",
            url: 'http://subtitle.loc/Dictionary/LoadWords/' + window.location.search,
            success: function (data) {
                data = JSON.parse(data);
                this.ready_repeat_s__words = data;
                $('.currentRepeat_s__words').text(this.currentRepeat_s__words);
                $('.ready_repeat_s__words').text(this.ready_repeat_s__words.length);

            }.bind(this), error: function () {

            }
        });
    };


//========== END: Блок загрузки и обработки субтиров


//========== Блок основных функций [Печать таблицы с субтитрами] []
    /**
     * Задача функции: распечатать таблицу субтитров
     */
    this.print_table_subtitles = function () {
        var subtitles_count = Object.keys(this.ready_eng_sub).length;
        for (var i = 0; i < subtitles_count; i++) {
            if (this.ready_eng_sub[i] !== undefined) {
                var number = this.ready_eng_sub[i][NUMBER_SUBTITLE];
                var start = this.ready_eng_sub[i][START_SUBTITLE];
                var text_subtitle = this.ready_eng_sub[i][TEXT_SUBTITLE];
                $('#table_offset').append('<tr id="' + number + '">' + '<td class="point">' + '' + '</td>' + '<td>' + number + '</td>' + '<td>' + start + '</td>' + '<td>' + text_subtitle + '</td>' + '</tr>');
            }
        }
    };

    /**
     * Что я жду от этой функции.
     * 1) Что она будет находить текущий субтитр:
     *      Если это первый запуск то должна определить стартовый субтитр
     *      Если была перемеотка то должна определить
     */
    this.findCurrentElementSubtitles = function () {
        //console.log('findCurrentElementSubtitles');
        //var startTime = (new Date()).getTime();

        var subtitles_count = Object.keys(this.ready_eng_sub).length;
        //console.log('Количество субтитров: '+subtitles_count);
        for (var i = 0; i < subtitles_count; i++) {

            if (this.ready_eng_sub[i] !== undefined) {
                //Берём поочерёдно время начало и время конца каждого субтитра.
                var start = this.ready_eng_sub[i][START_SUBTITLE];
                var end = this.ready_eng_sub[i][END_SUBTITLE];


                //И проверяем текущее время на видео находится между временем начала и конца субтитра?
                var if_currentTime_between_start_end = (this.video.currentTime >= start) && (this.video.currentTime <= end);

                //проверяем больше ли время начала рассматриваемого субтитра чем текущее время на видео? если да то берём этот субтитр (например инициализация первого кадра)
                var or_currentTime_less_start = this.video.currentTime <= start;

                if (if_currentTime_between_start_end || or_currentTime_less_start) {
                    this.currentElementSubtitles = this.ready_eng_sub[i][NUMBER_SUBTITLE];

                    $('#currentNumberSubtitles').text(this.currentElementSubtitles);
                    //console.log('нашёл текущий субтитр ' + this.currentElementSubtitles);
                    this.neon_and_scroll();
                    break;
                }
            }
        }
        //var endTime = (new Date()).getTime() - startTime;
        //console.log(endTime);
    };

    /**
     * show_subtitle вызов этой функции происходит каждые 100 милисек.
     * Отрабатывает она только в том случаи если видео не стоит на пазе, то есть идёт просмотр, и секудны тикают
     * Задача функции: во время отображать текущий субтитр и когда его время закончиться переключать индикатор на следующий.
     */
    this.show_subtitle = function () {
        if (this.video.paused == true) {
            return;
        }
        $('#video_currentTime').text(this.video.currentTime.toFixed(3));

        var start = this.ready_eng_sub[this.currentElementSubtitles][START_SUBTITLE];
        var end = this.ready_eng_sub[this.currentElementSubtitles][END_SUBTITLE];
        var text_subtitle = this.ready_eng_sub[this.currentElementSubtitles][TEXT_SUBTITLE];

        // ovarlap_text_subtitle (boolean) - совпадение текста субтитра
        //var overlap_text_subtitle = $('#place_for_subtitle').html() == text_subtitle;
        var data_subtitle_number = $('#place_for_subtitle span:first').attr('data-subtitle_number');
        var overlap_text_subtitle = data_subtitle_number == this.currentElementSubtitles;

        if (this.video.currentTime >= start && this.video.currentTime <= end && overlap_text_subtitle === false) {
            if(this.regims_statys != REGIM_REPEAT_HEARING) {
                $('#place_for_subtitle').html(text_subtitle);
            } else {
                $('#place_for_subtitle').html(this.hearing(text_subtitle, data_subtitle_number));
            }
        } else if ((this.video.currentTime > end)) {

            //Если флаг то останавливаем просмотр видео и возвращаем return что бы выйти из функции
            if(self.if_end_then_stop){
                self.video.pause();
                this.if_end_then_stop = false;
                return;
            }


            if (this.regims_statys == REGIM_SHOW) {
                $('#place_for_subtitle').html('');
            } else if (this.regims_statys == REGIM_REPEAT_SEEING) {
                this.repeat_iteration();
                this.currentElementSubtitles++;
                $('#currentNumberSubtitles').text(this.currentElementSubtitles);
                this.neon_and_scroll();
                this.video.pause();
                return;
            } else if (this.regims_statys == REGIM_REPEAT_HEARING){
                //Если мы п режиме HEARING то после окончания времени субтитра останавливаем но не зачищаем наши точки...
                this.video.pause();
                return;
            }
            //вызываем функцию которая проверяет прошли ли мы должное количество субтитров 20 штук. Что бы вернётсья обратно.
            this.repeat_iteration();

            this.currentElementSubtitles++;
            //
            $('#currentNumberSubtitles').text(this.currentElementSubtitles);
            //
            this.neon_and_scroll();
        }
    };


    //Клавиша "Y" - предыдущий субтитр
    this.previous_subtitle = function () {
        if (this.ready_eng_sub[this.currentElementSubtitles - 1] !== undefined) {
            this.currentElementSubtitles--;
        }
        $('#currentNumberSubtitles').text(this.currentElementSubtitles);

        this.video.currentTime = this.ready_eng_sub[this.currentElementSubtitles][START_SUBTITLE];

        this.show_subtitle();
        this.neon_and_scroll();
    };

    this.repeat_current_subtitle = function () {
        this.video.currentTime = this.ready_eng_sub[this.currentElementSubtitles][START_SUBTITLE];
        this.if_end_then_stop = true;
        this.show_subtitle();
        this.neon_and_scroll();
    };

    //клавиша "U" - следующий субтитр
    this.next_subtitle = function () {
        this.currentElementSubtitles++;

        $('#currentNumberSubtitles').text(this.currentElementSubtitles);

        this.video.currentTime = this.ready_eng_sub[this.currentElementSubtitles][START_SUBTITLE];
        this.show_subtitle();
        this.neon_and_scroll();
    };

    //старт и пауза
    this.play_pause = function () {
        if (this.video.paused == true) {
            this.video.play();
            //this.video.controls = false;
        } else {
            this.video.pause();
            $("#current_video").attr("controls", "");
            //this.video.controls = true;
        }
    };

    this.previous_repeat_s__words = function () {
        //Есть ли шаг назад для указателя на текущем месте в нашей коолекции слов
        if (this.ready_repeat_s__words[this.currentRepeat_s__words - 1] !== undefined) {
            $('#addSentence').css('display', 'none');
            //делаем шаг назад указателем в коллекции
            this.currentRepeat_s__words--;
            $('.currentRepeat_s__words').text(this.currentRepeat_s__words+1);
        }
        this.if_end_then_stop = true;
        this.show_sentence_from_my_dictionary();
    };

    this.repeat_current_repeat_s__words = function () {
        this.if_end_then_stop = true;
        this.show_sentence_from_my_dictionary();
    };

    this.next_repeat_s__words = function () {

        //if (this.currentRepeat_s__words < Object.keys(this.ready_repeat_s__words).length) {
        if (this.ready_repeat_s__words[this.currentRepeat_s__words + 1] !== undefined) {
            $('#addSentence').css('display', 'none');
            this.currentRepeat_s__words++;
            $('.currentRepeat_s__words').text(this.currentRepeat_s__words+1);
        }
        this.if_end_then_stop = true;
        this.show_sentence_from_my_dictionary();

    };

    this.previous_phrase = function () {

    };

    this.repeat_current_phrase = function () {

    };

    this.next_phrase = function () {

    };

    this.repeat_current_phrase = function () {

    };

    this.previous_phrase = function () {

    };


    this.previous_question = function () {

    };

    this.repeat_current_question = function () {

    };

    this.previous_question = function () {

    };


    this.show_sentence_from_my_dictionary = function () {
        if(undefined == this.ready_repeat_s__words[this.currentRepeat_s__words]){
            return;
        }

        //вычисляем какой субтитр надо выложить
        this.currentElementSubtitles = this.ready_repeat_s__words[this.currentRepeat_s__words].subtitle_id;

        //Перематываем на этот субтитр
        this.video.currentTime = this.ready_eng_sub[this.currentElementSubtitles][START_SUBTITLE];
        //Отображаем
        this.show_subtitle();
        //Подсвечиваем и подкручиваем в списке субтитров
        this.neon_and_scroll();

        //вычисляем вводили ли мы какое то значение или нет
        /*
         1) Выясняем есть ли у этого "слова" свойство next_time если есть берём оттуда значение и вставляем в инпут который летит следом после предложения,
         если нет то инпут будет пустой
         */
        $('#next_time').val(self.ready_repeat_s__words[self.currentRepeat_s__words].next_time || '');
        $('#send_next_time').attr('data-id_my_dictionary', self.ready_repeat_s__words[self.currentRepeat_s__words].id);



        //А теперь заменяем на идентичное предложение но с меткой.
        $('#place_for_subtitle').html(self.ready_repeat_s__words[self.currentRepeat_s__words].sentence);
    };


    /**
     * Задача функции: подсветить текущий субтитр из общего списка, и сфокусироваться на нём по номеру id="10"
     */
    this.neon_and_scroll = function () {
        $('tr').removeClass('success');
        var current_tr = $('#' + this.currentElementSubtitles);
        current_tr.addClass('success');

        //скрол
        var container = $('#div_table_subtitles'),
            scrollTo = current_tr;

        container.animate({
            scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
        }, 300);
    };


//========== START: Блок описания функции работы с переводом слова


    //Это ключ для yandex translate
    //trnsl.1.1.20160605T185607Z.76cfe7b88c3d8ad6.d6dd57ebc33f14bd61482fc4862350fe76c48b6a

    //Это ключ для yandex словарь API
    //dict.1.1.20160607T212602Z.ba01c3b5d5234d8d.63907e476615958aa44a09c71b321263f3f4cb81

    this.translate = function (text, select_language, translate_language, success) {
        $.ajax({
            type: "POST",
            url: 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key=' + YANDEX_DICTIONARY_API_KEY,
            data: {
                text: text,  // text to translate
                lang: 'en-ru',
                ui: 'ru'
            }, success: function (result) {
                success(result);
                self.current_word_yandex_dictionary_object = result;
                //console.log(result);
            }, error: function (XMLHttpRequest, errorMsg, errorThrown) {
                console.log(errorMsg);
            }
        });
    };

    /** эта функция парсит тяжёлый JSON объект с большим количеством вложенных массив. */
    this.print_info_about_word = function (word_info) {
        var result = '';

        //первый цикл перебирающий массивы (существительных, прилагательных и т.д.)
        for (var a = 0; a < word_info.length; a++) {
            result += '<span class="text-primary word_header">' + word_info[a].text + '</span> <span class="text-success">' + word_info[a].pos + '</span> <span class="text-info word_header">[' + word_info[a].ts + ']</span><br>';
            for (var b = 0; b < word_info[a].tr.length; b++) {
                result += ' <span class = "chose_word_translate">' + word_info[a].tr[b].text + '</span> /';
            }
            //slice обрезаем последним лишний слеш
            result = result.slice(0, -1);
            result += '<br><br>';
        }
        return result;
    };

    //Добавляет заклаку на время в видео проигрывателе
    this.bookmark = function () {
        //здесь берётся текущее время и отсылается в базу.
        $.ajax({
            type: "POST",
            url: 'http://subtitle.loc/Video/AddBookmark/' + window.location.search,
            data: {
                bookmark: self.video.currentTime
            }, success: function (data) {

            }, error: function () {
            }
        });
    };

    this.repeat_iteration = function () {
        if (0 == (this.currentElementSubtitles % COUNT_SUBTITLES_IN_ITERATION)) {

            switch (this.regims_statys) {
                //Если мы посмотрели 20 субтитров в режиме "просмотра", предлагаем повторить их ((и переводим на следующий режим))
                case REGIM_SHOW:
                    $('#place_for_subtitle').html('<span>Повторить итерацию? [ '+COUNT_SUBTITLES_IN_ITERATION+' субтитров] <a class="yes_repeat_iteration">Да</a> / <a class="not_repeat_iteration">Нет</a></span>');
                    this.video.pause();
                    break;
                //Если мы посмотрели 20 субтитров в режиме "повторения" предлагаем на слух их определить (и переводим на следующий режим)
                case REGIM_REPEAT_SEEING:
                    $('#place_for_subtitle').html('<span>Потренируем слух? [ '+COUNT_SUBTITLES_IN_ITERATION+' субтитров] <a class="yes_repeat_iteration">Да</a> / <a class="not_repeat_iteration">Нет</a></span>');
                    this.video.pause();
                    break;
                //Если мы посмотрели 20 субтитров в режиме "на слух" то автоматически продолжаем просмотрв видео (и переводим на следующий режим)
                case REGIM_REPEAT_HEARING:
                    //После прохождения 20 субтитров в режиме REGIM_REPEAT_HEARING мы возвращаемся на режим REGIM_SHOW;
                    this.regims_statys = REGIM_SHOW;
                    self.removeEventHotKeyHearing();
                    self.createEventHotKey();
                    break;


            }
        }
    };

//========== END: Блок описания функции работы с переводом слова


//========== START: Блок описания всех слушателе событий

    this.add_all_events_on_player = function () {
        /*//Если события уже навешивались 1 раз то больше не навешиваем
         if(this.complete_add_all_events_on_player === true){
         return;
         }
         this.complete_add_all_events_on_player = true;*/

        //Событие нажатия на "слово" в субтитре
        $(document).on('click', 'span.word-wrapper', function () {
            //если нажали на слово, останавливаем просмотр видео
            self.video.pause();

            //Очищаем input от прошлого открытия окна в поле "перевод слова"
            $('#translate_word').val('');

            //Проверяем если подсветка у слова уже была это значит что слово доставленно из словаря и в модальное окно надо отобразить сохранённые данные.
            if ($(this).hasClass('neon-word-wrapper')) {

                //Вставляем перевод слова.
                $('#translate_word').val(self.ready_repeat_s__words[self.currentRepeat_s__words].translate);
            }

            //делаем подсветку слова постоянно а не только при наведении
            $('span').removeClass('neon-word-wrapper');
            $(this).addClass('neon-word-wrapper');


            //Копируем всё предложение прям из области субтитров в модальное окно
            $('#modal_place_for_subtitle').html($(this).parent('span').clone());

            //регулярным выражением из кликнутой области вычлиняем только лишь слово без запятых "sleep," и прочих знаков .?! и т.д.
            var regexp = /[\w']+/g;
            word = regexp.exec($(this).text());

            //вставляем это слово как значение input (оригинал "иностранное слово")
            $('#original_word').val(word);

            //Отправляем слово на перевод в Yandex Dictionary API
            self.translate(word[0], 'en', 'ru', function (response) {

                //получив ответ от сервиса очищаем модальное окно от предыдущих похожих операций
                $('#word_desctiption').empty();

                //и распечатываем ответ сервиса в модальном окне
                $('#word_desctiption').append(self.print_info_about_word(response.def));

                //отображаем модальное окно
                $('#addSentence').css('display', 'block');

            });
        });

        //Событие нажатия на любой из вариантов перевода. (по нажатию на слово оно должно вставиться в поле)
        $(document).on('click', 'span.chose_word_translate', function () {
            $('#translate_word').val($(this).text());
        });

        //на кнопку close закрываем окно добавления слова.
        $(document).on('click', '.close_add_me', function () {
            $('#addSentence').css('display', 'none');
        });

        //в модальном окне на кнопку Add my Dictionary вешаем событие нажатия
        $(document).on('click', '.add_me', function () {

            //очищаем область модального окна от предыдущих операций (тех ситуаций когда поле было пусто и операция выдаёт ошибку что незаполено)
            $('#div_for_input_translate_word').removeClass('has-error');
            $('#lable_option_translate').text('Перевод: ');
            $('span#lower_entry').text('');


            if ($('#translate_word').val() == '') {
                $('#div_for_input_translate_word').addClass('has-error');
                $('#lable_option_translate').text('Не заполнен перевод');
                $('span#lower_entry').text('Выберете из имеющихся или напишите свой');
                return;
            }


            //Здесь AJAX запрос на отправку данных в таблицу myDictionary
            $.ajax({
                type: "POST",
                url: 'http://subtitle.loc/Dictionary/Add/' + window.location.search,
                data: {
                    subtitle_id: $('#modal_place_for_subtitle span:first').attr('data-subtitle_number'),
                    sentence: $('#modal_place_for_subtitle').html(),
                    word: $('#original_word').val(),
                    translate: $('#translate_word').val(),
                }, success: function (result) {
                    $('#addSentence').css('display', 'none');
                    if(result){
                        setTimeout(function(){self.load_words()}, 2000);
                    }
                }, error: function () {

                }
            });
        });

        //событие закрытия модального окна, очищаем.
        $('#addSentence').on('hidden.bs.modal', function () {
            $('#div_for_input_translate_word').removeClass('has-error');
            $('#lable_option_translate').text('Перевод: ');
            $('span#lower_entry').text('');
        });


        //========== События управляющие процессом просмотра видео, перемотки и отображения субтитров

        //Активируем функционал нажатия на горячие клавиши
        this.createEventHotKey();
        //this.createEventHotKeyHearing();
        //Найти текущий субтитр
        this.findCurrentElementSubtitles();

        //Каждые 100 милисекунд проверяет какой субтитр сейчас отображать.
        setInterval(function () {
            this.show_subtitle()
        }.bind(this), 100);

        //Отрабатывает, когда операция поиска завершена. (в ситуации перемотки когда мы отпустили мыш)
        this.video.addEventListener('play', function () {
            this.findCurrentElementSubtitles();
        }.bind(this));


        //После перемотки сразу же запускаем плеер.
        // И тогда у нас сразуже сработает поиск текущего элемента, и это занимает в среднем 7 (и так сделано в puzzle english)
        this.video.addEventListener('seeked', function () {
            this.video.play();
        }.bind(this));


        $(document).on('focus', 'input', function () {
            //удаляем события нажатия на горячие клавишы Y U и т.д. что бы можно было спокойно нажимать на эти буквы работая с полем в форме.
            console.log('фокус на поле, удаляем события');
            self.removeEventHotKey();
        });

        $(document).on('blur', 'input', function () {
            console.log('разфокуссировались, создаём события');
            self.createEventHotKey();
        });

        //Событие на нажатие да повторить.
        $(document).on('click', '.yes_repeat_iteration', function () {
            self.currentElementSubtitles -= COUNT_SUBTITLES_IN_ITERATION;
            console.log(self.ready_eng_sub);
            console.log(self.currentElementSubtitles);
            console.log(self.ready_eng_sub[self.currentElementSubtitles]);
            self.video.currentTime = self.ready_eng_sub[self.currentElementSubtitles][START_SUBTITLE];
            $('#place_for_subtitle').html('');

            //Переключаем режим режим в зависимости от стадии просмотра партии из 20 субтитров (просмотр, повторный просмотр, распознать на слух)
            switch (self.regims_statys) {
                //Если мы посмотрели 20 субтитров в режиме "просмотра", предлагаем повторить их ((и переводим на следующий режим))
                case REGIM_SHOW:
                    self.regims_statys = REGIM_REPEAT_SEEING;
                    break;
                case REGIM_REPEAT_SEEING:
                    self.regims_statys = REGIM_REPEAT_HEARING;
                    self.removeEventHotKey();
                    self.createEventHotKeyHearing();
                    //запускаем режим преобразования букв в точки
                    break;
            }

            self.video.play();
        });
        //Событие на нажатие "Нет не повторять"
        $(document).on('click', '.not_repeat_iteration', function () {
            self.video.play();
        });

        //Обновляем следующее время для повторения у слова.
        $(document).on('click', '#send_next_time', function () {
            var next_time = $('#next_time').val();

            $.ajax({
                type: "POST",
                url: 'http://subtitle.loc/Dictionary/UpdateNextTime/?id=' + $('#send_next_time').attr('data-id_my_dictionary'),
                data: {
                    next_time: next_time
                }, success: function (data) {
                    self.ready_repeat_s__words[self.currentRepeat_s__words].next_time = next_time;
                    self.video.play();
                }, error: function (XMLHttpRequest, errorMsg, errorThrown) {
                    console.log(errorMsg);
                }
            });
        });
    };

//========== END: Блок описания всех слушателе событий


//========== START: Блок работы с модулем hearing (где слова закодированны в точки)

    this.hearing = function(text_subtitle){

        var regexp = /([\w'])+/gi;

        //номер субтитра нужен для того что бы при интервально выполняемой функции
        var data_subtitle_number =$(text_subtitle, 'span:first').attr('data-subtitle_number');

        var array_text_subtitle = $(text_subtitle).text().match(regexp);
        var future_text_subtitle = [];
        for (var w = 0; w < array_text_subtitle.length; w++) {

            //переводим каждую букву в точку
            var converting_leter_to_dot = '';
            var first_leter = '';

            for(l = 0; l < array_text_subtitle[w].length; l++){
                if(0 == l){
                    first_leter = array_text_subtitle[w][l];
                }
                converting_leter_to_dot += '.';
            }

            future_text_subtitle[w] = '<span class="" data-word="'+array_text_subtitle[w]+'" data-first-leter="'+first_leter+'" data-trying="'+HEARING_DONT_TRY+'">' + converting_leter_to_dot + '</span>';
        }
        return '<span data-subtitle_number="' + data_subtitle_number + '">' + future_text_subtitle.join('&nbsp;&nbsp;&nbsp;&nbsp;') + '</span>';
    };

    this.hearing_checking = function(key){

        //Приводим к маленькому регистру
        var written_key = key.toLowerCase();

        var checking_word = $('#place_for_subtitle span[data-trying=0]:first');


        if(!checking_word.attr('data-first-leter')){
            //каждый раз когда заканчиваем набор субтитра проверяем был ли это кратный 20 субтитр что бы взять следующую пачку из 20 субтитров
            this.repeat_iteration();
            this.next_subtitle();
            return;
        }

        var cheking_key = checking_word.attr('data-first-leter').toLowerCase();

        //1) Проверка на соответствие нажатию (клавиша состоящая из одного символа цифры или буквы "не Backspace" так как много букв содержиат)
        //2) Подпадает под диапазон регулярки не возвращает -1 (-1 значит что совпадений нет)
        if(key.length == 1 && key.search(/\d|[a-z]/) != -1){
            if (written_key == cheking_key) {    //Изменение span с ... в случаи правильно подобранной буквы

                //1 Меняем статус на проверенна
                $(checking_word).attr('data-trying', HEARING_TRY);
                //2 Заменяем точки на символы
                $(checking_word).text($(checking_word).attr('data-word'));
            } else {
                //Меняем статус на проверенна
                $(checking_word).attr('data-trying', HEARING_TRY);
                $(checking_word).before('<span class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;'+written_key+'\\</span>');
                $(checking_word).text($(checking_word).attr('data-word')).addClass('text-muted');
            }
        }
    };

//========== END: Блок работы с модулем hearing (где слова закодированны в точки)



//========== START: События нажатия на "горячие клавиши" [Y, U ...]

    //Блок хоткеев начался
    this.HandlerEventHotKey = function (event) {
        var key = event.key;
        console.log(key.toUpperCase());
        switch (key.toUpperCase()) {
            case ' ':
                event.preventDefault();
                self.play_pause();
                break;
            case 'DELETE':
                self.delete_word();
                break;
            case 'Q':
                self.previous_repeat_s__words();
                break;
            case 'W':
                event.preventDefault();
                self.repeat_current_repeat_s__words();
                break;
            case 'E':
                self.next_repeat_s__words();
                break;
            case 'H':
                event.preventDefault();
                if($('#addSentence').css('display') == 'none'){
                    $('.neon-word-wrapper').click();
                    $('#translate_word').val(self.ready_repeat_s__words[self.currentRepeat_s__words].translate);
                } else {
                    $('#addSentence').css('display', 'none');
                }
                break;
            case 'A':
                self.previous_subtitle();
                break;
            case 'S':
                event.preventDefault();
                self.repeat_current_subtitle();
                break;
            case 'D':
                self.next_subtitle();
                break;
            case 'V':
                self.bookmark();
                break;
            case 'T':
                event.preventDefault();
                $("#next_time").focus();
                self.createEventHotKeyWordNextTime();
                break;


        }

    };




    //repeat words
    this.HandlerEventHotKey2 = function (event) {
        var key = event.key;
        switch (key.toUpperCase()) {
            case 'ENTER':
                event.preventDefault();
                $("#send_next_time").click();
                $("input#next_time").blur();
                self.removeEventLHotKeyWordNextTime();
                break;
            case 'ESCAPE':
                event.preventDefault();
                $("input#next_time").blur();
                self.removeEventLHotKeyWordNextTime();
                break;
        }

    };

    //it is "Hearing" process
    this.HandlerEventHotKey3 = function (event){

        var key = event.key;
        switch (key.toUpperCase()) {
            case ' ':
                event.preventDefault();
                self.repeat_current_subtitle();
                break;
            default:
                self.hearing_checking(event.key);
                break;
        }
    };




    //метод создающий событие нажатия на горячие клавиши
    this.createEventHotKey = function () {
        window.addEventListener('keydown', this.HandlerEventHotKey);
    };

    //метод удаляющий события нажатия на горячие клавиши
    this.removeEventHotKey = function () {
        window.removeEventListener('keydown', this.HandlerEventHotKey);
    };

    //создание обработчика событий для поля задающего промежуток времени для показа
    this.createEventHotKeyWordNextTime = function (){
        window.addEventListener('keydown', this.HandlerEventHotKey2);
    };


    //создание обработчика событий для поля задающего промежуток времени для показа
    this.removeEventLHotKeyWordNextTime = function (){
        window.removeEventListener('keydown', this.HandlerEventHotKey2);
    };

    this.createEventHotKeyHearing = function (){
        window.addEventListener('keydown', this.HandlerEventHotKey3);
    };

    this.removeEventHotKeyHearing = function(){
        window.removeEventListener('keydown', this.HandlerEventHotKey3);
    };

//=========== Самая опасная функция. Удаление видео.
    this.deleteVideo = function () {
        $.ajax({
            type: "POST",
            url: 'http://subtitle.loc/Video/Delete/' + window.location.search,
            data: {}, success: function (data) {
                window.location.href = window.location.origin;
            }, error: function (XMLHttpRequest, errorMsg, errorThrown) {
                console.log(errorMsg);
            }
        });
    };

    // ========= Удаление слова из словаря

    this.delete_word = function () {
        console.log('нажали delete');
        var id = $('#place_for_subtitle').find('button').attr('data-id_my_dictionary') || false;
        if (id) {
            console.log('удаляем слово');
            $.ajax({
                type: "POST",
                url: 'http://subtitle.loc/Dictionary/Delete/?id=' + id,
                data: {}, success: function (data) {

                }, error: function (XMLHttpRequest, errorMsg, errorThrown) {
                    console.log(errorMsg);
                }
            });
        }
    };
}