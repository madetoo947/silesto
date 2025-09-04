document.addEventListener('DOMContentLoaded', function () {
  // Находим все пункты меню, у которых есть подменю
  const menuParents = document.querySelectorAll('.menu-item-has-children > a')

  // Добавляем обработчик клика
  menuParents.forEach(function (menuItem) {
    menuItem.addEventListener('click', function (e) {
      // Отменяем переход по ссылке (если href="#")
      e.preventDefault()

      // Находим родительский <li> и подменю
      const parentLi = this.parentElement
      const subMenu = parentLi.querySelector('.sub-menu')

      // Закрываем все открытые sub-menu (опционально)
      document.querySelectorAll('.sub-menu').forEach(function (menu) {
        if (menu !== subMenu) {
          menu.classList.remove('active')
          menu.parentElement.classList.remove('active')
        }
      })

      // Открываем/закрываем текущее sub-menu
      parentLi.classList.toggle('active')
      subMenu.classList.toggle('active')
    })
  })
})

var $ = jQuery

// Глобальные переменные
let isLoading = false
let currentPage = 1
let hasMore = true
let currentCategory = 'main' // По умолчанию

// 1. Универсальный попап
function initUniversalPopup() {
  // const popupHTML = `
  //   <div id="universal-popup" class="universal-popup">
  //       <div class="popup-overlay"></div>
  //       <div class="popup-container">
  //           <button class="popup-close-btn">×</button>
  //           <div class="popup-content">
  //               <iframe class="popup-iframe" allowfullscreen></iframe>
  //               <div class="video-container"></div>
  //           </div>
  //       </div>
  //   </div>
  //   `;
  // $("body").append(popupHTML);

  // Обработчики закрытия
  $('.popup-close-btn, .popup-overlay').on('click', closePopup)
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closePopup()
  })
}

// Определяем текущую категорию на основе URL или класса body
function determineCurrentCategory() {
  const path = window.location.pathname

  if (path.includes('/rent/')) {
    return 'rent'
  } else if (path.includes('/human-videos/')) {
    return 'human_videos'
  }
  return 'main' // По умолчанию
}

