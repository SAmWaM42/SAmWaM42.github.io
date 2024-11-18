
drop database easy_leave;
create database easy_leave;
use easy_leave;

CREATE TABLE gender (
    ID INT PRIMARY KEY,
    name VARCHAR(6)
);
CREATE TABLE type_values (
    name VARCHAR(15) NOT NULL PRIMARY KEY,
    days INT NOT NULL
);
CREATE TABLE organization (
    ID VARCHAR(60) NOT NULL PRIMARY KEY,
    name VARCHAR(60) NOT NULL
);
CREATE TABLE roles (
    name VARCHAR(45),
    ID INT PRIMARY KEY
);
CREATE TABLE employee (
    name VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL DEFAULT '@gmail.com',
    gender_id INT NOT NULL DEFAULT 0,
    ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    org_ID VARCHAR(60) NOT NULL,
    role_ID INT NOT NULL DEFAULT 2,
    password VARCHAR(100) NOT NULL,
    CONSTRAINT FOREIGN KEY (org_ID)
        REFERENCES organization (ID),
    CONSTRAINT FOREIGN KEY (gender_id)
        REFERENCES gender (ID),
    CONSTRAINT FOREIGN KEY (role_ID)
        REFERENCES roles (ID)
);

CREATE TABLE leave_requests (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(15) NOT NULL DEFAULT 'Annual',
    employee_ID INT NOT NULL,
    start_date DATE NOT NULL,
    end_date INT NOT NULL,
    status ENUM('Pending') NOT NULL DEFAULT 'Pending',
    CONSTRAINT FOREIGN KEY (employee_ID)
        REFERENCES employee (ID)
);
CREATE TABLE leave_record (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(15) NOT NULL DEFAULT 'Annual',
    employee_ID INT NOT NULL,
    start_date DATE NOT NULL,
    end_date INT NOT NULL,
    status ENUM('Accepted', 'Rejected') NOT NULL,
    CONSTRAINT FOREIGN KEY (employee_ID)
        REFERENCES employee (ID)
);
CREATE TABLE leave_balance (
    emp_id INT NOT NULL PRIMARY KEY,
    annual_leave_balance INT DEFAULT 0,
    sick_leave_balance INT DEFAULT 0,
    maternity_leave_balance INT DEFAULT 0,
    last_accrual_date DATE NOT NULL,
    CONSTRAINT FOREIGN KEY (emp_id)
        REFERENCES employee (ID)
);

insert into roles(ID,name) values (0,'Admin');
insert into roles(ID,name) values (1,'H.R.');
insert into roles(ID,name) values (2,'Employee');

insert into gender(ID,name) values (0,'Male');
insert into gender(ID,name) values (1,'Female');
insert into gender(ID,name) values (2,'Other');

insert into type_values(name,days) values ('Maternity',90);
insert into type_values(name,days) values ('Paternity',14);
insert into type_values(name,days) values ('Sick',5);
insert into type_values(name,days) values ('Compassionate',4);








 