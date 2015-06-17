Zikula_Owncloud
===============

A connector to use Zikula users in your ownCloud instance


Installing
----------

_I have tested both modules with Zikula 1.3.9 and OwnCloud 8.0.4._
Please note the issue described [here](https://github.com/owncloud/calendar/pull/814). Because of this issue the module won't delete users calendars (rest of the modules work fine).

This package needs php-curl!

To install this connector you have to install the module from the directory ```Zikula_Module``` ```Owncloud``` into your Zikula instance and the app ```zikula_auth``` from the directory ```Owncloud_Module```.
After that you have to configure both modules by accessing the admin-panel in your Zikula instance and Owncloud instance. There you have to input a auth code wich must be equal at both modules and the server address and/or ip from the other instance.

Usage
-----

After installing the modules correctly you should be able to login with your Zikula-credentials if you have the necessary rights (```Owncloud::Use``` at ```ACCESS_EDIT``` at least). If you want a direct redirection without typing in your credentials again from your Zikula instance you can add a link calling the ```redirect``` function of the user type of the module. For example the following link in a template:
```smarty
{modurl modname='Owncloud' type='user' func='redirect'}
```


Contribute
----------

Pull request are welcome! If you find any issue please add it to the issues section.