function openPopup(content, type = 'iframe') {
  const $popup = $('#universal-popup')
  const $iframe = $popup.find('.popup-iframe')
  const $popupVideoBox = $popup.find('.popup-video-box')
  const $cinemaIframe = $popup.find('.popup-cinema-iframe')
  const $videoContainer = $popup.find('.video-container')
  const $formContainer = $popup.find('.form-container')
  const $formRequestContainer = $popup.find('.form-request-invoice')
  const $payContainer = $popup.find('.pay-container')

  // Очищаем предыдущий контент
  $popupVideoBox.hide()
  $iframe.attr('src', '').hide()
  $videoContainer.empty().hide()
  $formContainer.hide()
  $formRequestContainer.hide()
  $payContainer.hide()

  // Загружаем новый контент
  if (type === 'iframe') {
    $('.popup-close-btn').css('color', '#000')
    $('#universal-popup .popup-content').css('background', '#fff')
    $iframe.attr('src', content).show()
  } else if (type === 'video') {
    $('#universal-popup .popup-content').css('background', '#000')
    $('.popup-close-btn').css('color', '#fff')
    $videoContainer
      .html(
        `   <h2>${content.name}</h2>  
            <video controls autoplay style="width:100%; height:auto;">
                <source src="${content.trailerUrl}" type="video/mp4">
            </video>
        `
      )
      .show()
  } else if (type === 'form') {
    $('.popup-content').css('background', '#000')
    $('.popup-close-btn').css('color', '#fff')
    $formContainer.show()
  } else if (type === 'request') {
    $('.popup-content').css('background', '#000')
    $('.popup-close-btn').css('color', '#fff')
    $formRequestContainer.show()
  } else if (type === 'cinema-tariler') {
    $('.popup-content').css('background', '#000')
    $('.popup-close-btn').css('color', '#fff')
    $cinemaIframe.attr('src', content)
    $popupVideoBox.show()
  } else if (type === 'banner') {
    // Проверяем, сколько раз уже показывали попап
    const popupShowCount = localStorage.getItem('newPopupShowCount') || 0
    const maxShowCount = 3 // Максимальное количество показов

    // Если уже показали 3 или больше раз - выходим
    if (parseInt(popupShowCount) >= maxShowCount) {
      return
    }

    // Увеличиваем счетчик показов и сохраняем
    localStorage.setItem('newPopupShowCount', parseInt(popupShowCount) + 1)
    $('#universal-popup .video-container').css('background', 'transparent')
    $('#universal-popup .video-container').css('border', 'none')
    $('#universal-popup .popup-content').css('background', 'transparent')
    $('.popup-close-btn').css('color', '#fff')
    $videoContainer
      .html(
        `   <a href="${content.link}" target="_blank" rel="noopener noreferrer">
            <img src="${content.img}" 
                 alt="Banner" 
                 style="max-width: 100%; height: auto; display: block;">
        		</a>
        `
      )
      .show()
  } else if (type === 'pay-wimdow') {
    $('#universal-popup .popup-container').css('max-width', ' 700px')
    $('#universal-popup .popup-overlay').css(
      'background',
      ' rgba(0, 0, 0, 0.5)'
    )
    $payContainer
      .css('border', '1px solid #fff')
      .html(
        `<h2 style="width: 100%; margin-bottom: 15px;">Buy ${content.name}</h2>
        <p style="width: 100%;">Send donation ${content.price} (specify amount in the Other).<br>Please do not write comments. We will send access to your email.</p>
        <a href="https://app.lava.top/mifisto?donate=open" class="button red-color" style="max-width: 190px;padding: 8px 0px;" target="_blank" rel="noopener noreferrer">BUY NOW</a>
        `
      )
      .show()
  } else if (type === 'confirm_purchase') {
    $('#universal-popup .popup-container').css('max-width', '700px')
    $('#universal-popup .popup-overlay').css('background', 'rgba(0, 0, 0, 0.5)')

    // --- НАЧАЛО ИЗМЕНЕНИЯ HTML ---
    $payContainer
      .css('border', '1px solid #fff')
      .html(
        `<h2>${content.title}</h2>
         <div style="display: flex; gap: 20px; align-items: flex-start; margin-top: 15px;">
            <img src="${content.thumbnailUrl}" alt="${content.videoName}" style="width: 200px; height: auto; border: 1px solid #fff;">
            <div style="flex: 1;">
                <h3>${content.videoName}</h3>
                <p style="margin-top: 10px;">Buy this video for <strong>$${content.price}</strong>? ${content.text}</p>
            </div>
         </div>
         <div style="display: flex; gap: 10px; margin-top: 20px; justify-content: flex-end;">
            <button class="button grey-color" onclick="closePopup(); return false;" style="width: 120px;">Cancel</button>
            <button class="button red-color" id="confirm-purchase-btn" data-video-id="${content.videoId}" data-video-price="${content.price}" style="width: 120px;">Confirm</button>
         </div>
        `
      )
      .show()
    // --- КОНЕЦ ИЗМЕНЕНИЯ HTML ---
  }

  $popup.fadeIn(300)
  $('body').css('overflow', 'hidden')
}

function closePopup() {
  $('#universal-popup').fadeOut(300)
  $('body').css('overflow', '')
  $('#universal-popup .popup-container').css('max-width', ' 900px')
  $('#universal-popup .popup-overlay').css('background', ' rgba(0, 0, 0, 0.9)')

  // Пауза видео при закрытии
  const $video = $('#universal-popup video')
  if ($video.length) {
    $video[0].pause()
  }
}

// 2. Проверка ссылок Stripe (полная версия)
function checkLink(link) {
  const parts = link.split('/')
  const payID = parts[3]
  const status = UrlExists(
    'https://merchant-ui-api.stripe.com/payment-links/' + payID,
    function () {}
  ).status
  return status !== 404
}

function UrlExists(url, cb) {
  return $.ajax({
    async: false,
    url: url,
    dataType: 'text',
    type: 'POST',
    complete: function (xhr) {
      if (typeof cb === 'function') cb.apply(this, [xhr.status])
    },
  })
}

// // 3. Обработка платежей через data-атрибуты кнопок
// function processPayment($button, type = "buy") {
// 	const name = $button.data("name");
// 	const paymentMethod = paymentSettings.paymentMethod;

// 	// Определяем приоритеты обработки в зависимости от paymentMethod и type
// 	const usePaguelloFirst = (paymentMethod === "paguello" && type !== "payment-link") ||
// 		(paymentMethod === "stripe" && type === "payment-link");

// 	if (usePaguelloFirst) {
// 		// Сначала проверяем Paguello ссылки
// 		const paguelloLink = type === "rent" ? $button.data("paguello-rent") : $button.data("paguello");
// 		if (paguelloLink) {
// 			openPopup(paguelloLink, "iframe");
// 			return;
// 		}

