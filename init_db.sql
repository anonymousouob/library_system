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

CREATE TABLE IF NOT EXISTS failed_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS login_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    result ENUM('success','fail','blocked') NOT NULL,
    message VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE PROCEDURE BorrowBook(IN p_MemberID INT, IN p_BookID INT, OUT p_Message VARCHAR(255), OUT p_Success BOOLEAN)
BEGIN
    DECLARE v_CopyID INT;
    SELECT CopyID INTO v_CopyID 
    FROM copy 
    WHERE BookID = p_BookID AND Status = 'Available' 
    LIMIT 1;
    
    IF v_CopyID IS NOT NULL THEN
        UPDATE copy SET Status = 'On Loan' WHERE CopyID = v_CopyID;
        INSERT INTO loan (CopyID, MemberID, DueDate) 
        VALUES (v_CopyID, p_MemberID, DATE_ADD(CURDATE(), INTERVAL 14 DAY));
        SET p_Message = CONCAT('借書成功，請於 ', DATE_ADD(CURDATE(), INTERVAL 14 DAY), ' 前歸還');
        SET p_Success = TRUE;
    ELSE
        SET p_Message = '借書失敗：沒有可借副本';
        SET p_Success = FALSE;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER BeforeBookDelete
BEFORE DELETE ON book
FOR EACH ROW
BEGIN
    DELETE FROM copy WHERE BookID = OLD.BookID;
END //
DELIMITER ;

DELIMITER //
CREATE FUNCTION CountOverdue(p_MemberID INT) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_Count INT;
    SELECT COUNT(*) INTO v_Count 
    FROM loan 
    WHERE MemberID = p_MemberID 
      AND Status = 'On Loan' 
      AND DueDate < CURDATE();
    RETURN v_Count;
END //
DELIMITER ;

INSERT INTO book (Title, Author, Publisher, PublicationYear, Genre) VALUES ('DataBase','A','B',2025,'Comp.sci');
INSERT INTO copy (BookID, ShelfLocation, Status) VALUES (1,'A-1-01','Available');
