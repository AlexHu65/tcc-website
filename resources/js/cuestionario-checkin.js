(function () {
    var form = document.querySelector('[data-checkin-form]');
    var result = document.querySelector('[data-checkin-result]');

    if (!form || !result) return;

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        var questionCards = Array.prototype.slice.call(form.querySelectorAll('.question-card'));
        var total = 0;
        var riskFlag = false;

        questionCards.forEach(function (card) {
            var checked = card.querySelector('input[type="radio"]:checked');
            var value = checked ? Number(checked.value) : 0;
            total += value;

            if (card.getAttribute('data-category') === 'risk' && value > 0) {
                riskFlag = true;
            }
        });

        var title = '';
        var message = '';

        if (total <= 5) {
            title = 'Bienestar relativamente estable';
            message =
                'Tus respuestas sugieren que, en este momento, tu bienestar emocional parece relativamente estable. Aun así, siempre es valioso seguir cuidando tu salud mental.';
        } else if (total <= 12) {
            title = 'Señales de desgaste emocional';
            message =
                'Tus respuestas sugieren algunas señales de desgaste emocional. Podría ser un buen momento para detenerte, escucharte y explorar qué necesitas.';
        } else {
            title = 'Malestar emocional importante';
            message =
                'Tus respuestas sugieren un nivel importante de malestar emocional. Una valoración profesional puede ayudarte a comprender lo que estás viviendo y construir herramientas concretas.';
        }

        if (riskFlag) {
            result.classList.add('alert');
            message +=
                '<br><br><strong>Importante:</strong> marcaste una respuesta relacionada con pensamientos de hacerte daño o no querer estar aquí. Este resultado merece atención inmediata. Busca apoyo con una persona de confianza o servicios de emergencia de tu localidad.';
        } else {
            result.classList.remove('alert');
        }

        result.innerHTML =
            '<strong>' +
            title +
            '</strong><br>' +
            message +
            '<br><br><small>Puntaje orientativo: ' +
            total +
            '</small>';
        result.hidden = false;
        result.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    form.addEventListener('reset', function () {
        result.hidden = true;
        result.innerHTML = '';
        result.classList.remove('alert');
    });
})();
