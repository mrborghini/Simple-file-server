CREATE DATABASE simple_file_server;

USE simple_file_server;

CREATE TABLE users (
    userid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email varchar(50) NOT NULL,
    password varchar(255) NOT NULL
);

CREATE TABLE uploads (
    fileid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    filelocation varchar(255) NOT NULL,
    userid int NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(userid)
);