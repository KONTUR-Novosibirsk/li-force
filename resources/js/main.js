import Swiper from 'swiper';
import {Navigation, Pagination, Controller, EffectFade, Autoplay, Thumbs} from 'swiper/modules';

Swiper.use([Navigation, Pagination, Controller, EffectFade, Autoplay, Thumbs]);

import 'jquery-ui';
import "jquery-ui/ui/widgets/mouse";
import "jquery-ui/ui/widgets/slider";
import 'jquery-ui-touch-punch';

import "jquery-ui/themes/base/all.css";

import '@fancyapps/fancybox';
import '@fancyapps/fancybox/dist/jquery.fancybox.css';
import { storageManager } from './utils/storageManager.js';

$(function () {
    const mainSlider = new Swiper('.main-slider__content', {
        speed: 400,
        loop: true,
        spaceBetween: 50,
        navigation: {
            nextEl: '.main-slider__next',
            prevEl: '.main-slider__perv',
        },
        pagination: {
            el: '.main-slider__pagination',
            clickable: true,
        },
    });
    const categoriesSlider = new Swiper('.categories-list .swiper', {
        slidesPerView: 'auto',
        loop: true,
        navigation: {
            nextEl: '.categories-list .swiper-next',
            prevEl: '.categories-list .swiper-prev',
        },
        pagination: {
            el: '.categories-list .swiper-pagination',
            clickable: true,
        },
    });
    const aboutSlider = new Swiper('.about-list .swiper', {
        slidesPerView: 'auto',
        loop: true,
        navigation: {
            nextEl: '.about-list .swiper-next',
            prevEl: '.about-list .swiper-prev',
        },
        pagination: {
            el: '.about-list .swiper-pagination',
            clickable: true,
        },
    });
    const worksSlider = new Swiper('.works__swiper', {
        slidesPerView: 'auto',
        spaceBetween: 24,
        // loop: true,

        breakpoints:{
            0: {
                spaceBetween: 12,
            },
            768:{
                spaceBetween: 24,
            }
        }
    });
    const hitSlider = new Swiper('.hit-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 20,
        loop: true,
        navigation: {
            nextEl: '.hit-list .swiper-next',
            prevEl: '.hit-list .swiper-prev',
        },
        pagination: {
            el: '.hit-swiper .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            0: {
                spaceBetween: 12,
            },
            768: {
                spaceBetween: 16,
            },
            1400:{
                spaceBetween: 20,
            }
        }
    });

    const newsSlider = new Swiper('.news-swiper', {
        slidesPerView: 4,
        spaceBetween: 26,
        loop: true,
        navigation: {
            nextEl: '.news-swiper_next',
            prevEl: '.news-swiper_prev',
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1.5,
                spaceBetween: 8,
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 16,
            },
            767:{
                slidesPerView: 3,
                spaceBetween: 16,
            },
            // when window width is >= 480px
            1200: {
                slidesPerView: 4,
            },
            // when window width is >= 640px
            1400: {
                slidesPerView: 4,
            }
        },
    });
    const promotionSlider = new Swiper('.promotion-list', {
        slidesPerView: 2,
        spaceBetween: 40,
        speed: 400,
        loop: true,
        autoplay: {
            delay: 3000,
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1,
            },
            // when window width is >= 480px
            1200: {
                slidesPerView: 1,
            },
            // when window width is >= 640px
            1400: {
                slidesPerView: 2,
            }
        }
    });


    $('.header_bottom-login__link').click(function () {
        $('.login-popup').fadeToggle();
    })
})

$(document).ready(function () {
    let page = 1; // текущая страница
    let perPage = 5; // количество блоков на странице
    let totalPages = Math.ceil($('.hit-item').length / perPage); // общее количество страниц
    let totalItems = $('.hit-item').length; // общее количество блоков
    let shownItems = perPage; // количество показанных блоков

    // показать только первые 10 блоков
    $('.hit-item').slice(0, perPage).show();

    // добавить обработчик события click на кнопку "Показать еще"
    $('.hit-list__more').click(function () {
        // проверить, не достигнут ли конец списка блоков
        if (page < totalPages) {
            // скрыть все блоки на текущей странице
            $('.hit-item').slice(page * perPage, (page + 1) * perPage).hide();
            // увеличить номер текущей страницы
            page++;
            // показать следующую страницу блоков
            $('.hit-item').slice(page * perPage - perPage, page * perPage).show();
            // обновить количество показанных блоков
            shownItems += perPage;
            // проверить, не нужно ли спрятать кнопку "Показать еще"
            if (shownItems >= totalItems) {
                $('.hit-list__more').hide();
            }
        }
    });
});

