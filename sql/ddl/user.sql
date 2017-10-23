CREATE TABLE `f_user`(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    password TEXT,
    username TEXT UNIQUE,
    name TEXT,
    mail TEXT UNIQUE,
    age INTEGER,
    karma INTEGER,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated NUMERIC,
    deleted NUMERIC,
    admin BOOLEAN DEFAULT 0
);

INSERT INTO `f_user`(username,password,mail) VALUES
('barelydead', '$2y$10$JnPkcufGXRHVP24rPfDyse5ngaI4oJIODP7kpYX4jXUwQ9v3cZAoq', 'christofer.jungberg@gmail.com'),
('doe', '$2y$10$H9GepHh7EdNG3qd2m1iAnOHFZz7Fcto9qokzODDBnl8edjdn5QH/a', 'doe@doe.se'),
('admin', '$2y$10$ImZ8SYa9uAWu45ElvFbbhuu/Nqef1E/uXpHZewmfUUcG3x3taqDI6', 'admin@admin.se')
;
