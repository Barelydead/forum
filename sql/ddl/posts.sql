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

    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated NUMERIC,
    deleted NUMERIC,
    FOREIGN KEY(user) REFERENCES f_user(id)
);

INSERT INTO `f_post`(title,content,tags,user,question) VALUES
('Första kommentaren', 'En kommentar som fyller ut min hemsida på ett fint sätt.', 'filler tag', 1, 1),
('Programmering', 'varför fungerar min kod?', 'existensielt', 2, 1)
;


CREATE TABLE f_tag(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tag TEXT,
    description TEXT,
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


DROP VIEW IF EXISTS `VPostOverview`;

CREATE VIEW `VPostOverview` AS
SELECT p.*, t.*, u.*,
p.created as postDate
FROM f_post2tag as p2t
INNER JOIN f_post AS p ON
p.id = p2t.post
INNER JOIN f_tag AS t ON
t.id = p2t.tag
INNER JOIN f_user AS u ON
u.id = p.user;
