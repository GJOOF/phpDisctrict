<nav
    class="navbar navbar-expand-sm navbar-dark bg-primary"
>
    <a class="navbar-brand" href="index.php" style="vpadding-left:20px"> 
    <img src="img/Emblem.svg" class="card-img-top" alt="Russian Ground Forces" width="32px" height="32px">
    </a>
    <button
        class="navbar-toggler d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapsibleNavId"
        aria-controls="collapsibleNavId"
        aria-expanded="false"
        aria-label="Toggle navigation"
    ></button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="armies.php">Армии</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="units.php">Военные части</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="personnel.php">Личный состав</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vehicle.php">Техника</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="weapon.php">Вооружение</a>
            </li>
        </ul>
    </div>
</nav>
<style>
   .army-card{
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.army-card:hover{
    transform: translateY(-5px);
}
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
h2 {
    text-align: center;
    color: #333;
    font-size: 28px;
    margin-bottom: 20px;
}

.table-container {
    width: 90%;
    margin: 0 auto;
    overflow-x: auto;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

th {
    background-color: #3498db;
    color: #ffffff;
    font-size: 18px;
}

td {
    font-size: 16px;
    color: #555;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}
</style>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>