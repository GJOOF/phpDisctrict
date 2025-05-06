<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание объекта с модальным окном</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Стили для кнопки */
        .create-button {
            background-color: #4CAF50; /* Зелёный цвет */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .create-button:hover {
            background-color: #45a049; /* Более тёмный зелёный при наведении */
        }

        /* Стили для модального окна */
        .modal {
            display: none; /* Скрыто по умолчанию */
            position: fixed; /* Окно фиксированное */
            z-index: 1; /* На переднем плане */
            left: 0;
            top: 0;
            width: 100%; /* Полная ширина */
            height: 100%; /* Полная высота */
            overflow: auto; /* Включает прокрутку, если нужно */
            background-color: rgb(0,0,0); /* Чёрный фон */
            background-color: rgba(0,0,0,0.4); /* Чёрный с прозрачностью */
            padding-top: 60px; /* Отступ сверху */
        }

        /* Стили для содержимого модального окна */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% сверху и центрирование */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ширина */
        }

        /* Стили для кнопок с иконками */
        .option-button {
            background-color: #e7e7e7;
            border: none;
            color: black;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            border-radius: 4px;
            width: 100px; /* Ширина кнопки */
            position: relative; /* Позиционирование для ввода количества */
        }

        .option-button i {
            font-size: 24px; /* Размер иконки */
            display: block; /* Иконка на отдельной строке */
            margin-bottom: 5px; /* Отступ под иконкой */
        }

        /* Стили для поля ввода количества */
        .quantity-input {
            width: 50px;
            margin-top: 5px;
            display: none; /* Скрыто по умолчанию */
        }

        /* Стили для списка объектов */
        .object-list {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            max-width: 300px;
        }
    </style>
</head>
<body>

    <h2>Создание объекта</h2>
    <button class="create-button" id="createBtn">Создать объект</button>

    <div class="object-list" id="object-list">
        <h3>Созданные объекты:</h3>
        <ul id="object-container"></ul>
    </div>

    <!-- Модальное окно -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" style="cursor: pointer; float: right;">&times;</span>
            <h3>Выберите вариант:</h3>
            <div id="options-container">
                <button class="option-button" data-value="Объект 1">
                    <i class="fas fa-cogs"></i>Объект 1
                    <input type="number" class="quantity-input" min="1" placeholder="Кол-во">
                    <button class="submit-button">Отправить</button>
                </button>
                <button class="option-button" data-value="Объект 2">
                    <i class="fas fa-shield-alt"></i>Объект 2
                    <input type="number" class="quantity-input" min="1" placeholder="Кол-во">
                    <button class="submit-button">Отправить</button>
                </button>
                <button class="option-button" data-value="Объект 3">
                    <i class="fas fa-user"></i>Объект 3
                    <input type="number" class="quantity-input" min="1" placeholder="Кол-во">
                    <button class="submit-button">Отправить</button>
                </button>
                <button class="option-button" data-value="Объект 4">
                    <i class="fas fa-car"></i>Объект 4
                    <input type="number" class="quantity-input" min="1" placeholder="Кол-во">
                    <button class="submit-button">Отправить</button>
                </button>
</button>
                
            </div>
        </div>
    </div>

    <script>
        // Получаем элементы
        const btn = document.getElementById("createBtn");
        const objectContainer = document.getElementById("object-container");
        const modal = document.getElementById("myModal");
        const closeModal = document.getElementById("closeModal");

        // Открываем модальное окно
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Закрываем модальное окно
        closeModal.onclick = function() {
            modal.style.display = "none";
        }

        // Закрываем модальное окно при клике вне его
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        // Обработчик события для кнопок с вариантами
        document.querySelectorAll('.option-button').forEach(button => {
            button.onclick = function() {
                const quantityInput = this.querySelector('.quantity-input');
                quantityInput.style.display = 'block'; // Показываем поле ввода количества
                quantityInput.focus(); // Устанавливаем фокус на поле ввода
                const value = this.getAttribute('data-value');

                // Добавление объекта в список
                quantityInput.onkeypress = function(event) {
                    if (event.key === 'Enter') {
                        const quantity = this.value;
                        if (quantity && quantity > 0) {
                            const newObject = document.createElement("li");
                            newObject.textContent = `${value} (количество: ${quantity})`;
                            objectContainer.appendChild(newObject);
                            this.value = ''; // Сбрасываем поле ввода
                            this.style.display = 'none'; // Скрываем поле ввода
                        } else {
                            alert('Пожалуйста, введите корректное количество.');
                        }
                    }
                };
            };
        });
    </script>

</body>
</html>