$(document).ready(function () {
    $('.mobile-menu__item').click(function (event) {
        if ($(this).hasClass('fake-current')) {
            $(".mobile-menu__item").removeClass('hide-current');
            $(".mobile-menu__item").removeClass('fake-current');
        } else {
            $(".mobile-menu__item").removeClass('fake-current');
            $(".mobile-menu__item").addClass('hide-current');
            $(this).addClass('fake-current');
            $(this).removeClass('hide-current');
        }
    });

    $('.catalog-menu__close, .modal-menu__close').click(function (event) {
        $(".mobile-menu__item").removeClass('hide-current');
        $(".mobile-menu__item").removeClass('fake-current');
    });
});

$(document).ready(function () {
    $('.menu-catalog, .catalog-menu__close').click(function (event) {
        $('.catalog-menu').toggleClass('show');
        $('.modal-menu').removeClass('show');
        if ($('.catalog-menu').hasClass('show')) {
            $('html').addClass('lock')
        } else {
            $('html').removeClass('lock')
        }
    });
    $('.menu-list, .modal-menu__close').click(function (event) {
        $('.modal-menu').toggleClass('show');
        $('.catalog-menu').removeClass('show');
        if ($('.modal-menu').hasClass('show')) {
            $('html').addClass('lock')
        } else {
            $('html').removeClass('lock')
        }
    });
});

$(function () {
    $('.header-burger, .burger-menu__close').click(function (event) {
        $('.header-burger, .burger-menu').toggleClass('active');
        $('html').toggleClass('lock')
    });
    $('.burger-menu__shadow').click(function (event) {
        $('.burger-menu').removeClass('active');
        $('html').removeClass('lock');
        $('.header-burger').removeClass('active');
    });
})

$(function () {
    $('.sub-menu__arrow').click(function () {
        $(this).toggleClass('active')
        $(this).siblings('.sub-menu').slideToggle();
    })
})

$(function () {
    $('.header-search__item').click(function () {
        $('.header-search__popup').fadeToggle();
    })
})
$(function () {
    $('.compared-characteristics__heading').click(function () {
        $(this).toggleClass('active')
        $(this).siblings('.compared-characteristics__body').slideToggle();
    })
})

$(function () {
    const productSliderNav = new Swiper('.product-slider_navigate', {
        slidesPerView: 'auto',
        spaceBetween: 12,
        direction: "vertical",

    });
    const productSliderMain = new Swiper('.product-slider_main', {
        slidesPerView: 1,
        spaceBetween: 15,
        // loop: true,
        thumbs: {
            swiper: productSliderNav,
        },
        navigation: {
            nextEl: '.product-next',
            prevEl: '.product-prev',
        },
        pagination: {
            el: '.product-slider_main_pagination'
        }
    });

})

$(function () {
    const similarSlider = new Swiper('#product-similar .product-similar__swiper', {
        slidesPerView: 'auto',
        spaceBetween: 12,
        speed: 400,
        loop: true,
        navigation: {
            nextEl: '#product-similar .swiper_next',
            prevEl: '#product-similar .swiper_prev',
        },
        pagination: {
            el: '#product-similar .swiper-pagination',
            clickable: true,
        },
    });
    const buyWtihSlider = new Swiper('#product-buy-with .product-similar__swiper', {
        slidesPerView: 'auto',
        spaceBetween: 12,
        speed: 400,
        loop: true,
        navigation: {
            nextEl: '#product-buy-with .swiper_next',
            prevEl: '#product-buy-with .swiper_prev',
        },
        pagination: {
            el: '#product-buy-with .swiper-pagination',
            clickable: true,
        },
    });
})