// 		// Затем проверяем Stripe ссылки
// 		const stripeLinks = type === "rent" ? $button.data("rent-stripe") : $button.data("stripe");
// 		if (stripeLinks) {
// 			processStripeLinks(stripeLinks, name);
// 			return;
// 		}
// 	} else {
// 		// Сначала проверяем Stripe ссылки
// 		const stripeLinks = type === "rent" ? $button.data("rent-stripe") : $button.data("stripe");
// 		if (stripeLinks) {
// 			processStripeLinks(stripeLinks, name);
// 			return;
// 		}

// 		// Затем проверяем Paguello ссылки
// 		const paguelloLink = type === "rent" ? $button.data("paguello-rent") : $button.data("paguello");
// 		if (paguelloLink) {
// 			openPopup(paguelloLink, "iframe");
// 			return;
// 		}
// 	}

// 	showPaymentError(name);
// }

function processPayment($button, type = 'buy') {
  const name = $button.data('name')
  const paymentMethod = paymentSettings.paymentMethod

  // Получаем все возможные ссылки из data-атрибутов
  const paguelloLink =
    type === 'rent' ? $button.data('paguello-rent') : $button.data('paguello')
  const stripeLinks =
    type === 'rent' ? $button.data('rent-stripe') : $button.data('stripe')

  // Особый случай: для Stripe + аренды используем Paguello
  if (paymentMethod === 'stripe' && type === 'rent' && paguelloLink) {
    openPopup(paguelloLink, 'iframe')
    return
  }

  if (paymentMethod === 'stripe' && type === 'payment-link' && paguelloLink) {
    openPopup(paguelloLink, 'iframe')
    return
  }

  // BUTTON BUY NOW and Cinema
  if (paymentMethod === 'stripe' && stripeLinks) {
    processStripeLinks(stripeLinks, name)
    return
  }

  if (paymentMethod === 'paguello' && paguelloLink) {
    openPopup(paguelloLink, 'iframe')
    return
  }

  // Если ничего не подошло
  showPaymentError(name)
}

function processStripeLinks(linksString, name) {
  const links = linksString.split('\n').filter((link) => link.trim() !== '')
  console.log(links)
  let validLinkFound = false

  for (let i = 0; i < links.length; i++) {
    if (checkLink(links[i])) {
      window.open(links[i], '_blank')
      validLinkFound = true
      break
    }
  }

  if (!validLinkFound) {
    showPaymentError(name)
  }
}

function showPaymentError(name) {
  console.log(name)
}

// 4. Загрузка видео (обновленная версия с переносом data-атрибутов)
const loadVideos = async (category = currentCategory) => {
  if (isLoading || !hasMore) return

  isLoading = true
  $('#loading').addClass('active')

  try {
    const response = await $.ajax({
      url: silesto_ajax.ajaxurl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'load_videos',
        page: currentPage,
        category: category,
        security: silesto_ajax.nonce,
      },
    })

    if (response.success) {
      $('#video-container').append(response.data.html)
      currentPage++
      hasMore = response.data.has_more
    }
  } catch (error) {
    console.error('AJAX Error:', error)
  } finally {
    isLoading = false
    $('#loading').removeClass('active')
  }
}
// Поиск видео
const initVideoSearch = () => {
  const $form = document.getElementById('video-search-form')
  const $input = $form.querySelector('input[name="s"]')
  const $resultsContainer = $form.querySelector('.search-results')
  let searchTimeout

  $form.addEventListener('submit', (e) => {
    e.preventDefault()
    performSearch($input.value.trim())
  })

  $input.addEventListener('input', (e) => {
    clearTimeout(searchTimeout)
    const query = e.target.value.trim()

    if (query.length > 2) {
      searchTimeout = setTimeout(() => {
        performSearch(query)
      }, 500)
    } else {
      $resultsContainer.innerHTML = ''
      $resultsContainer.style.display = 'none'
    }
  })

  async function performSearch(query) {
    if (!query) {
      $resultsContainer.innerHTML = ''
      $resultsContainer.style.display = 'none'
      return
    }

    try {
      const response = await fetch(silesto_ajax.ajaxurl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'load_videos',
          search: query,
          category: currentCategory,
          security: silesto_ajax.nonce,
          page: 1, // Всегда первая страница для поиска
        }),
      })

      const data = await response.json()

      if (data.success) {
        $resultsContainer.innerHTML = data.data.html
        $resultsContainer.style.display = 'flex'

        // Инициализируем обработчики для новых элементов
        // initVideoButtons();
      }
    } catch (error) {
      console.error('Search error:', error)
    }
  }

  // Закрытие результатов при клике вне формы
  document.addEventListener('click', (e) => {
    if (!$form.contains(e.target)) {
      $resultsContainer.style.display = 'none'
    }
  })
}

