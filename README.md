<img src="https://user-images.githubusercontent.com/11581453/33417023-8a7c889a-d5da-11e7-9fba-4ec8a925e7e0.png" width="150">

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

![Attendance-login-system](https://user-images.githubusercontent.com/11581453/41508123-e4e1579e-7269-11e8-9493-3e6c3a7e9b9c.png)

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



***This application can't working without Employee Attendance with QR, so please download first on Google Play.***

# -----How to install Attendance login system?
Download from https://github.com/abedputra/Attendance-login-system.

# -----How to use this application ?
Please follow 2 steps:
1. Settings Admin
-First download Attendance login system
-How to Install ?? follow Github desc.
-After install go to Attendance login system link
-Login into Attendance login system
-Do settings menu such as how many employee do you have
-Make sure you get new KEY
-Go to generate QR, and generate your employee name
-Give the QR to your employees so they can scan for attendance.

2. Settings Application
-Install this application.
-Go to settings.
-Fill the data such as the link where Attendance login system is located, and the KEY.
-Scan your barcode.
-Done

# -----How to get KEY?
-Go to Attendance login system link
-Login
-Go to settings
-Click get Key
-Save
-Dont forget to add KEY to your application

# -----How to get my employees data?
-Go to Attendance login system link
-Login
-Go to employee menu

# Support me
Support me at <a href="https://www.patreon.com/abedputra">Patron</a>

# About
Attendance login system is based on the [codeigniter](https://github.com/bcit-ci/CodeIgniter). Attendance login system is based frontend on the Bootstrap framework created by  [Mark Otto](https://twitter.com/mdo) and [Jacob Thorton](https://twitter.com/fat).
Password hashing with PBKDF2, Author: [havoc AT defuse.ca](https://github.com/defuse).
Ported to CodeIgniter by [Richard Thornton](http://twitter.com/RichardThornton). 
CodeIgniter Curl Libraries by [Philip Sturgeon](https://github.com/philsturgeon).

If you have question, please email me : abedputra@gmail.com
Visit: http://abedputra.com

# LICENSE
The MIT License (MIT).

Copyright (c) 2017, Abed Putra. 
 
Please feel free to send me an email if you have any problems. 
Thank you so much, my email : abedputra@gmail.com.
