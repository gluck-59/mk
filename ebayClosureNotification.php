<?php
$hash = hash_init('sha256');

hash_update($hash, $challengeCode);
hash_update($hash, $verificationToken);
hash_update($hash, $endpoint);

$responseHash = hash_final($hash);
echo $responseHash;