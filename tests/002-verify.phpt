--TEST--
Crypt_MicroID::verify()
--FILE--
<?php

require_once 'tests-config.php';

try {
    $identity = "mailto:stpeter@jabber.org";
    $service  = "http://www.xmpp.net/";
    $microID  = "mailto+http:sha1:ca94387152e8ea62fee73c45c4bae79e54543485";
    $result   = Crypt_MicroID::verify($identity, $service, $microID);
    var_dump($result);
} catch (PEAR_Exception $error) {
    echo 'UH OH! ' . $error->getMessage() . "\n";
}

?>
--EXPECT--
bool(true)
