<?php

/**
 * Provides methods needed to generate and verify MicroIDs. 
 * All MicroID formats are supported.
 *
 * This package is based on original code written by Will Norris (will@willnorris.com).
 * That work is used here by permission.
 *
 * Hey, if you download this module, drop me an email! That's the fun
 * part of this whole open source thing.
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

require_once 'Crypt/MicroID/AlgorithmNotFoundException.php';

/**
 * Crypt_MicroID
 *
 * <code>
 * <?php
 * require_once 'Crypt/MicroID.php';
 * try {
 *     $microID = Crypt_MicroID::generate('xmpp:stpeter@jabber.org', 'https://www.xmpp.net/');
 *     echo $microID;
 * } catch (Crypt_MicroID_AlgorithmNotFoundException $e) {
 *     echo $e->getMessage(); 
 * }
 * ?>
 * </code>
 *
 * @category Crypt
 * @package  Crypt_MicroID  
 * @author   Kurt Wilms <wilms@cs.umn.edu>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link     http://pear.php.net/package/Crypt_MicroID
 */
class Crypt_MicroID
{

    /**
     * The MicroID "algo"
     *
     * @var         string      ALGORITHM
     * @link http://microid.org/microid.html#generation
     */
    const ALGORITHM = 'sha1';

    /**
     * Compute a MicroID for the given identity and service URIs.
     *
     * @param string  $identity  identity URI
     * @param string  $service   service URI
     * @param string  $algorithm algorithm used to calculating the hash
     * @param boolean $legacy    if true, uritypes and algorithm will not be prefixed to the generated microid
     *
     * @return string the calculated microid
     * @throws Crypt_MicroID_AlgorithmNotFoundException
     * @link http://microid.org/draft-miller-microid-01.html#generation
     */
    public static function generate($identity, $service, 
        $algorithm = self::ALGORITHM, $legacy = false) 
    {

        $microID = '';

        // Add uritypes and algorithm if not using legacy mode
        if (!$legacy) {
            $microID .= substr($identity, 0, strpos($identity, ':')) . '+' .
                substr($service, 0, strpos($service, ':')) . ':' .
                strtolower($algorithm) . ':';
        }

        // Try message digest engine
        if (function_exists('hash')) {
            if (in_array(strtolower($algorithm), hash_algos())) {
                return $microID .= hash($algorithm, hash($algorithm, $identity) . 
                    hash($algorithm, $service));
            }
        }

        // Try mhash engine
        if (function_exists('mhash')) {
            $hash_method_constant = 'MHASH_' . strtoupper($algorithm);
            if (defined($hash_method_constant)) { 
                $hash_method = constant($hash_method_constant);
                $identity_hash = bin2hex(mhash($hash_method, $identity));
                $service_hash = bin2hex(mhash($hash_method, $service));
                return $microID .= bin2hex(mhash($hash_method, $identity_hash . 
                    $service_hash));
            }
        }

        // Direct string function
        if (function_exists($algorithm)) { 
            return $microID .= $algorithm($algorithm($identity) .
                $algorithm($service));
        }

        throw new Crypt_MicroID_AlgorithmNotFoundException(
                    "MicroID: unable to find adequate function",
                     "for algorithm '$algorithm'"
                    );

    }


    /**
     * Compute a MicroID for the given identity and service 
     * URIs and verify that it matches the provided MicroID.  
     *
     * The provided ID can be in the legacy format (without URI 
     * types or algorithm), in which case the SHA1 algorithm will 
     * be assumed.
     *
     * @param string $identity identity URI
     * @param string $service  service URI
     * @param string $microID  existing MicroID to test against
     *
     * @return boolean true if the computed microid matches the provided MicroID
     * @throws Crypt_MicroID_AlgorithmNotFoundException
     * @link http://microid.org/draft-miller-microid-01.html#processing
     */
    public static function verify($identity, $service, $microID) 
    {

        $algorithm = self::ALGORITHM;
        $legacy    = true;        
        $id_parts  = explode(':', $microID);

        // Not legacy mode
        if (sizeof($id_parts) == 3) {
            $algorithm = $id_parts[1];
            $legacy    = false;
        }

        return ( self::generate($identity,
                                            $service,
                                            $algorithm,
                                            $legacy) == $microID
                    );
    }

}

