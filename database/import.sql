CREATE DATABASE simple_file_server; -- Create the database named simple_file_server --

USE simple_file_server; -- Making sure it's running the rest of the script in the database --

CREATE TABLE users ( -- Create table named users --
    userid int NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Making user id's automatically -- 
    email varchar(50) NOT NULL, -- email with max characters of 50 --
    password varchar(255) NOT NULL, -- Password with max characters of 50 --
    theme int NULL -- Themes with number of style --
);

CREATE TABLE uploads ( -- Create table named uploads --
    fileid int NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Making file id's automatically --
    filelocation varchar(255) NOT NULL, -- filelocation with max characters of 255, because in most operating systems max length of a file is 255 characters --
    filename varchar(255) NOT NULL, -- filename with max characters of 255, because in most operating systems max length of a file is 255 characters --
    userid int NOT NULL, -- Make another userid --
    trash boolean NOT NULL, -- Make trash which only can be only be true(1) or false(0) -- 
    FOREIGN KEY (userid) REFERENCES users(userid) -- Reference the userid from table users --
);