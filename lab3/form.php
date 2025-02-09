<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sauron</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <link rel="icon" href="око1.ico" type="image/x-icon">
    <link rel="stylesheet" href="MyProjects/Project1/static/styles/style.css">

</head>
<body>
    <div class="container-fluid page px-sm-0">
        <div class="row d-flex mx-sm-0 mb-sm-2">
            <header>
                <div id="fir">
                    <img id="logo" src="MyProjects/Project1/static/images/око.jpg" alt="Логотип" />
                    <h1 class="name">SAURON</h1>
                </div>
            </header>

            <div class="container-fluid mt-3 mb-sm-2 px-0 mx-sm-2">
                <nav class="">
                    <ul class="px-3 mx-sm-2">
                        <li> <a class="px-sm-2" href="rings.html">Кольца </a></li>
                        <li> <a class="px-sm-2" href="followers.html"> Последователи </a></li>
                        <li> <a class="px-sm-2" href="bio.html"> Биография</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="content container-fluid mt-sm-0">
                <div class ="ns container-fluid mt-sm-0">
                    <div class="row d-flex">
                        <h1 class="rediv container-fluid"> Sauron Redivivus</h1>
                        <p class="first_t px-sm-3"> Есть только один властелин кольца, он один способен подчинять его себе. И он не делится властью.</p>
                        <div class="lnks order-2 order-sm-1 col-md-6">
                            <h2> Маркированный список гиперссылок</h2>
                            <ul>
                    
                                <li> <a href="http://kubsu.ru/" target="_blank">Абсолютная гиперссылка на главную страницу сайта kubsu.ru</a> </li>
                                <li> <a href="https://kubsu.ru/" target="_blank">Абсолютная гиперссылка на главную сайта kubsu.ru в протоколе https;</a></li>
                                <li> <a href="https://kubsu.ru/"> 
                                    <img class="px-sm-2 pt-sm-2 pb-sm-2" src="MyProjects/Project1/static/images/саурон.jpg" alt="саурон" style width="200"></a></li>
                                <li> <a href="/Lab1/MyProjects/Project1/about/p10.html" target="_blank"> Сокращенная ссылка на внутреннюю страницу;</a></li>
                                <li> <a href="/Lab1/MyProjects/Project1/index.html" target="_blank"> Сокращенная ссылку на главную страницу;</a></li>
                                <li><a href="#Form">Ссылка на форму</a></li>
                                <li> <a href="http://example.com/search?query=books&sort=asc&page=2" target="_blank">Ссылка с тремя параметрами в URL:http://example.com/search?query=books&sort=asc&page=2;</a></li>
                                <li> <a href="https://www.example.com/products?cat=electronics&id=456" target="_blank"> Ссылка с параметром id: https://www.example.com/products?cat=electronics&id=456;</a></li>
                                <li> <a href="./p9.html" target="_blank"> Относительная ссылка на страницу в текущем каталоге></a></li>
                                <li> <a href="./about/p10.html" target="_blank">Относительная ссылка в каталоге about> </a></li>
                                <li> <a href="../p11.html" target="_blank"> Относительная ссылка на страницу в каталоге уровнем выше текущего</a></li>
                                <li> <a href="../../p12.html" target="_blank"> Относительная ссылка на страницу в каталоге на два уровня выше текущего</a></li>
                                <li> <p> Для получения доп информации перейдите на <a href="https://kubsu.ru/"> веб-сайт</a></p></li>
                                <li> <a href="https://ru.wikipedia.org/wiki/HTML#:~:text=23%5D.-,%D0%A1%D1%82%D1%80%D1%83%D0%BA%D1%82%D1%83%D1%80%D0%B0%20HTML%2D%D0%B4%D0%BE%D0%BA%D1%83%D0%BC%D0%B5%D0%BD%D1%82%D0%B0,-%5B%D0%BF%D1%80%D0%B0%D0%B2%D0%B8%D1%82%D1%8C%20%7C">Перейти к этому разделу</a></li>
                                <li> <map name="knopka">
                                <area shape="rect" coords="0,0,109,56" href="https://kubsu.ru/" alt="Подсказка">                            <area shape="circle" coords="221,32,20" href="https://ru.wikipedia.org/wiki/HTML#:~:text=23%5D.-,Структура%20HTML-документа,-%5Bправить%20%7C/" alt="Подсказка">
                                    </map>
                                    <img class="sau px-sm-2 pt-sm-2 pb-sm-2" src="MyProjects/Project1/static/images/ss1.jpg" alt="Кнопка" usemap="#knopka">
                                </li>
                                <li> <a href="">Пустой href</a></li>
                                <li> <a id="ex"> https://kubsu.ru/</a></li>
                                <li> <a href="index.html" target="_blank" rel="nofollow"> Запрет  поисковикам</a></li>
                                <li><a href="index.html" target="_blank" rel="noindex">ссылка запрещенная для индексации поисковиками</a></li>
                                <li><!--noindex--> часть страницы, индексирования которой нужно запретить<!--/noindex--></li>
                                <li><ol>
                                        <li><a href="https://kubsu.ru/" title="Кубгу"> Cсылка1</a></li>
                                        <li><a href="https://kubsu.ru/" title="Кубгу"> Ссылка2</a></li>
                                        <li><a href="https://kubsu.ru/" title="Кубгу"> Ссылка3</a></li>
                                    </ol>
                                </li> 
                                <li> <a href="ftp://user:pass123@tavalik.ru/Temp/file.txt" target="_blank"> ссылкa на файл на сервере FTP с авторизацией;</a></li>
                            </ul>
                        </div>
                        <div class="t1 order-1 order-sm-2 px-sm-0 col-md-6">
                                <h2 class="px-sm-3"> Таблица</h2>
                                    <table class="t row d-flex">
                                        <tr class="nametb px-sm-2 pt-sm-2 pb-sm-2">
                                        <th>Имена</th>
                                        <th>Имена/Прозвища</th>
                                        <th> Прозвища</th>
                                        <th> Титулы </th>
                                        </tr>
                                        <tr class="tb">
                                        <td  colspan="4">Равнозначно великий и ужасный</td>
                                        </tr>
                                        <tr>
                                        <td>Майрон ― настоящее имя Саурона. С квенья переводилось как «Восхитительный». </td>
                                        <td>Саурон ― имя, с квенья переводящееся как «Отвратительный».</td>
                                        <td> Властелин Колец ― прозвище Саурона, полученное им после создания Единого Кольца. </td>
                                        <td>Наместник Мелькора ― звание Саурона при Мелькоре.</td>
                                        </tr>
                                        <tr class="tb">
                                            <td> Аулендиль ― имя, которым Саурон назывался в Эрегионе. С квенья переводилось как «Слуга Аулэ»</td>
                                            <td> Саурон-Лжец ― прозвище, которое дал Саурону в Нуменоре Амандиль</td>
                                            <td>Зигур ― прозвище, которое Саурон получил от нуменорцев; с адунаика переводится как «Чародей» </td>
                                            <td>Тёмный Властелин ― титул Саурона, наследованный у Мелькора/Моргота, первого Тёмного Властелина в Арде;</td>
                                        </tr>
                                        <tr >
                                            <td>Артано ― имя, которым Саурон назывался в Эрегионе. С квенья переводилось как «Благородный Кузнец»</td>
                                            <td>Тень ― прозвище Саурона, возникшее из-за ассоциации с тенью.</td>
                                            <td>Некромант ― прозвище Саурона, полученное в годы пребывания в Дол-Гулдуре</td>
                                            <td>Король Людей и Властелин Земли ― титулы, взятые Сауроном в годы владычества с Средиземье во Вторую Эпоху.</td>
                                        </tr>
                                        <tr class="tb">
                                            <td>Аннатар ― имя, которым Саурон назывался в Эрегионе. С квенья переводилось как «Владыка Даров»</td>
                                            <td>Саурон Великий ― прозвище, которым нарёк Саурона Гэндальф в разговоре с Фродо;</td>
                                            <td>Волк-Саурон ― прозвище Саурона, полученное им в бою с Хуаном.</td>
                                            <td>Властелин Мордора ― титул Саурона как владыки земель Мордора.</td>
                                        </tr>
                                        <tr>
                                            <td>Гортаур ― имя, с синдарина содержащее корень Þaur (Отвратительный) и приставку gor (Ужас).</td>
                                            <td>Враг ― прозвище Саурона, как врага непорабощённых народов. Аналогичное прозвище принадлежало Морготу;</td>
                                            <td>Саурон Redivivus (от лат. Воскресший) ― прозвище, которым Дж. Р. Р. Толкин называет Саурона в годы его появления в Лихолесье.</td>
                                            <td>Властелин Тёмных Земель ― титул Саурона как владыки земель Мордора.</td>
                                        </tr>
                                    </table>
                        </div>
                    </div>
                </div>
        
                <div id="Form"><h2>Форма</h2></div>
                <form class="pl-sm-3" action=""
                    method="POST">
                    <label>
                        ФИО:<br />
                        <input name="field-name-1"
                        value="" />
                    </label><br />
                    <label>
                        Номер телефона:<br />
                        <input name="field-email"
                        value=""
                        type="tel" />
                    </label><br />
                    <label>
                        email:<br />
                        <input name="field-email"
                        value=""
                        type="email" />
                    </label><br />
                    <label>
                        Дата рождения:<br />
                        <input name="field-date"
                        value=""
                        type="date" />
                    </label><br />
                    Пол:<br />
                    <label><input type="radio" checked="checked"
                    name="radio-group-1" value="Значение1" />
                    Женский</label>
                    <label><input type="radio"
                    name="radio-group-1" value="Значение2" />
                    Мужской</label><br />
                    <label>
                        Любимый язык программирования:
                        <br />
                        <select name="field-name-4[]"
                        multiple="multiple">
                        <option value="Значение1">Pascal</option>
                        <option value="Значение2" selected="selected">C
                        <option value="Значение3" selected="selected">C++
                        <option value="Значение3" selected="selected">JavaScript
                        <option value="Значение3" selected="selected">PHP
                        <option value="Значение3" selected="selected">Python
                        <option value="Значение3" selected="selected">Java
                        <option value="Значение3" selected="selected">Haskel
                        <option value="Значение3" selected="selected">Clojure
                        <option value="Значение3" selected="selected">Prolog
                        <option value="Значение3" selected="selected">Scala
                        </select>
                    </label><br />
                    <label>
                        Биография:<br />
                        <textarea name="field-name-2"></textarea>
                    </label><br />   
                    С контрактом ознакомлен:<br />
                    <label><input type="checkbox" checked="checked" name="check-1" />
                    </label><br />
                    <div class="kn pb-sm-3">
                        <input type="submit" value="Сохранить" />
                    </div>
                </form>
            </div>
            <footer>
                <p> (с) Тополян Алина</p>
            </footer>
        </div>
    </div>
</body>
</html>