document.addEventListener('DOMContentLoaded', function () {
    // Инициализация основного слайдера товаров
    const productsSwiper = new Swiper('.compared-swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.compared-btn-next',
            prevEl: '.compared-btn-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            1000: {
                slidesPerView: 3,
            },
            1400: {
                slidesPerView: 4,
            },
        }
    });

    // Автоматически находим все слайдеры характеристик по data-атрибуту
    const characteristicsSwipers = [];
    const characteristicElements = document.querySelectorAll('[data-swiper="characteristics"]');

    characteristicElements.forEach(element => {
        const swiper = new Swiper(element, {
            slidesPerView: 4,
            spaceBetween: 20,
            allowTouchMove: false,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                1000: {
                    slidesPerView: 3,
                },
                1400: {
                    slidesPerView: 4,
                },
            }
        });
        characteristicsSwipers.push(swiper);
    });

    // Функция синхронизации
    function syncSwipers(targetIndex, sourceSwiper) {
        // Синхронизируем основной слайдер (если источник не он)
        if (sourceSwiper !== productsSwiper && productsSwiper.activeIndex !== targetIndex) {
            productsSwiper.slideTo(targetIndex);
        }

        // Синхронизируем все слайдеры характеристик
        characteristicsSwipers.forEach(swiper => {
            if (swiper !== sourceSwiper && swiper.activeIndex !== targetIndex) {
                swiper.slideTo(targetIndex);
            }
        });
    }

    // События для основного слайдера
    productsSwiper.on('slideChange', function () {
        syncSwipers(productsSwiper.activeIndex, productsSwiper);
    });

    // События для всех слайдеров характеристик
    characteristicsSwipers.forEach(swiper => {
        swiper.on('slideChange', function () {
            syncSwipers(swiper.activeIndex, swiper);
        });
    });
});
$(function () {
    $('.product-tabs__item:first-child').addClass('active')
    $('.product-characteristic').addClass('active')
    $('.product-tabs__item').click(function () {
        $('.product-tabs__item').removeClass('active');
        $('.product-tabs__content').removeClass('active');
        $(this).addClass('active');
        $(`.product-tabs__content[data-tab="${this.dataset.tab}"]`).addClass('active');
    });
})


document.addEventListener('DOMContentLoaded', function () {
    const showAllBtn = document.getElementById('showAllBtn');
    if (showAllBtn) {
        showAllBtn.addEventListener('click', toggleCharacteristics);
    }
});

function toggleCharacteristics() {
    const hiddenItems = document.querySelectorAll('.hidden-characteristic');
    const showAllBtn = document.querySelector('.show-all-btn');

    hiddenItems.forEach(item => {
        if (item.style.display === 'none' || !item.style.display) {
            item.style.display = 'flex'; //
        } else {
            item.style.display = 'none';
        }
    });


    showAllBtn.classList.toggle('active');


    if (showAllBtn.classList.contains('active')) {
        showAllBtn.innerHTML = 'Свернуть характеристики <svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.312827 9.73363C0.126198 9.56523 0.0214744 9.33754 0.0214744 9.10022C0.0214744 8.8629 0.126198 8.63521 0.312827 8.46681L4.53106 5.02287L0.316989 1.54404C0.21913 1.46241 0.140642 1.36395 0.0862002 1.25454C0.0317593 1.14513 0.00250769 1.02701 0.000153542 0.907238C-0.00220013 0.787464 0.0224166 0.668489 0.0725245 0.557426C0.122633 0.446363 0.19717 0.345475 0.291761 0.26081C0.386352 0.176146 0.499026 0.109437 0.623036 0.0646523C0.747046 0.019868 0.879834 -0.00207347 1.01352 0.000154112C1.1472 0.0023817 1.27904 0.0287433 1.40111 0.077628C1.52317 0.126513 1.63296 0.19695 1.72397 0.284707L6.66728 4.36579C6.67981 4.37701 6.69655 4.38074 6.70907 4.39197C6.89545 4.56075 7 4.78856 7 5.02599C7 5.26341 6.89545 5.49123 6.70907 5.66001L1.73094 9.73112C1.63882 9.816 1.52873 9.8835 1.40705 9.92968C1.28538 9.97586 1.15457 9.99977 1.02244 10C0.890297 10.0002 0.759434 9.9768 0.637561 9.93105C0.515688 9.8853 0.405313 9.81819 0.312827 9.73363Z" fill="#333333"/>\n' +
            '                                </svg>';
    } else {
        showAllBtn.innerHTML = 'Все характеристики <svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.312827 9.73363C0.126198 9.56523 0.0214744 9.33754 0.0214744 9.10022C0.0214744 8.8629 0.126198 8.63521 0.312827 8.46681L4.53106 5.02287L0.316989 1.54404C0.21913 1.46241 0.140642 1.36395 0.0862002 1.25454C0.0317593 1.14513 0.00250769 1.02701 0.000153542 0.907238C-0.00220013 0.787464 0.0224166 0.668489 0.0725245 0.557426C0.122633 0.446363 0.19717 0.345475 0.291761 0.26081C0.386352 0.176146 0.499026 0.109437 0.623036 0.0646523C0.747046 0.019868 0.879834 -0.00207347 1.01352 0.000154112C1.1472 0.0023817 1.27904 0.0287433 1.40111 0.077628C1.52317 0.126513 1.63296 0.19695 1.72397 0.284707L6.66728 4.36579C6.67981 4.37701 6.69655 4.38074 6.70907 4.39197C6.89545 4.56075 7 4.78856 7 5.02599C7 5.26341 6.89545 5.49123 6.70907 5.66001L1.73094 9.73112C1.63882 9.816 1.52873 9.8835 1.40705 9.92968C1.28538 9.97586 1.15457 9.99977 1.02244 10C0.890297 10.0002 0.759434 9.9768 0.637561 9.93105C0.515688 9.8853 0.405313 9.81819 0.312827 9.73363Z" fill="#333333"/>\n' +
            '                                </svg>';
    }
}

