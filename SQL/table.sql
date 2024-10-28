drop easy_leave;
create database easy_leave;
use easy_leave;
create table leave_requests
(
ID int primary key not null AUTO_INCREMENT,
Worker_ID  int not null  ,
start_date  date not null,
end_date date not null
);
create table special_requests
(
ID int primary key not null AUTO_INCREMENT,
Worker_ID  int not null,
Type varchar(45) not null,
start_date  date not null,
end_date date not null
);
