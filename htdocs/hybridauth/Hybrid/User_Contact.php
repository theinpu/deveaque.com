<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

/**
 * Hybrid_User_Contact
 *
 * used to provider the connected user contacts list on a standardized structure across supported social apis.
 *
 * http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Contacts.html
 */
class Hybrid_User_Contact {

    /* The Unique contact user ID */
    public $identifier = null;

    /* User website, blog, web page */
    public $webSiteURL = null;

    /* URL link to profile page on the IDp web site */
    public $profileURL = null;

    /* URL link to user photo or avatar */
    public $photoURL = null;

    /* User dispalyName provided by the IDp or a concatenation of first and last name */
    public $displayName = null;

    /* A short about_me */
    public $description = null;

    /* User email. Not all of IDp garant access to the user email */
    public $email = null;
}
