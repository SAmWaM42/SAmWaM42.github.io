

CREATE TABLE gender (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(6)
);
CREATE TABLE type_values (
    name VARCHAR(15) NOT NULL PRIMARY KEY,
    days INT NOT NULL
);
CREATE TABLE organization (
    ID  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL
);
CREATE TABLE employee (
    name VARCHAR(60) NOT NULL,
    gender_id INT NOT NULL DEFAULT 0,
    ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    org_ID INT NOT NULL,
    Role VARCHAR(45) DEFAULT 'secretary',
    CONSTRAINT FOREIGN KEY (org_ID)
        REFERENCES organization (ID),
    CONSTRAINT FOREIGN KEY (gender_id)
        REFERENCES gender (ID)
);

CREATE TABLE leave_requests (
    ID INT PRIMARY KEY  AUTO_INCREMENT,
    type VARCHAR(15) NOT NULL DEFAULT 'Annual',
    employee_ID INT NOT NULL,
    start_date DATE NOT NULL,
    end_date INT NOT NULL,
    status ENUM('Pending') NOT NULL DEFAULT 'Pending',
    CONSTRAINT FOREIGN KEY (employee_ID)
        REFERENCES employee (ID)
);
CREATE TABLE leave_record (
    ID INT PRIMARY KEY  AUTO_INCREMENT,
    type VARCHAR(15) NOT NULL DEFAULT 'Annual',
    employee_ID INT NOT NULL,
    start_date DATE NOT NULL,
    end_date INT NOT NULL,
	status ENUM('Accepted', 'Rejected') NOT NULL DEFAULT 'Pending',
    CONSTRAINT FOREIGN KEY (employee_ID)
        REFERENCES employee (ID)
);







 