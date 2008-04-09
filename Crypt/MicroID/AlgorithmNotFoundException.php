<?php

/**
 * The Crypt_MicroID Exception
 *
 * PHP version 5.1.0+
 *
 * LICENSE: This source file is subject to the New BSD license that is 
 * available through the world-wide-web at the following URI:
 * http://www.opensource.org/licenses/bsd-license.php. If you did not receive  
 * a copy of the New BSD License and are unable to obtain it through the web, 
 * please send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category Crypt
 * @package  Crypt_MicroID
 * @author   Kurt Wilms <wilms@cs.umn.edu> 
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Crypt_MicroID
 * @link     http://microid.org/
 */ 

require_once 'PEAR/Exception.php';

/**
 * Crypt_MicroID_AlgorithmNotFoundException
 *
 * @category Crypt
 * @package  Crypt_MicroID
 * @author   Kurt Wilms <wilms@cs.umn.edu>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link     http://pear.php.net/package/Crypt_MicroID
 */
class Crypt_MicroID_AlgorithmNotFoundException extends PEAR_Exception
{

}

?>