// 5. Обработчики событий (обновленные для работы с data-атрибутами кнопок)
const setupEventHandlers = () => {
  let isMobile =
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    )

  let lastScrollPos = 0
  let lastScrollTime = 0

  $(window).on('scroll', function () {
    const now = Date.now()
    const currentPos = $(window).scrollTop()

    // Рассчитываем скорость скролла
    const speed = Math.abs(currentPos - lastScrollPos) / (now - lastScrollTime)

    // Динамический offset: чем быстрее скролл, тем раньше начинаем загрузку
    const dynamicOffset = 1000 + speed * 50
    if (
      $(window).scrollTop() + $(window).height() >=
      $(document).height() - dynamicOffset
    ) {
      loadVideos(currentCategory)
    }

    lastScrollPos = currentPos
    lastScrollTime = now
  })

  $(document)
    .on('click', '[href="#contact-form"]', function (e) {
      e.preventDefault()
      openPopup('', 'form')
    })
    .on('click', '[href="#cinema-trailer"]', function (e) {
      e.preventDefault()
      const cinamaTrailerUrl = $(this).data('trailer')
      openPopup(cinamaTrailerUrl, 'cinema-tariler')
    })
    .on('click', '.trailer-btn', function (e) {
      if ($(this).data('trailer')) {
        e.preventDefault()
        const name = $(this).closest('.video-item').find('h3').text()
        const trailerUrl = $(this).data('trailer')
        const content = {
          name: name,
          trailerUrl: trailerUrl,
        }
        openPopup(content, 'video')
      }
    })
    .on('click', '.button_buy', function (e) {
      e.preventDefault()
      var videoName = $(e.currentTarget)
        .closest('.video-item.conteiner')
        .find('h3')
        .text()
      const price = $(e.currentTarget).data('price')
      const content = {
        name: videoName,
        price: price,
      }
      // $('[name="nf-field-16"]').val(videoName)
      // window.location.replace('/how-to-buy/')
      // processPayment($(this), "buy");

      openPopup(content, 'pay-wimdow')
    })
    .on('click', '.button_subscribe', function (e) {
      e.preventDefault()
      window.location.replace('/how-to-buy/')
      // processPayment($(this), "subscribe");
    })
    .on('click', '.button_rent', function (e) {
      e.preventDefault()
      // processPayment($(this), "rent");
    })
    .on('click', '.payment-link', function (e) {
      e.preventDefault()
      processPayment($(this), 'payment-link')
    })
    .on('click', '.subscribe', function (e) {
      e.preventDefault()
      $('.two-group').toggleClass('active')
    })
}

// 6. Инициализация
const init = () => {
  // Определяем текущую категорию
  currentCategory = determineCurrentCategory()

  initUniversalPopup()
  setupEventHandlers()
}
function openImagePopup() {
  // Создаем HTML для изображения с ссылкой
  const imageHtml = `
        <a href="https://x.com/VeSilest" target="_blank" rel="noopener noreferrer">
            <img src="https://silesto.com/wp-content/uploads/2025/06/Banner.webp" 
                 alt="Banner" 
                 style="max-width: 100%; height: auto; display: block;">
        </a>
    `

  // Находим элементы попапа
  const popup = document.getElementById('universal-popup')
  const popupContent = document.querySelector('.popup-content')
  const closeBtn = document.querySelector('.popup-close-btn')

  // Очищаем содержимое попапа и добавляем наше изображение
  popupContent.innerHTML = ''
  popupContent.insertAdjacentHTML('beforeend', imageHtml)

  // Показываем попап
  popup.style.display = 'block'

  // Добавляем обработчик закрытия
  closeBtn.addEventListener('click', function () {
    popup.style.display = 'none'
  })

  // Закрытие при клике на оверлей
  document
    .querySelector('.popup-overlay')
    .addEventListener('click', function () {
      popup.style.display = 'none'
    })
}
// Запуск при загрузке
$(document).ready(function () {
  init()
  loadVideos(currentCategory)
  if (window.location.pathname === '/') {
    initVideoSearch()
  }

  // Обновленная функция для инициализации кнопок
  // function initVideoButtons() {
  // 	document.querySelectorAll('.button_buy, .button_rent, .trailer-btn').forEach(btn => {
  // 		btn.addEventListener('click', setupEventHandlers);
  // 	});
  // }

  // var popup = $('#popup-message');
  // setTimeout(function () {
  // 	popup.css('display', 'block');

  // 	// Автоматическое закрытие через 10 секунд
  // 	setTimeout(function () {
  // 		popup.css('display', 'none');
  // 	}, 10000);
  // }, 5000);

  // // Закрытие по крестику
  // $('#popup-close').on('click', () => {
  // 	popup.css('display', 'none');
  // });

  // setTimeout(function () {
  // openPopup({img: 'https://silesto.com/wp-content/uploads/2025/06/Banner.webp', link: 'https://x.com/VeSilest'}, 'banner')
  // }, 5000);
})

