CREATE DATABASE school_db;



USE school_db;
CREATE TABLE students(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    course VARCHAR(50) NOT NULL
);



INSERT INTO students(name,email,course)
VALUES ("rubina","rubina@gmail.com","BSc.(HONS)CS"),
("eluja","eluja@gmail.com","BSc.(HONS)CS"),
("aanchal","aanchal@gmail.com","BSc.(HONS)CS"),
("shreyata","shreyata@gmail.com","BSc.(HONS)CS"),
("isha","isha@gmail.com","BSc.(HONS)CS");


