# Welcome to Magica

This is a MVC framework for building web applications in PHP. It's free and [open-source](LICENSE).

It was created from the foundations of "Just a demo application related to one of my blog posts: A Most Simple PHP MVC Beginners Tutorial." written by Neil Rosenstech. This framework is intended to demonstrate how a logging system can be maintained with PHP, and to show how sessions, administrations can be managed.

Original framework website: http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/

## Starting an application using this framework

1. First, download the framework, either directly or by cloning the repo.
1. Open [Models/Game_config.php](Models/Game_config.php) and enter your database configuration data.
1. Create routes, add controllers, views and models.

## Routing

Referring to the original framework, [Routes.php](Routes.php) contains function "call" that invokes controller and action via GET parameters. In Magica there are 3 controller classes: Portal, Game menu and Admin. The actions are defined in $controllers outside the function.

Auth_test and Auth_debug actions can be commented, they are for debug purpose.

## Controllers

The controller can include the authenication function, but the Auth class in [Models/Auth.php](Models/Auth.php) has dependency of other 3 classes: Session, User, User_log. The Auth class is initiated in the constructor of Controller class and stored as local variable of the class. It can then be invoked to query the auth level of user and provide further processing of user management.

## Views

The header part of each view is tailored to suit individual pages with the use of controller local variable "$current_page". This variable alters the layout of navigation panel. The Admin function only appears when local variable "$auth_level" equals to "admin".

Magica is incorporated with preloading and afterloading scripts, along with AJAX capability. The preloading and afterloading scripts are defined within [Views/Preload.php](Views/Preload.php) and [Views/Afterload.php](Views/Afterload.php). Magica uses Bootstrap datepicker provided by UX Solutions.

The AJAX function is managed in [Views/Ajax_post.php]Views/Ajax_post.php) although it is a Javascript file.

To use the AJAX, you should add a corresponding switch case in the section that defines query_php, the php file that access backend and post_parameter, the concatenated string that passes POST parameters to the backend php file.

After that, the new php backend should output a JSON that is encoded PHP array (look at php files inside folders in Models directory, they are examples) that must include value with key errorType (Outputs type of error), success (boolean that indicates successful operation), and 'redirect' (Indicates where the page will be redirected. However setting it to 'no' will not cause page to redirect).

Finally, include 'Views/Ajax_post.php' in needed action and use 'someAction="ajaxPost()"' to invoke the function. You may additionally input a 'request' variable and 'count variable in function call to ajaxPost, by adding a switch case of 'request variable' you can even manage multiple AJAX calls within the same page, while count is useful for select out of multiple records as indicated in using the Pagination class.

Magica is installed with a simple Pagination. It requires the current record number (offset), the limit of record and maximum number of records to be inserted as its construction parameter. Fortunately, you can already get all the data inside controller class before Pagination is even constructed. To use it simply require_once the class php, obtain the GET parameters of limit and offset, query the database for records and number of records and finally insert the limit, offset and record count into constructor of Pagination class. Afterwards, simple call getPaginationHtml() in view for a sample of Pagination navigation display.

## Models

Game_Config contains most of customizable information including:
1. Rapid access protection
1. A steadily increasing 'stamina' number management that someone making game may make use of it to create stamina system
1. Accout expiration time
1. Number of failed login attempts to block an IP from accessing the site
1. The time for automated IP block
1. Hours of inactivity for a session to get expired

The functionality of Magica is not completely finished, but it is expected the inactivity and account expiration will be implemented in future.

Database information is defined in [Connection.php](Connection.php) as per standard of its original framework.

Pagination does not access database, I expect it to be put in View folder later.

User, User_log, Session and Ip_block classes are used to construct a complete logging system with only encrypted password as its login method.

Auth class has dependency on User, User_log and Session classes, and is the primary class for managing most of authenication processes including session management and unauthorized accesses.

## Login system

Login system of Magica uses password with SHA-256 encryption. Accounts of Magica can change their own email and nickname, signin and out for theirselves and destroy sessions on other computers.

Admins has much later priviledge including changing username for every user, its role, reset ticks (it may be useful for game management in case someone's account is glitched), setting and lifting a ban, manage login sessions and IP blocks.

Sessions in Magica is protected by double confirmation between PHP sessions and data objects managed in MySQL database. The sessions are also protected against fixation attacks by using stationary session_id in tandem with fluid series_id that changes whenever the authenication is reconfirmed.

## Credits and reference

Magica uses the Bootstrap datepicker by UX Solutions

Website: https://uxsolutions.github.io/bootstrap-datepicker/

Github source: https://github.com/uxsolutions/bootstrap-datepicker

---

Magica uses the MVC framework framework by Neil Rosenstech.

Website: http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/

Github source: https://github.com/Raindal/php_mvc

A Most Simple PHP MVC Beginners Tutorial

Just a demo application related to one of my blog posts: A Most Simple PHP MVC Beginners Tutorial.

This shows how one can build an MVC app with PHP.
License

MIT License (MIT)

Copyright (c) <2013>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


Background image is a modified version of white tile from https://background-tiles.com
Website: https://background-tiles.com/overview/white/1009.php
