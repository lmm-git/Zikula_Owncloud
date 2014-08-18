Zikula_Owncloud
===============

A connector to use Zikula users in your ownCloud instance


Installing
----------

_I have tested both modules with Zikula 1.3.5 and OwnCloud 7.0.1. It will not work with OwnCloud version lower than 7.0.0. Please use Tag 0.8.0 instead._

To install this connector you have to install the module from the directory ```Zikula_Module``` ```Owncloud``` into your Zikula instance and the app ```zikula_auth``` from the directory ```Owncloud_Module```.
After that you have to configure both modules by accessing the admin-panel in your Zikula instance and Owncloud instance. There you have to input a auth code wich must be equal at both modules and the server address and/or ip from the other instance.

Using
-----

After installing the modules correctly you should be able to login with your Zikula-credentials if you have the necessary rights (```Owncloud::Use``` at ```ACCESS_EDIT``` at least). If you want a direct redirection without typing in your credentials again from your Zikula instance you can add a link calling the ```redirect``` function of the user type of the module. For example the following link in a template:
```
{modurl modname='Owncloud' type='user' func='redirect'}
```


Contribute
----------

Pull request are welcome! If you find any issue please add it to the issues section.
