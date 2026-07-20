<template>
    <form class="container configurator" @submit.prevent="handleSubmit">
        <div class="configurator__heading">
            <h2>Конфигуратор аккумуляторной сборки</h2>
            <button data-fancybox data-src="#instruction">С чего начать</button>
        </div>
        <div class="instruction" id="instruction" >
            <h2>С чего начать</h2>
            <a href="#">видеообзор №1</a>
            <a href="#">видеообзор №2</a>

            <h6>Шаг 1. Начните с главного.</h6>
            <p>Укажите требуемое напряжение и емкость, после чего нажмите кнопку "Подобрать конфигурацию". Конфигуратор рассчитает и отобразит варианты аккумуляторной сборки с платой БМС, зарядным устройством, проводами и разъемами.</p>
            <p>Если вам известен требуемый долговременный ток, укажите его перед автоподбором во вкладке «Параметры для опытных пользователей».</p>
            <p>На этом этапе, вы можете ознакомиться с предложенным выбором, изменить любой компонент на наиболее подходящий к вашему устройству. Ознакомиться со всеми характеристиками выбранного варианта и далее подобрать совместимые компоненты - корпус, зарядное устройство, BMS, провода и разъемы. Для контроля несовместимости программа подсвечивает окно ввода красным цветом.
            </p>
            <p>Если вы нашли свой вариант, и определились с комплектующими, можно переходить к шагу 4.
            </p>
            <p>Если предложенные на этом шаге варианты не подходят, приступаем к шагу 2.
            </p>

            <h6>Шаг 2. Уточните выбор в блоке "Параметры для опытных пользователей"</h6>
            <p>Для получения нужного результата, поэкспериментируйте с параметрами. После изменения значения в любом поле, конфигуратор заново пересчитывает результаты, показывает результирующие характеристики и проверяет компоненты на совместимость. Входные параметры которым не удовлетворяет один из компонентов батареи будет обведен красным цветом.
            </p>

            <h6>Шаг 3. Дополните вашу батарею.</h6>
            <p>Выберите компоненты - корпус, зарядное устройство, плата BMS. Конфигуратор по
                умолчанию учитывает складские остатки, и предлагает только подходящие к выбранному
                варианту батареи компоненты. От любого компонента можно отказаться, нажав кнопку</p>
            <p>Нажатие на изображение компонента открывает окно с карточкой товара, чтобы подробнее
                ознакомиться с характеристиками.</p>

            <h6>Шаг 4. Можно оформлять!</h6>
            <p>Осталось определиться с проводами и разъемами в блоке "Опции". Напишите желаемую
                длину, выберите тип разъёма. Ознакомьтесь со стоимостью. Если всё в порядке, нажмите
                кнопку "Купить сборку".</p>
        </div>
        <div class="configurator__content">
            <div class="configurator__content-left">
                <div class="form-section">
                    <div class="form-section__item">
                        <h4>Входные параметры </h4>
                        <div class="form-section__item-content">
                            <div class="form-group">
                                <label for="voltage">
                                    Напряжение, В
                                    <input type="number" name="voltage" id="voltage" v-model="formData.voltage" value="36">
                                </label>
                                <span title="Укажите номинальное напряжение">?</span>
                            </div>
                            <div class="form-group">
                                <label for="capacity">
                                    Емкость, Ач
                                    <input type="number" name="capacity" id="capacity" v-model="formData.capacity" value="10">
                                </label>
                                <span title="Укажите требуемую ёмкость">?</span>
                            </div>
                            <button type="button" class="btn" @click="calculateConfiguration">Подобрать конфигурацию</button>
                        </div>
                    </div>
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Параметры для опытных пользователей</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.75586" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                       <div class="form-section__item-content">
                           <div class="form-group">
                               <label for="loadCurrent">
                                   Долговременный ток нагрузки, А
                                   <input type="number" name="loadCurrent" id="loadCurrent" v-model="formData.loadCurrent" value="10.4">
                               </label>
                               <span title="Количество потребляемого тока на протяжении длительного времени">?</span>
                           </div>
                           <div class="form-group">
                               <label for="series">
                                   Серия (S), шт
                                   <input type="number" name="series" id="series" v-model="formData.series" value="10">
                               </label>
                               <span title="Количество последовательно соединенных элементов; определяет напряжение батареи">?</span>
                           </div>
                           <div class="form-group">
                               <label for="parallel">
                                   Параллели (P), шт
                                   <input type="number" name="parallel" id="parallel" v-model="formData.parallel" value="4">
                               </label>
                               <span title="Количество параллельно соединенных серий элементов; определяет ёмкость батареи">?</span>
                           </div>
                           <div class="form-checkbox-group">
                               <label for="symmetricBms">
                                   <input type="checkbox" id="symmetricBms" v-model="formData.symmetricBms">
                                   Симметричная BMS
                               </label>
                               <span title="Ток разряда и заряда равны, общий разъем заряд-разряд (подходит для рекуперации)">?</span>
                           </div>
                           <div class="form-checkbox-group">
                               <label for="considerStock">
                                   <input type="checkbox" id="considerStock" v-model="formData.considerStock">
                                   Учитывать складские остатки
                               </label>
                           </div>
                       </div>
                    </div>
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Состав батареи</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.75586" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                       <div class="form-section__item-content">
                           <div class="form-select-group">
                               <h5>Элемент</h5>
                               <a href="#voltage-select" class="el-select"  data-fancybox >+ <span> Выбор по напряжению и емкости</span></a>
                               <a href="#" class="sp-select">+ <span>Выбор по S и P</span></a>
                           </div>
                           <div class="form-select-group">
                               <h5>Корпус/внешняя оболочка</h5>
                               <a href="#" class="body-select">+ <span>Выбрать</span></a>
                           </div>
                           <div class="form-select-group">
                               <h5>Зарядное устройство</h5>
                               <a href="#" class="charger-select">+ <span>Выбрать</span></a>
                           </div>
                           <div class="form-select-group">
                               <h5>BMS</h5>
                               <a href="#" class="bms-select">+ <span>Выбрать</span></a>
                           </div>
                       </div>
                    </div>
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Форма батареи</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.75586" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">
                            <div class="form-checkbox-group">
                                <label>
                                    <input type="checkbox">
                                    Использовать холдеры
                                </label>
                            </div>
                            <div class="form-checkbox-group">
                                <label>
                                    <input type="checkbox" >
                                    Залить теплопроводным компаундом
                                </label>
                                <span title="Специальный теплопроводный компаунд защищает батарею от риска коррозии, увеличивает механическую прочность, равномерно отводит тепло, исключает воздушный зазор между элементами батареи и внешним кожухом из текстолита.">?</span>
                            </div>
                            <div class="form-group">
                                <label>
                                    Число рядов
                                    <input type="number" value="1">
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Опции</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.75586" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">
                            <div class="form-group">
                                <label>
                                    Длина зарядного провода, см
                                    <input type="number" value="0">
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    Длина разрядного провода, см
                                    <input value="20">
                                </label>
                            </div>
                            <div class="form-select-group">
                                <h5>Провод разряд</h5>
                                <a href="#">+ <span>Выбрать</span></a>
                            </div>
                            <div class="form-select-group">
                               <h5> Разъем разряда</h5>
                                <a href="#">+ <span>Выбрать</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="form-reset-btn">Сбросить все фильтры</button>
            </div>
            <div class="configurator__content-right">
                <div class="voltage-select" id="voltage-select">
                    <h2>Выбор по напряжению и емкости</h2>
                    <div class="voltage-select__search">
                        <input type="text" placeholder="Поиск">
                        <button>
                            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1273_10302)">
                                    <path d="M13.8004 3.175C16.7004 6.075 16.7004 10.875 13.8004 13.775C10.9004 16.675 6.10039 16.675 3.20039 13.775C0.300391 10.875 0.300391 6.075 3.20039 3.175C6.10039 0.275 10.8004 0.275 13.8004 3.175Z" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.5 14.4746L19.1 19.0746" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1273_10302">
                                        <rect width="21" height="21" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                    <ul class="voltage-select__sorter">
                        <li class="active">Все</li>
                        <li>Li-Ion</li>
                        <li>LiFePO4</li>
                        <li>LiTiO</li>
                    </ul>
                    <div class="voltage-select__table">
                        <table class="">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Емкость батареи</th>
                                    <th>Напряжение(эл/бат)</th>
                                    <th>Длина серии(S)</th>
                                    <th>Кол-во парал.(P)</th>
                                    <th>Макс. ток</th>
                                    <th>Масса сборки</th>
                                    <th>Удельная цена</th>
                                    <th>Сумма сборки</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                Li-Ion
                                                3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                Li-Ion
                                                3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                Li-Ion
                                                3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="voltage-select__product">
                                            <div class="voltage-select__product-img">
                                                <img src="/images/product1.png" alt="">
                                            </div>
                                            <div class="voltage-select__product-ttl">
                                                LiFePO4 3.7V, Samsung ICR18650-26FM/F/H/J/HM, 2600 мАч (аккумулятор литий-ионный)
                                            </div>
                                        </div>
                                    </td>
                                    <td>12</td>
                                    <td>3.2/35.2</td>
                                    <td>11</td>
                                    <td>2</td>
                                    <td>120</td>
                                    <td>3124</td>
                                    <td>28.41</td>
                                    <td>12 469 руб. </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="configurator-item">
                    <div class="configurator-characteristics">
                        <h3>Основные характеристики</h3>
                        <ul>
                            <li>
                                <div>Ёмкость батареи  <span title="Емкость, рассчитанная на основе емкости одного элемента и количества параллелей в сборке">?</span></div>
                                <div></div>
                                <div><span>0</span>Ah</div>
                            </li>
                            <li>
                                <div>Ёмкость батареи  <span title="Современная мера измерения емкости батареи, учитывающая напряжение и  емкость сборки в амперчасах и является произведением этих двух показателей">?</span></div>
                                <div></div>
                                <div><span>0</span>Wh</div>
                            </li>
                            <li>
                                <div>Напряжение батареи   <span title="Является приблизительно средней точкой разрядной кривой батареи по напряжению, номинальное значение взято из спецификации элемента">?</span></div>
                                <div></div>
                                <div><span>0</span>V</div>
                            </li>
                            <li>
                                <div>Долговременный ток разряда сборки   <span title="Ток, который можно будет потреблять  от батареи  продолжительное время с учетом используемой БМС и используемых аккумуляторных элементов.  Этот параметр не следует путать с Непрерывным током батареи, который достаточно сложно вычислить, так как он сильно зависит от теплообмена с окружающей средой. Поэтому в АКБ настоятельно рекомендуется устанавливать термопредохранитель, который запретит использование батареи , если температура сборки превысит допустимый уровень.">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Максимальный ток заряда   <span title="Ток, Которым можно заряжать сборку без вреда для элементов. Этот ток может быть значительно меньше тока разряда за счет использования несимметричной БМС">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Масса элементов   <span title="Складывается из элементов и соединителей и значительно может быть уменьшена за счет использования более энергоемких моделей элементов или элементов с другим химическим составом. Не включает в себя корпус, БМС и зарядное устройство.">?</span></div>
                                <div></div>
                                <div><span>0</span>Гр</div>
                            </li>
                            <li>
                                <div>Д x Ш x В, мм </div>
                                <div></div>
                                <div><span>0 x 0 x 0</span></div>
                            </li>
                        </ul>
                    </div>
                    <div class="configurator-characteristics">
                        <h3>Основные характеристики</h3>
                        <ul>
                            <li>
                                <div>Напряжение заряженной батареи   <span title=" максимально разрешенное напряжение заряда аккумуляторной сборки, которое соответствует полному 100%  заряду батареи при условии правильно отбалансированной батарее.">?</span></div>
                                <div></div>
                                <div><span>0</span>V</div>
                            </li>
                            <li>
                                <div>Длина серии (S)  <span title=" количество последовательно соединенных блоков батареи . При этом типе соединения напряжение всех последовательно соединенных  элементов складывается. Это позволяет сделать батарею на заданное напряжение  U=S*Uэлемента. ">?</span></div>
                                <div></div>
                                <div><span>0</span></div>
                            </li>
                            <li>
                                <div>Кол-во параллелей (P) <span title="количество параллельно соединенных элементов. При этом типе соединения Емкость сборки пропорционально увеличивается.">?</span></div>
                                <div></div>
                                <div><span>0</span></div>
                            </li>
                            <li>
                                <div>Кол-во элементов в сборке<span title="Число используемых элементов, определяется как S*P">?</span></div>
                                <div></div>
                                <div><span>0</span>шт</div>
                            </li>
                            <li>
                                <div>Макс. ток элементов<span title="Максимальный ток батареи, который могут дать элементы, собранные в сборку, без учета используемой БМС, которая в свою очередь может вносить ограничение на этот параметр . Как правило , время нагрузки таким током ограничено нагревом батареи, при котором  необходим температурный контроль.">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Долговременный ток элементов   <span title="Ток, который можно потреблять от элементов длительный промежуток времени , который ограничен только нагревом батареи. Нагрев зависит от многих параметров , таких как температура окружающей среды, компоновка элементов , корпус батареи.">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Максимальный ток батареи (5сек)  <span title="Максимальный ток батареи, который могут дать элементы , собранные в сборку, с учетом  используемой БМС, которая в свою очередь может вносить ограничение на этот параметр . Как правило , время нагрузки таким током ограничено нагревом батареи, при котором  необходим температурный контроль.">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Удельная цена  <span title="стоимость аккумулятора , необходимого для запаса энергии 1 Ваттчас. Параметр можно использовать для относительного сравнения сборок из разных элементов.">?</span></div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Загрузка батареи (долг. / пик.)  <span title="Параметр для оценки степени нагрузки на элементы батареи.  Долговременная – рассчитана как  100* (долговременный ток элементов / планируемый долговременный ток). Пик – считаем как 100*(максимально допустимый ток элементов/ планируемый максимальный ток нагрузки). Параметр для оценки степени нагрузки на элементы батареи.  Долговременная – рассчитана как  100* (долговременный ток элементов / планируемый долговременный ток)
									Пик – считаем как 100*(максимально допустимый ток элементов/ планируемый максимальный ток нагрузки)
									При проектировании батареи этот параметр нужно стремиться получить как можно ниже, желательно в диапазоне 50-70% , с помощью выбора соответствующей модели элементов и емкости батареи .">?</span>
                                </div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                            <li>
                                <div>Нагрузка на силовой провод  <span title="параметр для определения степени нагрузки на силовой провод. Можно считать, что при загрузке 100% при длительной нагрузке провод будет нагреваться в допустимых пределах. Плотность тока при этом 8 А/мм2. Для снижения нагрузки выбирайте провод с увеличенным сечением.">?</span> </div>
                                <div></div>
                                <div><span>0</span>A</div>
                            </li>
                        </ul>
                    </div>
                    <!-- Предпросмотр -->
                    <div class="preview">
                        <h3>Форма батареи</h3>
                        <div class="product-preview" :style="previewStyle">
                            <img :src="selectedProduct.image" :alt="selectedProduct.name">
                        </div>

                    </div>

                    <div class="configurator-price">
                        <h3>Предварительная стоимость</h3>
                        <ul>
                            <li>
                                <div>Элементы</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Корпус/внешняя оболочка</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Зарядное устройство</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Плата BMS</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Зарядный провод</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Разрядный провод</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Зарядный разъем</div>
                                <div></div>
                                <div>0</div>
                            </li>
                            <li>
                                <div>Разрядный разъем</div>
                                <div></div>
                                <div>0</div>
                            </li>
                        </ul>
                        <div class="configurator-price__total">
                            <span>ИТОГО</span>
                            <div>
                                <span>{{ totalPrice }}</span>
                                руб.
                            </div>
                        </div>
                        <button>Купить сборку</button>
                    </div>
                </div>
            </div>
        </div>


    </form>
