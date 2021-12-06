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

$sql = "CREATE TABLE user(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
unique_id text NOT NULL,
fname text NOT NULL,
lname text NOT NULL,
email text NOT NULL,
mobile text NOT NULL,
password text NOT NULL,
dob text NOT NULL,
pannum text NOT NULL, 
marital_status text NOT NULL,
gender text NOT NULL,
occuption text NOT NULL,
income text NOT NULL,
emergency_contact text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table user created successfully";
}

$sql = "CREATE TABLE contact(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name text NOT NULL,
email text NOT NULL,  
phone text NOT NULL,  
address text NOT NULL,  
message text NOT NULL,  
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {

    echo "Table  contact created successfully.";

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
$sql = "CREATE TABLE employee(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
leader text NOT NULL,
unique_id text NOT NULL,
fname text NOT NULL,
lname text NOT NULL,
email text NOT NULL,
mobile text NOT NULL,
password text NOT NULL,
bankdetails text NOT NULL,
accntnum text NOT NULL,
ifsccode text NOT NULL,
panimg text NOT NULL,
adhaarimg text NOT NULL,
dob text NOT NULL,
pannum text NOT NULL, 
marital_status text NOT NULL,
gender text NOT NULL,
occuption text NOT NULL,
income text NOT NULL,
emergency_contact text NOT NULL,
address text NOT NULL,
profilepic text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table employee_list created successfully";
}

$sql = "CREATE TABLE clients(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
employee_id text NOT NULL,
clientname text NOT NULL,
regdate text NOT NULL,
mobile text NOT NULL,
servicetype text NOT NULL, 
bankname text NOT NULL, 
loanamount text NOT NULL,
companyname text NOT NULL,
pointstype text NOT NULL,
location text NOT NULL,
status text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table clients created successfully";
}
  
  
 $sql = "CREATE TABLE track_work(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
campaignuser_id INT(11) NOT NULL,
executive_id INT(11) NOT NULL,
employee_id INT(11) NOT NULL,
clientname text NOT NULL,
selecttype text NOT NULL,
mobile text NOT NULL,
email text NOT NULL, 
amount text NOT NULL,
address text NOT NULL,
remark text NOT NULL,
assingedto text NOT NULL,
source text NOT NULL,
status text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table track_work created successfully";
}
 $sql = "CREATE TABLE support(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
clientname text NOT NULL,
query text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table support created successfully";
}
 $sql = "CREATE TABLE user_investment(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
applieddate text NOT NULL,
investedtype text NOT NULL,
loanamount text NOT NULL,
payin text NOT NULL,
growth text NOT NULL,
status text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table user_investment created successfully";
}
 $sql = "CREATE TABLE user_loans(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
applieddate text NOT NULL,
investedtype text NOT NULL,
requiredamount text NOT NULL,
status text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table user_loans created successfully";
}
 $sql = "CREATE TABLE pointset(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL,
amount text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table pointset created successfully";
}
 $sql = "CREATE TABLE worksource(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL, 
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table worksource created successfully";
}
$sql = "CREATE TABLE manager(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
unique_id text NOT NULL,
name text NOT NULL,
email text NOT NULL,
mobile text NOT NULL,
password text NOT NULL,
bankdetails text NOT NULL,
accntnum text NOT NULL,
ifsccode text NOT NULL,
panimg text NOT NULL,
adhaarimg text NOT NULL,
location text NOT NULL,
branchname text NOT NULL,
status text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table manager created successfully";
}

$sql = "CREATE TABLE executive(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT(11) NOT NULL,
user_role text NOT NULL,
employee_id INT(11) NOT NULL,
unique_id text NOT NULL,
name text NOT NULL,
email text NOT NULL,
mobile text NOT NULL,
password text NOT NULL,
panimg text NOT NULL,
adhaarimg text NOT NULL,
location text NOT NULL, 
status text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table manager created successfully";
}
$sql = "CREATE TABLE campaigns(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
user_role VARCHAR(30) NOT NULL,
unique_id text NOT NULL,
title text NOT NULL,
manager_id text NOT NULL,
service text NOT NULL,
executive text NOT NULL, 
status text NOT NULL,
timeframe INT(11) NOT NULL,
feedbackoptions text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table campaigns created successfully";
}
$sql = "CREATE TABLE campaigns_excell(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
user_role text NOT NULL,
campaign_id text NOT NULL, 
excellname text NOT NULL, 
excellfile text NOT NULL, 
uploaddate text NOT NULL, 
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table campaigns_users created successfully";
}
$sql = "CREATE TABLE campaigns_users(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id text NOT NULL,
user_role text NOT NULL,
campaign_id INT(11) NOT NULL,
campaignexcell_id INT(11) NOT NULL,
executive_id text NOT NULL,
name text NOT NULL, 
mobile text NOT NULL, 
callstart INT(11) NOT NULL,
callstatus INT(11) NOT NULL,
callmessage text NOT NULL,
callduration text NOT NULL,
duration text NOT NULL,
reg_date  VARCHAR(20) NOT NULL,
mod_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "Table campaigns_users created successfully";
}
/* Dailer */
 $sql = "CREATE TABLE campaign_options(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
user_id text NOT NULL, 
name text NOT NULL, 
value text NOT NULL, 
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table campaign_options created successfully";
}
 $sql = "CREATE TABLE feedbackoptions(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
title text NOT NULL, 
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table feedbackoptions created successfully";
}
 $sql = "CREATE TABLE campaign_logs(
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
campaign_id INT(11) NOT NULL,
executive_id text NOT NULL,
logstatus text NOT NULL,
logtime text NOT NULL,
action text NOT NULL,
reg_date TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table campaign_logs created successfully";
}
?>