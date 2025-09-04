jQuery(document).ready(function ($) {
  // Назначаем обработчик клика на кнопки с классом 'recharge-amount-btn'
  $('.recharge-amount-btn').on('click', function (event) {
    event.preventDefault()

    const clickedButton = $(this)
    const amount = clickedButton.data('amount')
    const offerId = clickedButton.data('offer-id') // Получаем ID оффера
    const messageDiv = $('#silesto-recharge-message')

    // Блокируем все кнопки на время запроса
    $('.recharge-amount-btn').prop('disabled', true)
    clickedButton.text('Подождите...')
    messageDiv.hide()

    const formData = {
      action: 'silesto_initiate_payment',
      amount: amount, // Передаем сумму
      offerId: offerId, // Передаем ID оффера
      nonce: $('#silesto_recharge_nonce').val(),
    }

    $.ajax({
      type: 'POST',
      url: silesto_ajax.ajax_url,
      data: formData,
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          // Успех: перенаправляем на оплату
          window.location.href = response.data.payment_url
        } else {
          // Ошибка от WordPress
          showError(response.data.message)
        }
      },
      error: function () {
        // Ошибка сети
        showError('Ошибка сети. Пожалуйста, попробуйте еще раз.')
      },
    })
  })

  function showError(message) {
    const messageDiv = $('#silesto-recharge-message')
    messageDiv
      .css('color', 'red')
      .text('Ошибка: ' + message)
      .show()

    // Разблокируем все кнопки и возвращаем им исходный текст
    $('.recharge-amount-btn').each(function () {
      const amount = $(this).data('amount')
      $(this)
        .prop('disabled', false)
        .text('Пополнить на ' + amount + ' $')
    })
  }
})
