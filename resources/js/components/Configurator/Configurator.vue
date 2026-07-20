<template>
    <form class="container configurator" @submit.prevent="handleSubmit">
        <div class="configurator__heading">
            <h2>Конфигуратор аккумуляторной сборки</h2>
            <button type="button" data-fancybox data-src="#instruction">С чего начать</button>
            <button type="button" data-fancybox data-src="#configurator-left">Подобрать конфигурацию</button>
        </div>

        <!-- Инструкция -->
        <div class="instruction" id="instruction">
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

        <div class="configurator__content" >
            <div class="configurator__content-left" id="configurator-left">
                <div class="form-section">
                    <div class="form-section__item">
                        <h4>Входные параметры</h4>
                        <div class="form-section__item-content">
                            <div class="form-group">
                                <label for="voltage">
                                    Напряжение, В
                                    <input type="number" id="voltage" v-model.number="formData.voltage" min="1" max="100">
                                </label>
                                <span title="Укажите номинальное напряжение">?</span>
                            </div>
                            <div class="form-group">
                                <label for="capacity">
                                    Емкость, Ач
                                    <input type="number" id="capacity" v-model.number="formData.capacity" min="1" max="1000">
                                </label>
                                <span title="Укажите требуемую ёмкость">?</span>
                            </div>
                            <button type="button" class="btn" @click="calculateConfiguration">
                                Подобрать конфигурацию
                            </button>
                        </div>
                    </div>
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Параметры для опытных пользователей</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.755859" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">
                            <div class="form-group">
                                <label for="loadCurrent">
                                    Долговременный ток нагрузки, А
                                    <input type="number" id="loadCurrent" v-model.number="formData.loadCurrent" min="0" max="500">
                                </label>
                                <span title="Количество потребляемого тока на протяжении длительного времени">?</span>
                            </div>
                            <div class="form-group">
                                <label for="series">
                                    Серия (S), шт
                                    <input type="number" id="series" v-model.number="formData.series" min="1" max="100">
                                </label>
                                <span title="Количество последовательно соединенных элементов; определяет напряжение батареи">?</span>
                            </div>
                            <div class="form-group">
                                <label for="parallel">
                                    Параллели (P), шт
                                    <input type="number" id="parallel" v-model.number="formData.parallel" min="1" max="100">
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

                    <!-- Состав батареи -->
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Состав батареи</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.755859" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5>Элемент</h5>
                                    <button v-if="selectedComponents.element" data-fancybox data-src="#voltage-select" class="show"></button>
                                    <button v-if="selectedComponents.element" @click="removeComponent('element')" class="close"></button>
                                </div>
                                <div v-if="selectedComponents.element" class="selected-component">

                                    <img :src="selectedComponents.element.image" :alt="selectedComponents.element.name">
                                    <h5>{{ selectedComponents.element.name }}</h5>
                                </div>
                                <a href="#voltage-select" class="el-select" data-fancybox>
                                    + <span> Выбор по напряжению и емкости</span>
                                </a>
                                <a href="#sp-select" data-fancybox>
                                    + <span>Выбор по S и P</span>
                                </a>
                            </div>
                            <!-- Корпус/внешняя оболочка -->
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5>Корпус/внешняя оболочка</h5>
                                    <button v-if="selectedComponents.case" data-fancybox data-src="#voltage-select" class="show"></button>
                                    <button v-if="selectedComponents.case" @click="removeComponent('case')" class="close"></button>
                                </div>
                                <a v-if="!selectedComponents.case" href="#body-select" class="" data-fancybox>
                                    + <span>Выбрать</span>
                                </a>
                                <div v-if="selectedComponents.case" class="selected-component">
                                    <img :src="selectedComponents.case.image" :alt="selectedComponents.case.name">
                                    <h5>{{ selectedComponents.case.name }}</h5>
                                </div>
                            </div>
                            <!-- Зарядное устройство -->
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5>Зарядное устройство</h5>
                                    <button v-if="selectedComponents.charger" data-fancybox data-src="#charger-select" class="show"></button>
                                    <button v-if="selectedComponents.charger" @click="removeComponent('charger')" class="close"></button>
                                </div>
                                <a v-if="!selectedComponents.charger" href="#charger-select" class="" data-fancybox>
                                    + <span>Выбрать</span>
                                </a>
                                <div v-if="selectedComponents.charger" class="selected-component">
                                    <img :src="selectedComponents.charger.image" :alt="selectedComponents.charger.name">
                                    <h5>{{ selectedComponents.charger.name }}</h5>
                                </div>
                            </div>
                            <!-- BMS -->
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5>BMS</h5>
                                    <button v-if="selectedComponents.bms" data-fancybox data-src="#bms-select" class="show"></button>
                                    <button v-if="selectedComponents.bms" @click="removeComponent('bms')" class="close"></button>
                                </div>
                                <a v-if="!selectedComponents.bms" href="#bms-select" data-fancybox>
                                    + <span>Выбрать</span>
                                </a>
                                <div v-if="selectedComponents.bms" class="selected-component">
                                    <img :src="selectedComponents.bms.image" :alt="selectedComponents.bms.name">
                                    <h5>{{ selectedComponents.bms.name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Форма батареи -->
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Форма батареи</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.755859" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">
                            <div class="form-checkbox-group">
                                <label>
                                    <input type="checkbox" v-model="formData.useHolders">
                                    Использовать холдеры
                                </label>
                            </div>
                            <div class="form-checkbox-group">
                                <label>
                                    <input type="checkbox" v-model="formData.useCompound">
                                    Залить теплопроводным компаундом
                                </label>
                                <span title="Специальный теплопроводный компаунд защищает батарею от риска коррозии, увеличивает механическую прочность, равномерно отводит тепло, исключает воздушный зазор между элементами батареи и внешним кожухом из текстолита.">?</span>
                            </div>
                            <div class="form-group">
                                <label>
                                    Число рядов
                                    <input type="number" v-model.number="formData.rowsCount" min="1" max="10">
                                </label>
                            </div>

                            <!-- Варианты форм батареи -->
                            <div class="battery-forms">
                                <div class="battery-forms-grid">
                                    <div
                                        v-for="form in compatibleBatteryForms"
                                        :key="form.id"
                                        class="battery-form-option"
                                        :class="{ 'selected': selectedBatteryForm?.id === form.id }"
                                        @click="selectBatteryForm(form)"
                                    >
                                        <div class="battery-form-image">
                                            <img :src="form.image" :alt="form.name">
                                        </div>
                                    </div>
<!--                                    <div  class="battery-form-option" >-->
<!--                                        <input type="file">-->
<!--                                        <span>?</span>-->
<!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Опции -->
                    <div class="form-section__item">
                        <div class="form-section__item-ttl">
                            <h4>Опции</h4>
                            <svg width="17" height="9" viewBox="0 0 17 9" fill="none">
                                <path d="M0.655273 0.755859L8.15194 7.25586L15.6486 0.755859" stroke="#382F22" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="form-section__item-content">

                            <div class="form-group">
                                <label>
                                    Длина зарядного провода, см
                                    <input type="number" v-model.number="formData.chargeWireLength" min="0" max="500">
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    Длина разрядного провода, см
                                    <input type="number" v-model.number="formData.dischargeWireLength" min="0" max="500">
                                </label>
                            </div>
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5>Провод разряд</h5>
                                    <button v-if="selectedComponents.dischargeWire" data-fancybox data-src="#wire-select" class="show"></button>
                                    <button v-if="selectedComponents.dischargeWire" @click="removeComponent('dischargeWire')" class="close"></button>
                                </div>
                                <div v-if="selectedComponents.dischargeWire" class="selected-component">
                                    <img :src="selectedComponents.dischargeWire.image" alt="">
                                    <h5>{{ selectedComponents.dischargeWire.name }}</h5>
                                </div>
                                <a v-if="!selectedComponents.dischargeWire" href="#wire-select" data-fancybox>+ <span>Выбрать</span></a>
                            </div>
                            <div class="form-select-group">
                                <div class="form-select-group__header">
                                    <h5> Разъем разряда</h5>
                                    <button v-if="selectedComponents.dischargeConnector" data-fancybox data-src="#connector-select" class="show"></button>
                                    <button v-if="selectedComponents.dischargeConnector" @click="removeComponent('dischargeConnector')" class="close"></button>
                                </div>
                                <a v-if="!selectedComponents.dischargeConnector" href="#connector-select" data-fancybox>+ <span>Выбрать</span></a>
                                <div v-if="selectedComponents.dischargeConnector" class="selected-component">
                                    <img :src="selectedComponents.dischargeConnector.image" alt="">
                                    <h5>{{ selectedComponents.dischargeConnector.name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="form-reset-btn" @click="resetForm">Сбросить все фильтры</button>
            </div>

            <!-- Правая колонка - результаты -->
            <div class="configurator__content-right">
                <!-- Основные характеристики -->
                <div class="configurator-characteristics">
                    <h3>Основные характеристики</h3>
                    <ul>
                        <li>
                            <div>Ёмкость батареи <span title="Емкость, рассчитанная на основе емкости одного элемента и количества параллелей в сборке">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.capacityAh }} Ah</div>
                        </li>
                        <li>
                            <div>Энергоемкость батареи <span title="Современная мера измерения емкости батареи, учитывающая напряжение и емкость сборки">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.capacityWh }} Wh</div>
                        </li>
                        <li>
                            <div>Напряжение батареи <span title="Является приблизительно средней точкой разрядной кривой батареи по напряжению">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.voltage }} V</div>
                        </li>
                        <li>
                            <div>Долговременный ток разряда <span title="Ток, который можно потреблять от батареи продолжительное время">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.continuousDischargeCurrent }} A</div>
                        </li>
                        <li>
                            <div>Максимальный ток заряда <span title="Ток, которым можно заряжать сборку без вреда для элементов">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.maxChargeCurrent }} A</div>
                        </li>
                        <li>
                            <div>Масса элементов <span title="Складывается из элементов и соединителей">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.elementWeight }} г</div>
                        </li>
                        <li>
                            <div>Д x Ш x В, мм</div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.dimensions }}</div>
                        </li>
                    </ul>
                </div>

                <!-- Дополнительные характеристики -->
                <div class="configurator-characteristics">
                    <h3>Дополнительные характеристики</h3>
                    <ul>
                        <li>
                            <div>Напряжение заряженной батареи <span title="Максимально разрешенное напряжение заряда">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.chargedVoltage }} V</div>
                        </li>
                        <li>
                            <div>Длина серии (S) <span title="Количество последовательно соединенных блоков батареи">?</span></div>
                            <div></div>
                            <div>{{ formData.series }}</div>
                        </li>
                        <li>
                            <div>Кол-во параллелей (P) <span title="Количество параллельно соединенных элементов">?</span></div>
                            <div></div>
                            <div>{{ formData.parallel }}</div>
                        </li>
                        <li>
                            <div>Кол-во элементов в сборке <span title="Число используемых элементов, определяется как S*P">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.totalElements }} шт</div>
                        </li>
                        <li>
                            <div>Макс. ток элементов <span title="Максимальный ток батареи, который могут дать элементы">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.maxElementCurrent }} A</div>
                        </li>
                        <li>
                            <div>Долговременный ток элементов <span title="Ток, который можно потреблять от элементов длительный промежуток времени">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.continuousElementCurrent }} A</div>
                        </li>
                        <li>
                            <div>Максимальный ток батареи (5сек) <span title="Максимальный ток батареи с учетом используемой БМС">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.maxBatteryCurrent }} A</div>
                        </li>
                        <li>
                            <div>Удельная цена <span title="Стоимость аккумулятора для запаса энергии 1 Ваттчас">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.pricePerWh }} руб/Wh</div>
                        </li>
                        <li>
                            <div>Загрузка батареи (долг. / пик.) <span title="Параметр для оценки степени нагрузки на элементы батареи">?</span></div>
                            <div></div>
                            <div>{{ calculatedCharacteristics.loadPercentage }}%</div>
                        </li>
                    </ul>
                </div>

                <!-- Предпросмотр -->
                <div class="preview">
                    <h3>Форма батареи</h3>
                    <div v-if="!selectedBatteryForm" class="preview-placeholder">
                        <p>Выберите форму компоновки батареи</p>
                    </div>
                    <div class="product-preview">
                        <div v-if="selectedBatteryForm" class="preview-image">
                            <img :src="selectedBatteryForm.image" :alt="selectedBatteryForm.name">
                        </div>

                    </div>
                </div>

                <!-- Цена -->
                <div class="configurator-price">
                    <h3>Предварительная стоимость</h3>
                    <ul>
                        <li v-for="item in priceBreakdown" :key="item.name" :class="{ 'empty': !item.price }">
                            <div>{{ item.name }}</div>
                            <div></div>
                            <div>{{ item.price ? item.price + ' руб.' : '—' }}</div>
                        </li>
                    </ul>
                    <div class="configurator-price__total">
                        <span>ИТОГО</span>
                        <div>
                            <span>{{ totalPrice }}</span> руб.
                        </div>
                    </div>
                    <button type="submit" :disabled="totalPrice === 0" class="buy-btn">
                        Купить сборку
                    </button>
                </div>
            </div>
        </div>

        <!-- Voltage Select Popup -->
        <div class="voltage-select" id="voltage-select">
            <h2>Выбор по напряжению и емкости</h2>
            <div class="voltage-select__search">
                <input type="text" placeholder="Поиск" v-model="searchQuery">
                <button>
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <g clip-path="url(#clip0_1273_10302)">
                            <path d="M13.8004 3.175C16.7004 6.075 16.7004 10.875 13.8004 13.775C10.9004 16.675 6.10039 16.675 3.20039 13.775C0.300391 10.875 0.300391 6.075 3.20039 3.175C6.10039 0.275 10.8004 0.275 13.8004 3.175Z" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.5 14.4746L19.1 19.0746" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                </button>
            </div>
            <ul class="voltage-select__sorter">
                <li
                    v-for="category in categories"
                    :key="category"
                    :class="{ active: selectedCategory === category }"
                    @click="selectCategory(category)"
                >
                    {{ category }}
                </li>
            </ul>
            <div class="voltage-select__table">
                <table>
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
                    <tr
                        v-for="product in filteredProducts"
                        :key="product.id"
                        @click="selectProduct(product)"
                        class="product-row"
                    >
                        <td>
                            <div class="voltage-select__product">
                                <div class="voltage-select__product-img">
                                    <img :src="product.image" :alt="product.name">
                                </div>
                                <div class="voltage-select__product-ttl">
                                    {{ product.name }}
                                </div>
                            </div>
                        </td>
                        <td>{{ product.batteryCapacity }}</td>
                        <td>{{ product.batteryVoltage }}</td>
                        <td>{{ product.series }}</td>
                        <td>{{ product.parallel }}</td>
                        <td>{{ product.maxCurrent }}</td>
                        <td>{{ product.weight }}</td>
                        <td>{{ product.pricePerUnit }}</td>
                        <td>{{ product.totalPrice }} руб.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SP Select Popup -->
        <div class="sp-select" id="sp-select">
            <h2>Рекомендуемые варианты</h2>
            <table>
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Описание</th>
                    <th>Рекомендуемые S/P</th>
                    <th>Цена элемента</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="product in spProducts"
                    :key="product.id"
                    @click="selectProductFromSP(product)"
                    class="product-row"
                    style="cursor: pointer;"
                >
                    <td>
                        <img :src="product.image" :alt="product.name" style="width: 80px; height: 80px; object-fit: cover;">
                    </td>
                    <td>
                        <strong>{{ product.displayName }}</strong>
                        <br>
                        <small>
                            Напряжение: {{ product.voltage }}V,
                            Емкость: {{ product.capacity }}Ач,
                            Ток: {{ product.maxCurrent }}А
                        </small>
                    </td>
                    <td>
                        <strong>S: {{ product.recommendedSeries }}, P: {{ product.recommendedParallel }}</strong>
                        <br>
                        <small>
                            Напряжение сборки: {{ (product.voltage * product.recommendedSeries).toFixed(1) }}V
                            <br>
                            Емкость сборки: {{ (product.capacity * product.recommendedParallel).toFixed(1) }}Ач
                        </small>
                    </td>
                    <td>{{ product.price }} ₽</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Остальные попапы -->
        <!-- Body Select Popup -->
        <div class="body-select" id="body-select">
            <h2>Выбор корпуса</h2>
            <div v-if="compatibleCases.length > 0">
                <p>Совместимые корпуса:</p>
                <table>
                    <tr
                        v-for="caseItem in compatibleCases"
                        :key="caseItem.id"
                        @click="selectCase(caseItem)"
                        class="product-row"
                        style="cursor: pointer;"
                    >
                        <td>
                            <img :src="caseItem.image" :alt="caseItem.name" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ caseItem.name }}</strong>
                            <br>
                            <small>
                                Макс. элементов: {{ caseItem.maxElements }},
                                Размеры: {{ caseItem.dimensions }}
                            </small>
                        </td>
                        <td>{{ caseItem.price }} ₽</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>Нет совместимых корпусов. Сначала выберите элемент батареи.</p>
            </div>
        </div>

        <!-- Charger Select Popup -->
        <div class="charger-select" id="charger-select">
            <h2>Выбор зарядного устройства</h2>
            <div v-if="compatibleChargers.length > 0">
                <p>Совместимые зарядные устройства:</p>
                <table>
                    <tr
                        v-for="charger in compatibleChargers"
                        :key="charger.id"
                        @click="selectCharger(charger)"
                        class="product-row"
                        style="cursor: pointer;"
                    >
                        <td>
                            <img :src="charger.image" :alt="charger.name" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ charger.name }}</strong>
                            <br>
                            <small>
                                Напряжение: {{ charger.voltage }}V,
                                Ток: {{ charger.current }}A,
                                Химия: {{ charger.chemistry }}
                            </small>
                        </td>
                        <td>{{ charger.price }} ₽</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>Нет совместимых зарядных устройств. Сначала выберите элемент батареи.</p>
            </div>
        </div>

        <!-- BMS Select Popup -->
        <div class="bms-select" id="bms-select">
            <h2>Выбор BMS</h2>
            <div v-if="compatibleBMS.length > 0">
                <p>Совместимые платы BMS:</p>
                <table>
                    <tr
                        v-for="bms in compatibleBMS"
                        :key="bms.id"
                        @click="selectBMS(bms)"
                        class="product-row"
                        style="cursor: pointer;"
                    >
                        <td>
                            <img :src="bms.image" :alt="bms.name" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ bms.name }}</strong>
                            <br>
                            <small>
                                Серии: {{ bms.minSeries }}-{{ bms.maxSeries }}S,
                                Ток: {{ bms.maxCurrent }}A,
                                Химия: {{ bms.chemistry }},
                                {{ bms.symmetric ? 'Симметричная' : 'Несимметричная' }}
                            </small>
                        </td>
                        <td>{{ bms.price }} ₽</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>Нет совместимых BMS. Сначала выберите элемент батареи.</p>
            </div>
        </div>

        <!-- Wire Select Popup -->
        <div class="wire-select" id="wire-select">
            <h2>Выбор провода</h2>
            <div v-if="compatibleWires.length > 0">
                <p>Совместимые провода:</p>
                <table>
                    <tr
                        v-for="wire in compatibleWires"
                        :key="wire.id"
                        @click="selectDischargeWire(wire)"
                        class="product-row"
                        style="cursor: pointer;"
                    >
                        <td>
                            <img :src="wire.image" :alt="wire.name" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ wire.name }}</strong>
                            <br>
                            <small>
                                Макс. ток: {{ wire.maxCurrent }}A,
                                Сечение: {{ wire.crossSection }}мм²
                            </small>
                        </td>
                        <td>{{ wire.price }} ₽</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>Нет совместимых проводов для тока {{ formData.loadCurrent }}A.</p>
            </div>
        </div>

        <!-- Connector Select Popup -->
        <div class="connector-select" id="connector-select">
            <h2>Выбор разъема</h2>
            <div v-if="compatibleConnectors.length > 0">
                <p>Совместимые разъемы:</p>
                <table>
                    <tr
                        v-for="connector in compatibleConnectors"
                        :key="connector.id"
                        @click="selectDischargeConnector(connector)"
                        class="product-row"
                        style="cursor: pointer;"
                    >
                        <td>
                            <img :src="connector.image" :alt="connector.name" style="width: 80px; height: 80px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ connector.name }}</strong>
                            <br>
                            <small>
                                Макс. ток: {{ connector.maxCurrent }}A
                            </small>
                        </td>
                        <td>{{ connector.price }} ₽</td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>Нет совместимых разъемов для тока {{ formData.loadCurrent }}A.</p>
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
            formData: {
                voltage: 36,
                capacity: 10,
                loadCurrent: 10.4,
                series: 10,
                parallel: 4,
                symmetricBms: false,
                considerStock: true,
                useHolders: false,
                useCompound: false,
                rowsCount: 1,
                chargeWireLength: 0,
                dischargeWireLength: 20
            },
            selectedComponents: {
                element: null,
                case: null,
                charger: null,
                bms: null,
                dischargeWire: null,
                dischargeConnector: null
            },
            // Единый массив продуктов
            products: [
                {
                    id: 1,
                    name: 'LiFePO4 3.2V, JG CFR18650-1800 мАч',
                    category: 'LiFePO4',
                    type: 'element',
                    capacity: 1.8,
                    voltage: 3.2,
                    maxCurrent: 10,
                    weight: 45,
                    price: 100,
                    image: '/images/sp11.jpg',
                    // Добавьте ВСЕ необходимые поля для voltage-select
                    batteryCapacity: 7.2, // capacity * parallel
                    batteryVoltage: '3.2/32.0', // voltage + (voltage * series)
                    series: 10,
                    parallel: 4,
                    pricePerUnit: 13.89, // totalPrice / (voltage * capacity * parallel)
                    totalPrice: 4000, // price * series * parallel
                    // И для sp-select
                    recommendedSeries: 10,
                    recommendedParallel: 4,
                    displayName: 'LiFePO4 3.2V, JG CFR18650-1800 мАч (аккумулятор LiFePO4, 18650)'
                },
                {
                    id: 2,
                    name: 'Li-Ion 3.6V, BAK N18650CH, 2600 мАч',
                    category: 'Li-Ion',
                    type: 'element',
                    capacity: 2.6,
                    voltage: 3.6,
                    maxCurrent: 8,
                    weight: 48,
                    price: 200,
                    image: '/images/sp2.jpg',
                    // Поля для voltage-select
                    batteryCapacity: 12,
                    batteryVoltage: '3.6/36.0',
                    series: 10,
                    parallel: 4,
                    pricePerUnit: 25.50,
                    totalPrice: 11500,
                    // Поля для sp-select
                    recommendedSeries: 10,
                    recommendedParallel: 3,
                    compatibleCases: [1, 2],
                    compatibleBms: [2],
                    compatibleChargers: [2]
                },
                {
                    id: 3,
                    name: 'LiFePO4 3.2V, CATL 2000 мАч',
                    category: 'LiFePO4',
                    type: 'element',
                    capacity: 2.0,
                    voltage: 3.2,
                    maxCurrent: 15,
                    weight: 50,
                    price: 150,
                    image: '/images/product1.png',
                    // Только для sp-select (нет полей для voltage-select)
                    recommendedSeries: 8,
                    recommendedParallel: 5,
                    compatibleCases: [1],
                    compatibleBms: [1],
                    compatibleChargers: [1]
                },
                {
                    id: 4,
                    name: 'Li-Ion 3.7V, Samsung 3000 мАч',
                    category: 'Li-Ion',
                    type: 'element',
                    capacity: 3.0,
                    voltage: 3.7,
                    maxCurrent: 12,
                    weight: 52,
                    price: 250,
                    image: '/images/voltage.png',
                    // Только для voltage-select (нет рекомендованных S/P)
                    batteryCapacity: 15,
                    batteryVoltage: '3.7/37.0',
                    series: 10,
                    parallel: 5,
                    pricePerUnit: 30.00,
                    totalPrice: 15000
                }
            ],
            cases: [
                {
                    id: 1,
                    name: 'Текстолит 1мм (6 сторон)',
                    type: 'case',
                    price: 1,
                    image: '/images/body2.png',
                    maxElements: 100,
                    dimensions: '200x150x50'
                },
                {
                    id: 2,
                    name: 'Текстолит 2мм (6 сторон)',
                    type: 'case',
                    price: 2,
                    image: '/images/body1.png',
                    maxElements: 50,
                    dimensions: '150x100x40'
                }
            ],
            chargers: [
                {
                    id: 1,
                    name: 'Зарядное устройство 42В 2А (10S Li-Ion) 4202A',
                    type: 'charger',
                    price: 1900,
                    image: '/images/charger1.jpg',
                    voltage: 42,
                    current: 2,
                    chemistry: 'Li-Ion'
                },
                {
                    id: 2,
                    name: 'Зарядное устройство 36В 3А (10S LiFePO4)',
                    type: 'charger',
                    price: 1000,
                    image: '/images/charger2.jpg',
                    voltage: 36,
                    current: 3,
                    chemistry: 'LiFePO4'
                }
            ],
            bmsList: [
                {
                    id: 1,
                    name: 'Плата BMS 3-13S LiFePO4 HG13S (20А)',
                    type: 'bms',
                    price: 9000,
                    image: '/images/full_HG13S_6.jpg',
                    maxCurrent: 20,
                    minSeries: 3,
                    maxSeries: 13,
                    chemistry: 'LiFePO4',
                    symmetric: true
                },
                {
                    id: 2,
                    name: 'Плата BMS 2-13S Li-Ion HP13S (30A)',
                    type: 'bms',
                    price: 1080,
                    image: '/images/full_P_.jpg',
                    maxCurrent: 30,
                    minSeries: 2,
                    maxSeries: 13,
                    chemistry: 'Li-Ion',
                    symmetric: false
                }
            ],
            wires: [
                {
                    id: 1,
                    name: '20AWG 0,5 мм² Медный провод в тефлоновой изоляции',
                    type: 'wire',
                    price: 98,
                    image: '/images/cable.png',
                    maxCurrent: 10,
                    crossSection: 0.5,
                    // Добавьте displayName для корректного отображения
                    displayName: '20AWG 0,5 мм² Медный провод в тефлоновой изоляции'
                },
                // Добавьте еще проводов для тестирования
                {
                    id: 2,
                    name: '16AWG 1,5 мм² Медный провод',
                    type: 'wire',
                    price: 150,
                    image: '/images/cable.png',
                    maxCurrent: 20,
                    crossSection: 1.5,
                    displayName: '16AWG 1,5 мм² Медный провод'
                },
                {
                    id: 3,
                    name: '14AWG 2,5 мм² Медный провод',
                    type: 'wire',
                    price: 220,
                    image: '/images/cable1.jpg',
                    maxCurrent: 30,
                    crossSection: 2.5,
                    displayName: '14AWG 2,5 мм² Медный провод'
                }
            ],
            connectors: [
                {
                    id: 1,
                    name: 'Разъем DJ7021A-2.8-11 (2pin, вилка на кабель)',
                    type: 'connector',
                    price: 28,
                    image: '/images/connector1.png',
                    maxCurrent: 15
                }
            ],
            batteryForms: [
                {
                    id: 1,
                    name: 'Линейная компоновка',
                    image: '/images/test.jpg',
                    description: 'Элементы выстроены в один ряд',
                    compatibleElements: [1, 2],
                    maxSeries: 20,
                    maxParallel: 4
                },
                {
                    id: 2,
                    name: 'Шахматная компоновка',
                    image: '/images/test1-2.png',
                    description: 'Элементы расположены в шахматном порядке',
                    compatibleElements: [1, 2],
                    maxSeries: 15,
                    maxParallel: 6
                },
                {
                    id: 3,
                    name: 'Блочная компоновка',
                    image: '/images/test1-3.png',
                    description: 'Компактное расположение блоками',
                    compatibleElements: [1, 2],
                    maxSeries: 12,
                    maxParallel: 8
                },
                {
                    id: 4,
                    name: 'Многоуровневая компоновка',
                    image: '/images/voltage.png',
                    description: 'Элементы расположены в несколько уровней',
                    compatibleElements: [1],
                    maxSeries: 10,
                    maxParallel: 10
                }
            ],
            selectedBatteryForm: null,
            searchQuery: '',
            selectedCategory: 'Все',
            categories: ['Все', 'Li-Ion', 'LiFePO4', 'LiTiO']
        }
    },

    computed: {
        // Продукты для voltage-select (только с полями для таблицы)
        voltageProducts() {
            return this.products.filter(product =>
                product.hasOwnProperty('batteryCapacity') &&
                product.hasOwnProperty('batteryVoltage')
            );
        },

        // Продукты для sp-select (только с рекомендованными S/P)
        spProducts() {
            return this.products.filter(product =>
                product.hasOwnProperty('recommendedSeries') &&
                product.hasOwnProperty('recommendedParallel')
            ).map(product => ({
                ...product,
                displayName: product.name + ' (аккумулятор ' + product.category.toLowerCase() + ', 18650)'
            }));
        },

        // Для поиска в voltage-select
        filteredProducts() {
            let filtered = this.voltageProducts;

            if (this.selectedCategory !== 'Все') {
                filtered = filtered.filter(product => product.category === this.selectedCategory);
            }

            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(product =>
                    product.name.toLowerCase().includes(query)
                );
            }

            return filtered;
        },

        // Совместимые компоненты для попапов
        compatibleCases() {
            return this.getCompatibleCases();
        },

        compatibleBMS() {
            return this.getCompatibleBMS();
        },

        compatibleChargers() {
            return this.getCompatibleChargers();
        },

        compatibleWires() {
            return this.getCompatibleWires();
        },

        compatibleConnectors() {
            return this.getCompatibleConnectors();
        },

        calculatedCharacteristics() {
            const totalElements = this.formData.series * this.formData.parallel;
            let elementCapacity = 0;
            let elementVoltage = 0;

            if (this.selectedComponents.element) {
                elementCapacity = this.selectedComponents.element.capacity || 0;
                elementVoltage = this.selectedComponents.element.voltage || 0;
            }

            const capacityAh = elementCapacity * this.formData.parallel;
            const voltage = elementVoltage * this.formData.series;
            const capacityWh = voltage * capacityAh;

            const width = this.formData.series * 18 + 20;
            const length = this.formData.parallel * 18 + 20;
            const height = this.formData.rowsCount * 65 + 10;
            const dimensions = `${width} × ${length} × ${height}`;

            return {
                capacityAh: capacityAh.toFixed(1),
                capacityWh: Math.round(capacityWh),
                voltage: Math.round(voltage),
                continuousDischargeCurrent: this.formData.loadCurrent,
                maxChargeCurrent: this.formData.symmetricBms ? this.formData.loadCurrent : 5,
                elementWeight: totalElements * (this.selectedComponents.element?.weight || 45),
                dimensions: dimensions,
                chargedVoltage: Math.round(voltage * 1.2),
                totalElements: totalElements,
                maxElementCurrent: this.formData.parallel * (this.selectedComponents.element?.maxCurrent || 10),
                continuousElementCurrent: this.formData.parallel * 5,
                maxBatteryCurrent: Math.min(this.formData.parallel * 10, 100),
                pricePerWh: capacityWh > 0 ? (this.totalPrice / capacityWh).toFixed(2) : 0,
                loadPercentage: this.formData.loadCurrent > 0 ?
                    Math.min(100, Math.round((this.formData.loadCurrent / (this.formData.parallel * 5)) * 100)) : 0
            };
        },

        priceBreakdown() {
            const elementPrice = this.selectedComponents.element ?
                this.selectedComponents.element.price * this.formData.series * this.formData.parallel : 0;

            return [
                { name: 'Элементы', price: elementPrice },
                { name: 'Корпус/внешняя оболочка', price: this.selectedComponents.case?.price || 0 },
                { name: 'Зарядное устройство', price: this.selectedComponents.charger?.price || 0 },
                { name: 'Плата BMS', price: this.selectedComponents.bms?.price || 0 },
                { name: 'Зарядный провод', price: this.formData.chargeWireLength * 2 },
                { name: 'Разрядный провод', price: this.formData.dischargeWireLength * 2 },
                { name: 'Зарядный разъем', price: 0 },
                { name: 'Разрядный разъем', price: this.selectedComponents.dischargeConnector?.price || 0 }
            ];
        },

        totalPrice() {
            return this.priceBreakdown.reduce((sum, item) => sum + (item.price || 0), 0);
        },

        // Дополнительные изображения элемента
        additionalElementImages() {
            if (this.selectedComponents.element && this.selectedComponents.element.images) {
                return this.selectedComponents.element.images;
            }
            return [];
        },

        // Подходящие формы батареи для выбранного элемента
        compatibleBatteryForms() {
            if (!this.selectedComponents.element) {
                return [];
            }

            const elementId = this.selectedComponents.element.id;
            const totalElements = this.formData.series * this.formData.parallel;

            return this.batteryForms.filter(form => {
                const elementCompatible = form.compatibleElements.includes(elementId);
                const capacityCompatible = totalElements <= (form.maxSeries * form.maxParallel);
                return elementCompatible && capacityCompatible;
            });
        },

        // Основное превью
        mainPreviewImage() {
            if (this.selectedBatteryForm) {
                return this.selectedBatteryForm.image;
            }
            return null;
        }
    },

    mounted() {
        productFilterOnMount();
    },

    methods: {
        // Метод для вычисления размеров
        calculateDimensions() {
            const width = this.formData.series * 18 + 20;
            const length = this.formData.parallel * 18 + 20;
            const height = this.formData.rowsCount * 65 + 10;
            return `${width} × ${length} × ${height}`;
        },

        // Основной метод подбора конфигурации
        calculateConfiguration() {
            if (!this.formData.voltage || !this.formData.capacity) {
                alert('Пожалуйста, укажите напряжение и емкость');
                return;
            }

            this.selectElementByParams();

            if (this.selectedComponents.element) {
                this.selectCompatibleComponents();
            } else {
                alert('Не удалось подобрать подходящие элементы. Попробуйте изменить параметры.');
            }
        },

        // Подбор элемента по параметрам
        selectElementByParams() {
            const elements = this.products.filter(p => p.type === 'element');

            const requiredSeries = this.formData.series;
            const requiredParallel = this.formData.parallel;

            const suitableElement = elements.find(element => {
                const batteryVoltage = element.voltage * requiredSeries;
                const voltageMatch = Math.abs(batteryVoltage - this.formData.voltage) <= 2;

                const batteryCapacity = element.capacity * requiredParallel;
                const capacityMatch = Math.abs(batteryCapacity - this.formData.capacity) <= 2;

                const currentMatch = element.maxCurrent * requiredParallel >= this.formData.loadCurrent;

                return voltageMatch && capacityMatch && currentMatch;
            });

            if (suitableElement) {
                this.selectedComponents.element = { ...suitableElement };
                console.log('Выбран элемент:', suitableElement.name);
            }
        },

        // Автоматический подбор всех совместимых компонентов
        selectCompatibleComponents() {
            const element = this.selectedComponents.element;
            if (!element) return;

            this.selectCompatibleCase(element);
            this.selectCompatibleBMS(element);
            this.selectCompatibleCharger(element);
            this.selectWiresAndConnectors();
            this.autoSelectBatteryForm(element);
        },

        // Подбор совместимого корпуса
        selectCompatibleCase(element) {
            const totalElements = this.formData.series * this.formData.parallel;

            const suitableCase = this.cases.find(caseItem => {
                const capacityMatch = caseItem.maxElements >= totalElements;
                const stockMatch = this.formData.considerStock;
                return capacityMatch && stockMatch;
            });

            if (suitableCase) {
                this.selectedComponents.case = { ...suitableCase };
                console.log('Автоматически выбран корпус:', suitableCase.name);
            }
        },

        // Подбор совместимой BMS
        selectCompatibleBMS(element) {
            const suitableBMS = this.bmsList.find(bms => {
                const chemistryMatch = bms.chemistry === element.category;
                const seriesMatch = this.formData.series >= bms.minSeries &&
                    this.formData.series <= bms.maxSeries;
                const currentMatch = bms.maxCurrent >= this.formData.loadCurrent;
                const symmetricMatch = !this.formData.symmetricBms || bms.symmetric;
                return chemistryMatch && seriesMatch && currentMatch && symmetricMatch;
            });

            if (suitableBMS) {
                this.selectedComponents.bms = { ...suitableBMS };
                console.log('Автоматически выбрана BMS:', suitableBMS.name);
            }
        },

        // Подбор совместимого зарядного устройства
        selectCompatibleCharger(element) {
            const batteryVoltage = element.voltage * this.formData.series;
            const chargeVoltage = batteryVoltage * 1.2;

            const suitableCharger = this.chargers.find(charger => {
                const chemistryMatch = charger.chemistry === element.category;
                const voltageMatch = Math.abs(charger.voltage - chargeVoltage) <= 2;
                return chemistryMatch && voltageMatch;
            });

            if (suitableCharger) {
                this.selectedComponents.charger = { ...suitableCharger };
                console.log('Автоматически выбрано зарядное устройство:', suitableCharger.name);
            }
        },

        // Подбор проводов и разъемов
        selectWiresAndConnectors() {
            const suitableWire = this.wires.find(wire =>
                wire.maxCurrent >= this.formData.loadCurrent
            );

            if (suitableWire) {
                this.selectedComponents.dischargeWire = { ...suitableWire };
                console.log('Автоматически выбран провод:', suitableWire.name);
            }

            const suitableConnector = this.connectors.find(connector =>
                connector.maxCurrent >= this.formData.loadCurrent
            );

            if (suitableConnector) {
                this.selectedComponents.dischargeConnector = { ...suitableConnector };
                console.log('Автоматически выбран разъем:', suitableConnector.name);
            }
        },

        // Автоматический выбор подходящей формы батареи
        autoSelectBatteryForm(element) {
            if (this.compatibleBatteryForms.length > 0) {
                this.selectedBatteryForm = this.compatibleBatteryForms[0];
                console.log('Автоматически выбрана форма батареи:', this.selectedBatteryForm.name);
            }
        },

        // Выбор элемента из voltage-select
        selectProduct(product) {
            this.selectedComponents.element = {
                ...product,
                specifications: {
                    capacity: product.capacity,
                    voltage: product.voltage,
                    series: product.series,
                    parallel: product.parallel,
                    maxCurrent: product.maxCurrent
                }
            };

            this.selectCompatibleComponents();

            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        // Выбор элемента из sp-select
        selectProductFromSP(product) {
            this.selectedComponents.element = {
                ...product,
                specifications: {
                    capacity: product.capacity,
                    voltage: product.voltage,
                    maxCurrent: product.maxCurrent
                }
            };

            this.updateSeriesParallelFromProduct(product);
            this.selectCompatibleComponents();

            if (window.$.fancybox) {
                window.$.fancybox.close();
            }

            console.log('Выбран элемент из SP:', product.name);
        },

        // Обновление S и P на основе выбранного продукта
        updateSeriesParallelFromProduct(product) {
            if (product.recommendedSeries && product.recommendedParallel) {
                this.formData.series = product.recommendedSeries;
                this.formData.parallel = product.recommendedParallel;
            } else {
                this.calculateOptimalSeriesParallel(product);
            }
        },

        // Автоматический расчет оптимальных S и P
        calculateOptimalSeriesParallel(product) {
            if (!product.voltage || !product.capacity) return;

            const calculatedSeries = Math.round(this.formData.voltage / product.voltage);
            this.formData.series = Math.max(1, Math.min(calculatedSeries, 100));

            const calculatedParallel = Math.round(this.formData.capacity / product.capacity);
            this.formData.parallel = Math.max(1, Math.min(calculatedParallel, 100));
        },

        // Методы для ручного выбора компонентов
        selectCase(caseItem) {
            this.selectedComponents.case = caseItem;
            console.log('Выбран корпус:', caseItem.name);
            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        selectCharger(charger) {
            this.selectedComponents.charger = charger;
            console.log('Выбрано зарядное устройство:', charger.name);
            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        selectBMS(bms) {
            this.selectedComponents.bms = bms;
            console.log('Выбрана BMS:', bms.name);
            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        selectDischargeWire(wire) {
            this.selectedComponents.dischargeWire = wire;
            console.log('Выбран провод:', wire.name);
            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        selectDischargeConnector(connector) {
            this.selectedComponents.dischargeConnector = connector;
            console.log('Выбран разъем:', connector.name);
            if (window.$.fancybox) {
                window.$.fancybox.close();
            }
        },

        // Выбор формы батареи
        selectBatteryForm(form) {
            this.selectedBatteryForm = form;
            console.log('Выбрана форма батареи:', form.name);
        },

        // Получение совместимых компонентов для списков выбора
        getCompatibleCases() {
            if (!this.selectedComponents.element) return this.cases;

            const totalElements = this.formData.series * this.formData.parallel;
            return this.cases.filter(caseItem =>
                caseItem.maxElements >= totalElements
            );
        },

        getCompatibleBMS() {
            if (!this.selectedComponents.element) return this.bmsList;

            const element = this.selectedComponents.element;
            return this.bmsList.filter(bms => {
                const chemistryMatch = bms.chemistry === element.category;
                const seriesMatch = this.formData.series >= bms.minSeries &&
                    this.formData.series <= bms.maxSeries;
                const currentMatch = bms.maxCurrent >= this.formData.loadCurrent;
                const symmetricMatch = !this.formData.symmetricBms || bms.symmetric;
                return chemistryMatch && seriesMatch && currentMatch && symmetricMatch;
            });
        },

        getCompatibleChargers() {
            if (!this.selectedComponents.element) return this.chargers;

            const element = this.selectedComponents.element;
            const batteryVoltage = element.voltage * this.formData.series;
            const chargeVoltage = batteryVoltage * 1.2;

            return this.chargers.filter(charger => {
                const chemistryMatch = charger.chemistry === element.category;
                const voltageMatch = Math.abs(charger.voltage - chargeVoltage) <= 2;
                return chemistryMatch && voltageMatch;
            });
        },

        getCompatibleWires() {
            console.log('Поиск совместимых проводов...');
            console.log('Требуемый ток:', this.formData.loadCurrent);
            console.log('Все доступные провода:', this.wires);

            const compatibleWires = this.wires.filter(wire => {
                const isCompatible = wire.maxCurrent >= this.formData.loadCurrent;
                console.log(`Провод "${wire.name}": maxCurrent=${wire.maxCurrent}, совместим=${isCompatible}`);
                return isCompatible;
            });

            console.log('Совместимые проводы:', compatibleWires);
            return compatibleWires;
        },

        getCompatibleConnectors() {
            return this.connectors.filter(connector =>
                connector.maxCurrent >= this.formData.loadCurrent
            );
        },

        // Просмотр товара
        viewProduct(product) {
            console.log('Просмотр товара:', product);
            // Здесь можно добавить логику открытия карточки товара
        },

        // Удаление компонента
        removeComponent(componentType) {
            this.selectedComponents[componentType] = null;
            console.log('Удален компонент:', componentType);
        },

        // Выбор категории
        selectCategory(category) {
            this.selectedCategory = category;
        },

        // Обработка отправки формы
        handleSubmit() {
            if (this.totalPrice === 0) {
                alert('Пожалуйста, выберите компоненты для сборки');
                return;
            }

            const orderData = {
                configuration: this.formData,
                components: this.selectedComponents,
                characteristics: this.calculatedCharacteristics,
                totalPrice: this.totalPrice
            };

            console.log('Отправка заказа:', orderData);
            alert(`Заказ оформлен! Сумма: ${this.totalPrice} руб.`);

            // Здесь можно добавить отправку данных на сервер
            // this.sendOrderToServer(orderData);
        },

        // Сброс формы
        resetForm() {
            this.formData = {
                voltage: 36,
                capacity: 10,
                loadCurrent: 10.4,
                series: 10,
                parallel: 4,
                symmetricBms: false,
                considerStock: true,
                useHolders: false,
                useCompound: false,
                rowsCount: 1,
                chargeWireLength: 0,
                dischargeWireLength: 20
            };
            this.selectedComponents = {
                element: null,
                case: null,
                charger: null,
                bms: null,
                dischargeWire: null,
                dischargeConnector: null
            };
            this.selectedBatteryForm = null;
            this.searchQuery = '';
            this.selectedCategory = 'Все';

            console.log('Форма сброшена');
        },

        // Обработчик ошибок загрузки изображений
        handleImageError(event) {
            const target = event.target;
            target.src = 'https://via.placeholder.com/200x150/6c757d/ffffff?text=Изображение';
            target.alt = 'Изображение не найдено';
        }
    }
}
</script>

<style scoped>

</style>
