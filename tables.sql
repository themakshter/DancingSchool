DROP TABLE IF EXISTS Student;
 
CREATE TABLE Student
  (
     name          VARCHAR(90) NOT NULL,
     dateOfBirth   DATE NOT NULL,
     gender        CHAR(1) NOT NULL,
     email         VARCHAR(45) UNIQUE NOT NULL,
     StudentStatus ENUM('current', 'previous', 'suspended') DEFAULT 'current' NOT NULL,
     PRIMARY KEY(email)
  );
 
DROP TABLE IF EXISTS Payment;
 
CREATE TABLE Payment
  (
     paymentID     INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     paymentDate   DATETIME NOT NULL,
     amount        INT NOT NULL,
     student_email VARCHAR(45) NOT NULL,
     PaymentType   ENUM('visa', 'mastercard', 'cash') NOT NULL,
     UNIQUE (paymentID),
     FOREIGN KEY (student_email) REFERENCES Student(email)
  );
 
DROP TABLE IF EXISTS Examination;
 
CREATE TABLE Examination
  (
     examinationID  INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     examDate       DATE NOT NULL,
     student1_email VARCHAR(45) NOT NULL,
     student2_email VARCHAR(45) NOT NULL,
     MedalLevel     ENUM('gold', 'silver', 'bronze') NOT NULL,
     mark           INT NOT NULL,
     DanceStyle     ENUM('ballroom', 'latin') NOT NULL,
     UNIQUE(examinationID),
     FOREIGN KEY(student1_email) REFERENCES Student(email),
     FOREIGN KEY(student2_email) REFERENCES Student(email)
  );