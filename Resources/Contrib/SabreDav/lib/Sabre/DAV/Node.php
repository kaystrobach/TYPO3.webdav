<?php

/**
 * Node class
 *
 * This is a helper class, that should aid in getting nodes setup.
 *
 * @package Sabre
 * @subpackage DAV
 * @copyright Copyright (C) 2007-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://sabre.io/license/ Modified BSD License
 */
abstract class Sabre_DAV_Node implements Sabre_DAV_INode {

    /**
     * Returns the last modification time
     *
     * In this case, it will simply return the current time
     *
     * @return int
     */
    public function getLastModified() {

        return time();

    }

    /**
     * Deletes the current node
     *
     * @throws Sabre_DAV_Exception_Forbidden
     * @return void
     */
    public function delete() {

        throw new Sabre_DAV_Exception_Forbidden('Permission denied to delete node');

    }

    /**
     * Renames the node
     *
     * @throws Sabre_DAV_Exception_Forbidden
     * @param string $name The new name
     * @return void
     */
    public function setName($name) {

        throw new Sabre_DAV_Exception_Forbidden('Permission denied to rename file');

    }

}

