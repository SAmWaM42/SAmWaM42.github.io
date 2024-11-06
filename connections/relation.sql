
CREATE TABLE leave_record (
    ID INT PRIMARY KEY  AUTO_INCREMENT,
    type VARCHAR(15) NOT NULL DEFAULT 'Annual',
    employee_ID INT NOT NULL,
    start_date DATE NOT NULL,
    end_date INT NOT NULL,
	status ENUM('Accepted', 'Rejected') NOT NULL ,
    CONSTRAINT FOREIGN KEY (employee_ID)
        REFERENCES employee (ID)
);







 