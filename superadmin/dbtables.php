<?php
include("dbconfig.php");

 $sql = "CREATE TABLE superadmin(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username text NOT NULL,
email text NOT NULL,
password text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table superadmin created successfully";
}

 $sql = "CREATE TABLE loans(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name text NOT NULL,
dob text NOT NULL,
phone text NOT NULL,
email text NOT NULL,
address text NOT NULL,
district text NOT NULL,
state text NOT NULL,
yearlyincome text NOT NULL,
sourceincome text NOT NULL,
loantype text NOT NULL,
existingloan text NOT NULL,
bankdetails text NOT NULL,
branch text NOT NULL,
ifsccode text NOT NULL,
customerofbranch text NOT NULL,
investment text NOT NULL,
interestedin text NOT NULL,
comments text NOT NULL,

reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table loans created successfully";
}

$sql = "CREATE TABLE investment(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name text NOT NULL,
phone text NOT NULL,
email text NOT NULL,
location text NOT NULL,
investment text NOT NULL,
interestedin text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table investment created successfully";
}

$sql = "CREATE TABLE register(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
fname text NOT NULL,
lname text NOT NULL,
email text NOT NULL,
mobile text NOT NULL,
password text NOT NULL,

reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table register created successfully";
}

$sql = "CREATE TABLE track_team(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
employee_id text NOT NULL,
clientname text NOT NULL,
mobile text NOT NULL,
clients text NOT NULL,
loanamount text NOT NULL,
investedamount text NOT NULL,
location text NOT NULL,
teamsize text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table track_team created successfully";
}
$sql = "CREATE TABLE track_loans(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
application_date text NOT NULL,
required text NOT NULL,
loantype text NOT NULL,
application_status text NOT NULL,
emi_chart text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table track_loans created successfully";
}
$sql = "CREATE TABLE employee_income(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
emp_name text NOT NULL,
emp_id text NOT NULL,
emp_income text NOT NULL,
month text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table employee_income created successfully";
}
$sql = "CREATE TABLE freelancer_income(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name text NOT NULL,
freelancer_id text NOT NULL,
income text NOT NULL,
month text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table freelancer_income created successfully";
}


?>