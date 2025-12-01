CREATE DATABASE IF NOT EXISTS library_system;
USE library_system;

CREATE TABLE IF NOT EXISTS book (
    BookID INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(255) NOT NULL,
    Author VARCHAR(255) NOT NULL,
    Publisher VARCHAR(255) NOT NULL,
    PublicationYear INT,
    Genre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS copy (
    CopyID INT PRIMARY KEY AUTO_INCREMENT,
    BookID INT NOT NULL,
    ShelfLocation VARCHAR(50) NOT NULL,
    Status ENUM('Available','On Loan','Lost','Damaged') DEFAULT 'Available',
    FOREIGN KEY (BookID) REFERENCES book(BookID) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS member (
    MemberID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Account VARCHAR(255) UNIQUE,
    MemberType ENUM('Reader','Master') DEFAULT 'Reader',
    RegistrationDate DATE DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS loan (
    LoanID INT PRIMARY KEY AUTO_INCREMENT,
    CopyID INT NOT NULL,
    MemberID INT NOT NULL,
    LoanDate DATE NOT NULL DEFAULT (CURRENT_DATE),
    DueDate DATE NOT NULL,
    ReturnDate DATE DEFAULT NULL,
    Status ENUM('On Loan','Returned','Overdue') DEFAULT 'On Loan',
    FOREIGN KEY (CopyID) REFERENCES copy(CopyID) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (MemberID) REFERENCES member(MemberID) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO book (Title, Author, Publisher, PublicationYear, Genre) VALUES ('DataBase','A','B',2025,'Comp.sci');
INSERT INTO copy (BookID, ShelfLocation, Status) VALUES (1,'A-1-01','Available');
INSERT INTO member (Username, Password, Account, MemberType) VALUES ('admin','Qwer1234','Admin1234','Master');
