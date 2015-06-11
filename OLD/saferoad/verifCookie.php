<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 07/06/2015
 * Time: 19:09
 */

/* mysql_connect() */
/* mysql_select_db() */
function verifCookie()
{
    if (!isset($_COOKIE['auth']))
    {
        return 0;
    }
    $clean = array();
    $mysql = array();

    $now = time();
    $salt = 'SHIFLETT';

    list($identifier, $token) = explode(':', $_COOKIE['auth']);

    if (ctype_alnum($identifier) && ctype_alnum($token))
    {
        $clean['identifier'] = $identifier;
        $clean['token'] = $token;
    }
    else
    {
        /* ... */
    }

    $mysql['identifier'] = mysql_real_escape_string($clean['identifier']);


    $idUser = getUserToken(identifier) ;
    if ($idUser >= 1) {
        $record = mysql_fetch_assoc($idUser);

        if ($clean['token'] != $record['token']) {
            /* Failed Login (wrong token) */
        } elseif ($now > $record['timeout']) {
            /* Failed Login (timeout) */
        } elseif ($clean['identifier'] !=
            md5($salt . md5($record['username'] . $salt))
        ) {
            /* Failed Login (invalid identifier) */
        } else {
            return $record['pseudoUser'] + "/" + $record['motDePasse'];
        }
    }
    else
    {
        /* Failed Login (invalid identifier) */
    }
}