// -----------------------------------------------------------------counter----------------------------------------------------

document.addEventListener('DOMContentLoaded', function () {

    const counter = document.getElementById('counter');

    if (!counter) return;

    const minusBtn = counter.querySelector('.minus');
    const plusBtn = counter.querySelector('.plus');
    const counterValue = counter.querySelector('.counter-value');

    let value = parseInt(counterValue.textContent);

    counter.dataset.count = value;

    minusBtn.addEventListener('click', () => {

        if (value <= 1) {
            minusBtn.classList.add('disabled')
        } else {
            value--;
            minusBtn.classList.remove('disabled');
            counterValue.textContent = value;
        }

        counter.dataset.count = value;
    });

    plusBtn.addEventListener('click', () => {
        minusBtn.classList.remove('disabled');

        value++;
        counterValue.textContent = value;

        counter.dataset.count = value;
    });


})

//----------------------------------------------- review --------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const reviewTexts = document.querySelectorAll('.js-review-text');


    reviewTexts.forEach(text => {
        const readMoreButton = text.nextElementSibling.nextElementSibling;
        readMoreButton.addEventListener('click', function (){
            text.classList.toggle('expanded');
            if (text.classList.contains('expanded')) {
                readMoreButton.innerHTML = 'Скрыть';
            } else {
                readMoreButton.innerHTML = 'Читать полностью';
            }
        })

    })
});

//--------------------------------mobile submenu---------------------

$(function () {
    $('.has-sub-menu-arrow').click(function () {
        $(this).siblings('.sub-menu-wrapper').toggleClass('active')
    })
})

$(function () {
    $('.sub-menu-top svg').click(function () {
        $(this).parent().parent('.sub-menu-wrapper').removeClass('active')
    })
})

$(function () {
    $('.footer-menu .has-sub-menu-arrow').click(function () {
        $(this).toggleClass('active')
        $(this).siblings('.sub-menu-wrapper').slideToggle()
    })
})


//-----------------compared-products-------------------
// document.addEventListener('DOMContentLoaded', function () {
//     const comparedProducts = document.querySelector('.compared-products');
//     const comparedSlider = document.querySelector('.compared-slider');
//
//     // Порог скролла для активации фиксации (можно настроить)
//     const scrollThreshold = 500;
//
//     function handleScroll() {
//         if (window.scrollY > scrollThreshold) {
//             comparedProducts.classList.add('fixed');
//         } else {
//             comparedProducts.classList.remove('fixed');
//         }
//     }
//
//     // Слушаем событие скролла
//     window.addEventListener('scroll', handleScroll);
//
//     // Вызываем сразу на случай, если страница уже прокручена
//     handleScroll();
// });


//------------------------------FAQ--------------------------------

$(function () {
    $('.faq__item:first-child .faq__item-ttl').addClass('active');
    $('.faq__item:first-child .faq__item-desc').slideDown();

    $('.faq__item-ttl').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('.faq__item-desc').slideUp();
        } else {
            $('.faq__item-ttl').removeClass('active');
            $('.faq__item-desc').slideUp();

            $(this).addClass('active');
            $(this).siblings('.faq__item-desc').slideDown();
        }
    });
});




