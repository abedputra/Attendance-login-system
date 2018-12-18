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

# User Level
- is_admin
- is_author
- is_employee
- is_subscriber

# Wiki
https://github.com/abedputra/Attendance-login-system/wiki

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
<br><br>
***This application can't working without Employee Attendance with QR, so please download first on Google Play.***
<br><br>

# New Version
Pro version for app, please download from here (https://play.google.com/store/apps/details?id=com.aandt.employeeattendancewithqrpro

# Migration from v1.0 to v2.0a
Please update the SQL file. Please always backup your data first.
<br>
From v2.0a we add new table, for save history QR.


<br>
<br>
<br>

----------------------------------------------------------------------------------------------------------------------------------------

# -----How to use this application ?
Please follow 2 steps:<br>
1. Settings System Management
Please check this<br>
https://github.com/abedputra/Attendance-login-system/wiki/Settings-Management-System-%3F<br>
Please check this video how to istall the system
https://www.youtube.com/watch?v=s8pZl5UoT40

2. Settings Android Application<br>
Please check this<br>
https://github.com/abedputra/Attendance-login-system/wiki/Settings-on-Employee-Attendance-with-QR-Application-%3F

# -----How to get KEY?
-Go to Attendance login system link<br>
-Login<br>
-Go to settings<br>
-Click get Key<br>
-Save<br>
-Dont forget to add KEY to your application<br>

# -----How to get my employees data?
-Go to Attendance login system link<br>
-Login<br>
-Go to employee menu<br>

----------------------------------------------------------------------------------------------------------------------------------------

<br>
<br>
<br>

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
