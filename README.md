# Attendance login system
Simple application for employee attendance with QR. <b>(You need install Employee Attendance with QR on Google Play for attendance) https://play.google.com/store/apps/details?id=com.aandt.employeeattendancewithqr </b>

Created By me [Abed Putra](http://abedputra.com)

# Feature
- Add user
- Delete user
- Ban, Unban user
- Register new user sent to email token
- Forget password
- Role user level
- Edit user profile
- Gravatar user profile
- Recaptcha by Google
- List employees attendance
- Export employee attendance to CSV or XLS
- Check your employee attendance late or ontime every day
- Review your employee attendance for 1 week, 1 month, 1 Years etc
- Generate QR for your employee name

# Settings
- database.php
```
'hostname' => 'localhost', 'username' => '', 'password' => '', 'database' => '',
```

- config.php
```
//Link URL
$config['base_url'] = 'http://adminweb.com/admin/';
// Sent email from:
$config['register'] = 'admin@gmail.com';
$config['forgot'] = 'admin@gmail.com';
```

- ReCAPTCHA.php (Library)<br>
```
private $dataSitekey = ""; //Your SiteKey`
private $lang = "en"; //Lang ReCAPTCHA
public $secret = ''; //Secret
```

# User Level
- is_admin
- is_author
- is_editor
- is_subscriber

# Install
- Clone or download
- Import Sql file
- Do Settings
- Done

# login
- Pass : admin
- User : admin@gmail.com

# Check User Level
controller.php
```
//check user level
if(empty($data['role'])){
    redirect(site_url().'main/login/');
}
$dataLevel = $this->userlevel->checkLevel($data['role']);
//check user level

if($dataLevel == "is_admin"){
  (your code here)
}
```
# Warning
<b>(You need install Employee Attendance with QR on Google Play for attendance) https://play.google.com/store/apps/details?id=com.aandt.employeeattendancewithqr. Attendance login system can't working without Employee Attendance with QR application</b>

# About
Attendance login system is based on the [codeigniter](https://github.com/bcit-ci/CodeIgniter). Attendance login system is based frontend on the Bootstrap framework created by  [Mark Otto](https://twitter.com/mdo) and [Jacob Thorton](https://twitter.com/fat).
Password hashing with PBKDF2, Author: [havoc AT defuse.ca](https://github.com/defuse).
Ported to CodeIgniter by [Richard Thornton](http://twitter.com/RichardThornton). 
CodeIgniter Curl Libraries by [Philip Sturgeon](https://github.com/philsturgeon).

# LICENSE
The MIT License (MIT).

Copyright (c) 2017, Abed Putra. 
 
Please feel free to send me an email if you have any problems. 
Thank you so much, my email : abedputra@gmail.com.
