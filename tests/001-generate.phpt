--TEST--
Crypt_MicroID::generate()
--FILE--
<?php

require_once 'tests-config.php';

try {
    $identity = "mailto:stpeter@jabber.org";
    $service  = "http://www.xmpp.net/";
    $microID  = Crypt_MicroID::generate($identity, $service);
    var_dump($microID);
} catch (PEAR_Exception $error) {
    echo 'UH OH! ' . $error->getMessage() . "\n";
}

?>
--EXPECT--
string(57) "mailto+http:sha1:ca94387152e8ea62fee73c45c4bae79e54543485"
