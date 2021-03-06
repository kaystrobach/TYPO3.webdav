<?php

/**
 * Principal collection
 *
 * This is an alternative collection to the standard ACL principal collection.
 * This collection adds support for the calendar-proxy-read and
 * calendar-proxy-write sub-principals, as defined by the caldav-proxy
 * specification.
 *
 * @package Sabre
 * @subpackage CalDAV
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class Sabre_CalDAV_Principal_Collection extends Sabre_DAVACL_AbstractPrincipalCollection {

    /**
     * Returns a child object based on principal information
     *
     * @param array $principalInfo
     * @return Sabre_CalDAV_Principal_User
     */
    public function getChildForPrincipal(array $principalInfo) {

        return new Sabre_CalDAV_Principal_User($this->principalBackend, $principalInfo);

    }

}