// -----------------------compare and select functions---------------
document.addEventListener('DOMContentLoaded', () => {
    initializeSelectButtons();
    initializeCompareButtons();
    storageManager.updateHeaderCounters();

    // Слушаем обновления и обновляем состояние кнопок
    window.addEventListener('favoritesUpdated', () => {
        storageManager.updateHeaderCounters();
        updateSelectButtonsState(); // Обновляем состояние кнопок избранного
    });

    window.addEventListener('compareUpdated', () => {
        storageManager.updateHeaderCounters();
        updateCompareButtonsState(); // Обновляем состояние кнопок сравнения
    });
});
// Функция для обновления состояния всех кнопок избранного
function updateSelectButtonsState() {
    const links = document.querySelectorAll('.select-btn');
    const favorites = storageManager.getFavorites();

    links.forEach((link) => {
        const productId = link.getAttribute('data-product-id');
        const isFavorite = favorites.some(item => item.id == productId);

        if (isFavorite) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Функция для обновления состояния всех кнопок сравнения
function updateCompareButtonsState() {
    const links = document.querySelectorAll('.compare-btn');
    const compare = storageManager.getCompare();

    links.forEach((link) => {
        const productId = link.getAttribute('data-product-id');
        const inCompare = compare.some(item => item.id == productId);

        if (inCompare) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}
function initializeCompareButtons() {
    const links = document.querySelectorAll('.compare-btn');

    if (!links.length) return;

    links.forEach((link) => {
        const productId = link.getAttribute('data-product-id');
        const compare = storageManager.getCompare();
        const inCompare = compare.some(item => item.id == productId);

        if (inCompare) {
            link.classList.add('active');
        }

        link.addEventListener('click', handleCompareClick);
    });
}

async function handleCompareClick(e) {
    e.preventDefault();
    e.stopPropagation();

    const productId = this.getAttribute('data-product-id');
    const url = '/compare/sravnenie-tovarov/';

    // Проверяем текущее состояние
    const compare = storageManager.getCompare();
    const isCurrentlyInCompare = compare.some(item => item.id == productId);

    if (isCurrentlyInCompare) {
        // Удаляем из сравнения
        storageManager.removeFromCompare(productId);
        this.classList.remove('active');
        showNotification('Товар удален из сравнения');
    } else {
        // Добавляем в сравнение
        try {
            const productData = await fetchProductData(url, productId);
            if (productData && productData.length > 0) {
                storageManager.addToCompare(productData[0]);
                this.classList.add('active');
                showNotification('Товар добавлен для сравнения');
            }
        } catch (error) {
            console.error('Error handling compare click:', error);
        }
    }
}

function initializeSelectButtons() {
    const links = document.querySelectorAll('.select-btn');

    if (!links.length) return;

    links.forEach((link) => {
        const productId = link.getAttribute('data-product-id');
        const favorites = storageManager.getFavorites();
        const isFavorite = favorites.some(item => item.id == productId);

        if (isFavorite) {
            link.classList.add('active');
        }

        link.addEventListener('click', handleSelectClick);
    });
}

async function handleSelectClick(e) {
    e.preventDefault();
    e.stopPropagation();

    const productId = this.getAttribute('data-product-id');
    const url = '/likes/add/';

    // Проверяем текущее состояние
    const favorites = storageManager.getFavorites();
    const isCurrentlyFavorite = favorites.some(item => item.id == productId);

    if (isCurrentlyFavorite) {
        // Удаляем из избранного
        storageManager.removeFromFavorites(productId);
        this.classList.remove('active');
        showNotification('Товар удален из избранного');
    } else {
        // Добавляем в избранное
        try {
            const productData = await fetchProductData(url, productId);
            if (productData && productData.length > 0) {
                storageManager.addToFavorites(productData[0]);
                this.classList.add('active');
                showNotification('Товар добавлен в избранное');
            }
        } catch (error) {
            console.error('Error handling select click:', error);
        }
    }
}

async function fetchProductData(url, productId) {
    try {
        const response = await axios.get(`${url}${productId}`);
        return response.data;
    } catch (error) {
        console.error('Error fetching product data:', error);
        throw error;
    }
}

function showNotification(message) {
    console.log(message);
    // Реализация уведомлений
}

function updateAllCounters() {
    const favoriteCounters = document.querySelectorAll('.select-counter');
    const favoritesCount = storageManager.getFavorites().length;
    favoriteCounters.forEach(counter => {
        counter.textContent = favoritesCount;
    });

    const compareCounters = document.querySelectorAll('.compare-counter');
    const compareCount = storageManager.getCompare().length;
    compareCounters.forEach(counter => {
        counter.textContent = compareCount;
    });
}

window.addEventListener('favoritesUpdated', updateAllCounters);
window.addEventListener('compareUpdated', updateAllCounters);



//-----------config-selector--------------------

class CustomSelect {
    constructor(container, hiddenSelectId) {
        if (!container) return;

        this.container = container;
        this.trigger = container.querySelector('.config-select-trigger');
        this.dropdown = container.querySelector('.config-select-dropdown');
        this.options = container.querySelectorAll('.config-select-option');
        this.hiddenSelect = document.getElementById(hiddenSelectId);
        this.isOpen = false;
        this.currentValue = null;

        this.init();
    }

    init() {

        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown();
        });


        this.options.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                if (option.classList.contains('disabled')) return;
                this.selectOption(option);
            });
        });


        document.addEventListener('click', () => {
            this.closeDropdown();
        });


        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeDropdown();
            }
        });

        const selectedOption = this.container.querySelector('.config-select-option.selected');
        if (selectedOption) {
            this.setSelectedValue(selectedOption);
        }
    }

    toggleDropdown() {
        if (this.isOpen) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        this.dropdown.classList.add('show');
        this.trigger.classList.add('open');
        this.isOpen = true;
    }

    closeDropdown() {
        this.dropdown.classList.remove('show');
        this.trigger.classList.remove('open');
        this.isOpen = false;
    }

    selectOption(option) {

        this.options.forEach(opt => {
            opt.classList.remove('selected');
        });


        option.classList.add('selected');


        const selectedText = option.textContent;
        this.trigger.textContent = selectedText;


        const value = option.getAttribute('data-value');
        if (this.hiddenSelect) {
            this.hiddenSelect.value = value;
            const changeEvent = new Event('change', { bubbles: true });
            this.hiddenSelect.dispatchEvent(changeEvent);
        }

        this.currentValue = value;

        const customEvent = new CustomEvent('customSelectChange', {
            detail: { value: value, text: selectedText }
        });
        this.container.dispatchEvent(customEvent);

        this.closeDropdown();
    }

    setSelectedValue(option) {
        const selectedText = option.textContent;
        this.trigger.textContent = selectedText;
        const value = option.getAttribute('data-value');
        this.currentValue = value;

        if (this.hiddenSelect) {
            this.hiddenSelect.value = value;
        }
    }

    getValue() {
        return this.currentValue;
    }

    setValue(value) {
        const option = Array.from(this.options).find(opt => opt.getAttribute('data-value') === String(value));
        if (option && !option.classList.contains('disabled')) {
            this.selectOption(option);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const customSelect = new CustomSelect(
        document.getElementById('configSelect1'),
        'nativeSelect1'
    );
    const container = document.getElementById('configSelect1');

    if (!container) return;

    container.addEventListener('customSelectChange', (e) => {
    });

});


$(function () {
    function isMobile() {
        return window.innerWidth <= 1100;
    }

    $('.products-content_left h3').click(function () {
        if (!isMobile()) return;

        $(this).toggleClass('active')
        $(this).siblings('.products-menu').slideToggle();
    })
})


// -------------------Правовая информация--------------------
$(function () {
    $('.rules-tabs__item:first-child').addClass('active')
    $('.rules-tabs__content:first-child').addClass('active')
    $('.rules__content-title:first-child').addClass('active')
    $('.rules-tabs__item').click(function () {
        $('.rules-tabs__item').removeClass('active');
        $('.rules-tabs__content').removeClass('active');
        $('.rules__content-title').removeClass('active')
        $(this).addClass('active');
        $(`.rules-tabs__content[data-tab="${this.dataset.tab}"]`).addClass('active');
        $(`.rules__content-title[data-tab="${this.dataset.tab}"]`).addClass('active');
    });


    $('.rules-acc__title').click(function (){
        $(this).toggleClass('active');
        $(this).siblings('.rules-acc__content').slideToggle();
    })
})