</template>

<script>
import {productFilterOnMount} from "../../Shop/ProductFilterOnMount";

export default {
    name: 'Configurator',
    data() {
        return {
            basePrice: 1000,
            formData: {
                voltage: 36,
                capacity: 36,
                loadCurrent: 10.4,
                series: 10,
                parallel: 4,
                symmetricBms: false,
                considerStock: false
            },
            selectedOptions: {
                color: 'black',
                size: 'medium',
                material: 'leather'
            },
            options: [
                {
                    id: 'color',
                    name: 'Цвет',
                    items: [
                        { id: 'black', name: 'Черный', price: 0, image: '/images/black.jpg' },
                        { id: 'white', name: 'Белый', price: 200, image: '/images/white.jpg' },
                        { id: 'red', name: 'Красный', price: 300, image: '/images/red.jpg' }
                    ]
                },
                {
                    id: 'size',
                    name: 'Размер',
                    items: [
                        { id: 'small', name: 'Маленький', price: 0 },
                        { id: 'medium', name: 'Средний', price: 500 },
                        { id: 'large', name: 'Большой', price: 1000 }
                    ]
                },
                {
                    id: 'material',
                    name: 'Материал',
                    items: [
                        { id: 'leather', name: 'Кожа', price: 0 },
                        { id: 'fabric', name: 'Ткань', price: -200 },
                        { id: 'plastic', name: 'Пластик', price: -500 }
                    ]
                }
            ]
        }
    },
    mounted() {
        productFilterOnMount()
    },
    computed: {
        totalPrice() {
            let total = this.basePrice
            this.options.forEach(option => {
                const selectedItem = option.items.find(item =>
                    item.id === this.selectedOptions[option.id]
                )
                if (selectedItem) {
                    total += selectedItem.price
                }
            })
            return total
        },
        selectedProduct() {
            const colorOption = this.options.find(opt => opt.id === 'color')
            return colorOption.items.find(item => item.id === this.selectedOptions.color)
        },
        previewStyle() {
            return {
                border: '2px solid #ddd',
                padding: '20px',
                borderRadius: '8px'
            }
        }
    },
    methods: {
        selectOption(optionId, itemId) {
            this.selectedOptions[optionId] = itemId
        },
        getSelectedOptionName(optionId) {
            const option = this.options.find(opt => opt.id === optionId)
            const selectedItem = option.items.find(item =>
                item.id === this.selectedOptions[optionId]
            )
            return selectedItem ? selectedItem.name : ''
        },
        calculateConfiguration() {

        },
        getConfiguration() {
            return {
                basePrice: this.basePrice,
                totalPrice: this.totalPrice,
                formData: { ...this.formData },
                selectedOptions: { ...this.selectedOptions },
                optionNames: this.options.map(option => ({
                    name: option.name,
                    value: this.getSelectedOptionName(option.id)
                }))
            }
        },
        handleSubmit() {
            const configuration = this.getConfiguration()

            console.log('Данные формы:', configuration)

            // Здесь можно отправить данные на сервер
            // Например: this.$http.post('/api/order', configuration)

            alert(`Товар добавлен в корзину! Итоговая цена: ${this.totalPrice} ₽`)

            // Если нужно перенаправить на другую страницу
            // this.$router.push('/cart')
        }
    }
}
</script>

<style scoped>
</style>