// --- НАЧАЛО ЛОГИКИ ПОКУПКИ С БАЛАНСА (Версия 2.1 с исправленной ссылкой) ---
jQuery(document).ready(function ($) {
  var currentPurchaseButton = null

  // 1. При клике на кнопку "Buy for $X" - открываем ваш попап для подтверждения
  $(document).on('click', '.button_buy_with_balance', function (e) {
    e.preventDefault()
    currentPurchaseButton = $(this)
    var videoId = currentPurchaseButton.data('video-id')
    var videoPrice = currentPurchaseButton.data('video-price')
    var videoTitle = currentPurchaseButton
      .closest('.video-item')
      .find('h3')
      .text()
    var thumbnailUrl = currentPurchaseButton
      .closest('.video-item')
      .find('.video-thumbnail img')
      .attr('src')

    var popupContent = {
      title: 'Confirm Purchase',
      text: 'This amount will be deducted from your balance.',
      price: videoPrice,
      videoId: videoId,
      videoName: videoTitle,
      thumbnailUrl: thumbnailUrl,
    }

    openPopup(popupContent, 'confirm_purchase')
  })

  // 2. При клике на кнопку "Confirm" ВНУТРИ попапа - отправляем AJAX
  $(document).on('click', '#confirm-purchase-btn', function () {
    var $confirmButton = $(this)
    var videoId = $confirmButton.data('video-id')
    var videoPrice = $confirmButton.data('video-price')

    // --- ИЗМЕНЕНИЕ ЗДЕСЬ: Получаем ссылку на пост из data-атрибута ---
    var postLink = currentPurchaseButton.data('post-link')

    $confirmButton.text('Processing...').prop('disabled', true)
    currentPurchaseButton.text('Processing...').prop('disabled', true)

    $.ajax({
      url: silesto_ajax.ajaxurl,
      type: 'POST',
      data: {
        action: 'purchase_with_balance',
        nonce: silesto_ajax.nonce,
        video_id: videoId,
      },
      success: function (response) {
        if (response.success) {
          $('.popup-content .pay-container').html(
            '<h2>Success!</h2><p>' + response.data.message + '</p>'
          )
          $('.user-balance').text('Balance: $' + response.data.new_balance)

          // --- ИЗМЕНЕНИЕ ЗДЕСЬ: Превращаем кнопку в рабочую ссылку ---
          currentPurchaseButton
            .removeClass('button_buy_with_balance red-color')
            .addClass('grey-color')
            .text('WATCH')
            .attr('href', postLink) // <-- Устанавливаем правильный URL

          currentPurchaseButton.off('click').prop('disabled', false)

          setTimeout(function () {
            closePopup()
          }, 2000)
        } else {
          $('.popup-content .pay-container')
            .find('p')
            .first()
            .html(
              '<span style="color: red;">' + response.data.message + '</span>'
            )
          $confirmButton.text('Confirm').prop('disabled', false)
          currentPurchaseButton
            .text('Buy for $' + videoPrice)
            .prop('disabled', false)
        }
      },
      error: function () {
        $('.popup-content .pay-container')
          .find('p')
          .first()
          .html(
            '<span style="color: red;">An unexpected error occurred. Please try again.</span>'
          )
        $confirmButton.text('Confirm').prop('disabled', false)
        currentPurchaseButton
          .text('Buy for $' + videoPrice)
          .prop('disabled', false)
      },
    })
  })
})
// --- КОНЕЦ ЛОГИКИ ПОКУПКИ С БАЛАНСА ---
