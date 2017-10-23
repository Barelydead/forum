DROP TABLE IF EXISTS f_post;
DROP TABLE IF EXISTS f_user;
DROP TABLE IF EXISTS f_tag;
DROP TABLE IF EXISTS f_post2tag;
DROP TABLE IF EXISTS f_like2user;


CREATE TABLE `f_post`(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user INTEGER,

    content TEXT,
    title TEXT,
    likes INTEGER DEFAULT 0,

    question BOOLEAN,
    reply BOOLEAN,
    comment BOOLEAN,
    questionId INTEGER,
    replyId INTEGER,
    accepted BOOLEAN DEFAULT 0,

    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated NUMERIC,
    deleted NUMERIC,
    FOREIGN KEY(user) REFERENCES f_user(id)
);

INSERT INTO `f_post`(title,content,user,question) VALUES
('Första kommentaren', 'En kommentar som fyller ut min hemsida på ett fint sätt.', 1, 1),
('Programmering', 'varför fungerar min kod?', 2, 1)
;

CREATE TABLE `f_user`(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    password TEXT,
    username TEXT UNIQUE,
    name TEXT,
    mail TEXT UNIQUE,
    age INTEGER,
    karma INTEGER,
    description TEXT,

    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated NUMERIC,
    deleted NUMERIC,
    admin BOOLEAN DEFAULT 0
);

INSERT INTO `f_user`(username,password,mail,age,karma,name) VALUES
('barelydead', '$2y$10$JnPkcufGXRHVP24rPfDyse5ngaI4oJIODP7kpYX4jXUwQ9v3cZAoq', 'christofer.jungberg@gmail.com', 27, 15, "Christofer Jungberg"),
('doe', '$2y$10$H9GepHh7EdNG3qd2m1iAnOHFZz7Fcto9qokzODDBnl8edjdn5QH/a', 'doe@doe.se', 27, 15, "Doe"),
('admin', '$2y$10$ImZ8SYa9uAWu45ElvFbbhuu/Nqef1E/uXpHZewmfUUcG3x3taqDI6', 'admin@admin.se', 27, 15, "Admin")
;


CREATE TABLE f_tag(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tag TEXT UNIQUE,
    description TEXT
);

INSERT INTO `f_tag`(tag,description) VALUES
('javascript', 'A programming language'),
('cycle', 'A human powered vehicle')
;


CREATE TABLE f_post2tag(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post INTEGER,
    tag INTEGER,

    foreign key(post) REFERENCES f_post(id),
    FOREIGN KEY(tag) REFERENCES f_tag(id)
);

INSERT INTO `f_post2tag`(post,tag) VALUES
    (1, 1),
    (1, 2),
    (2, 2)
;


CREATE TABLE f_like2user(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post INTEGER,
    user INTEGER,
    type TEXT,

    foreign key(post) REFERENCES f_post(id),
    FOREIGN KEY(user) REFERENCES f_user(id)
);


DROP VIEW IF EXISTS `VPostOverview`;

CREATE VIEW `VPostOverview` AS
SELECT p.*, t.*,
u.username AS username,
u.mail as mail,
p.created as postDate,
p.id as id
FROM f_post2tag as p2t
INNER JOIN f_post AS p ON
p.id = p2t.post
INNER JOIN f_tag AS t ON
t.id = p2t.tag
INNER JOIN f_user AS u ON
u.id = p.user;


DROP TRIGGER IF EXISTS addKarmaWhenUpvote;
CREATE TRIGGER addKarmaWhenUpvote
AFTER INSERT ON f_like2user
FOR EACH ROW
WHEN new.type = "upvote"
BEGIN
    UPDATE f_user SET karma = karma + 1
    WHERE id = (select user from f_post WHERE id = new.post);
END;

DROP TRIGGER IF EXISTS removeKarmaWhenDownvote;
CREATE TRIGGER removeKarmaWhenDownvote
AFTER INSERT ON f_like2user
FOR EACH ROW
WHEN new.type = "downvote"
BEGIN
    UPDATE f_user SET karma = karma - 1
    WHERE id = (select user from f_post WHERE id = new.post);
END;


INSERT INTO `f_like2user`(post,user,type) VALUES
    (1, 1, 'upvote');
