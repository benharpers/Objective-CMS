OBJECTIVE CMS - CONTENT MANAGEMENT SYSTEM
=========================================

Objective CMS is a content management system that uses a database in a
very flexible way, similar to the "model-view-controller" or MVC
architecture.

Rather than the usual fixed fields provided by other CMS systems, the
database structure is an editable "model" which defines what type of
data each record is. Some types of records are containers - for example,
to store a series of entries, the model might be: a folder, containing a
page, containing a title and a body. I am planning on releasing this as
open source.

This is a partial implementation, many essential features have not been
implemented yet. These include:

* Admin Security - Users & Groups, Permissions
* Logging & Statistics - Graphs (jpGraph?) and log data
* Import & Export - Exchange data with various apps (FileMaker, Excel)
* Code Comments - Add comments to the code

There are several issues with the implementation that also need to be
addressed:

* MySQL code is very simple and does not take advantage of subqueries or multiple selects, will cause performance issues with high traffic sites.
* The data from MySQL is stored in arrays in an inefficient manner, large data sets will be slow and the server may even run out of memory.
* Smarty template compiling and caching do not function correctly.
* Currently only functions in the root directory of a domain.


INSTALLATION
------------

There is no easy to use wizard as of yet but the installation is fairly
simple and standard for almost any PHP/MySQL.

1. Create a MySQL database.
2. Create a user and grant access to the database.
3. Import the database.sql into the database.
4. Edit the ini/mysql.ini to set the database/user/password.
5. Be sure that Objective CMS is stored at the root of the domain.

Make sure there are .htaccess files in both the root directory and the admin.
Some FTP clients and OS's do not show these files but they are part of the 
distribution.

COPYRIGHT
---------

Objective CMS is released under the GNU Public License (see LICENSE.txt).
