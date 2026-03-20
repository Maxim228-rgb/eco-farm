CREATE DATABASE IF NOT EXISTS eco_farm;
USE eco_farm;

-- Таблица пользователей
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица продуктов
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    stock INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица бронирований домов
CREATE TABLE house_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    house_type ENUM('Деревянный домик', 'Эко-шале', 'Глэмпинг') NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица экскурсий
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    date DATE NOT NULL,
    max_people INT DEFAULT 10,
    booked INT DEFAULT 0,
    image_url VARCHAR(500)
);

-- Таблица бронирований экскурсий
CREATE TABLE tour_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tour_id INT NOT NULL,
    participants INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
);

-- Заполнение таблиц тестовыми данными
INSERT INTO products (name, description, price, image_url, stock) VALUES
('Органические яблоки', 'Свежие яблоки с нашего сада, без химикатов.', 2.50, 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?w=500', 50),
('Домашние яйца', 'Яйца от кур свободного выгула.', 3.20, 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=500', 30),
('Козье молоко', 'Натуральное козье молоко, богатое витаминами.', 1.80, 'https://images.unsplash.com/photo-1559598467-f8b76c8155d0?w=500', 20),
('Мед разнотравье', 'Мед с наших лугов, 500г.', 5.50, 'https://images.unsplash.com/photo-1587049352847-81a56d73c9d9?w=500', 15),
('Свежие овощи', 'Набор сезонных овощей (помидоры, огурцы, перец).', 4.00, 'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?w=500', 25);

INSERT INTO tours (name, description, price, date, max_people, booked, image_url) VALUES
('Обзорная экскурсия', 'Знакомство с фермой, животными и органическим производством.', 10.00, '2026-04-15', 15, 0, 'https://images.unsplash.com/photo-1500595046743-fd2a9f19b83c?w=500'),
('Мастер-класс по сыроварению', 'Научитесь делать сыр из нашего молока.', 25.00, '2026-04-20', 8, 0, 'https://images.unsplash.com/photo-1626804475294-324a0e19d429?w=500'),
('Верховая прогулка', 'Прогулка на лошадях по живописным окрестностям.', 15.00, '2026-04-18', 10, 0, 'https://images.unsplash.com/photo-1599059813005-1122176229c6?w=500');