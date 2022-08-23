<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); // подключаем пролог;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php") //подключаем хидер;
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

CJSCore::Init(array('ajax')); //подключение библиотеки BX JS с дополнительным расширением
$sidAjax = 'testAjax';
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){ //если получили Ajax запрос (с параметром ajax_form = testAjax)
    $GLOBALS['APPLICATION']->RestartBuffer();   // недокументированный метод, видимо очищает буфер чтобы не выводить header при ajax запросе
    echo CUtil::PhpToJSObject(array(            // отобразить заполненный PHP массив в виде массива JS
        'RESULT' => 'HELLO',
        'ERROR' => ''
    ));
    die();                                      // остановить выполнение скрипта
}

?>
<div class="group">
    <div id="block"></div >             <!-- блок block -->
    <div id="process">wait ... </div >  <!-- блок process -->
</div>
<script>
    window.BXDEBUG = true;
    function DEMOLoad(){        //функция Ajax запроса
        BX.hide(BX("block"));   // скрыть блок block
        BX.show(BX("process")); //отобразить блок process
        BX.ajax.loadJSON(       //сам Ajax запрос
            '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>', //адрес запроса
            DEMOResponse        //функция которой передать результат запроса
        );
    }
    function DEMOResponse (data){               // функция обработки результата Ajax зарпоса
        BX.debug('AJAX-DEMOResponse ', data);   // недокументировакнный метод, видимо логирование в консоль
        BX("block").innerHTML = data.RESULT;    // запись в block Ajax - ответа
        BX.show(BX("block"));                   // отобразить block
        BX.hide(BX("process"));                 // скрыть process

        BX.onCustomEvent(                       // вызвать кастомное событие DEMOUpdate для block
            BX(BX("block")),
            'DEMOUpdate'
        );
    }

    BX.ready(function(){
        /*
        BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){ //добавить кастомное событие DEMOUpdate для block
           window.location.href = window.location.href;
        });
        */
        BX.hide(BX("block"));           //скрыть блок block
        BX.hide(BX("process"));         //скрыть блок process

        BX.bindDelegate(                //установить обработчик события click для блока css_ajax
            document.body, 'click', {className: 'css_ajax' },
            function(e){
                if(!e)
                    e = window.event;
                DEMOLoad();              //запустить функцию DEMOLoad (Ajax запрос)
                return BX.PreventDefault(e); // недокументированный метод
            }
        );

    });

</script>
<div class="css_ajax">click Me</div> <!-- блок css_ajax по которому будем кликать -->
<?
// подключаем футер
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
