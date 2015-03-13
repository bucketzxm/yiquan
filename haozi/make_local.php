<?php
function access_url($url) {
    if ($url == '')
        return false;
    $file = '';
    $fp = fopen ( $url, 'r' ) or exit ( 'Openurlfaild!' );
    if ($fp) {
        while ( ! feof ( $fp ) ) {
            $file .= fgets ( $fp ) . "";
        }
        fclose ( $fp );
    }
    return $file;
}

access_url ( 'http://yiquanhost.duapp.com/haozi/Topic_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/haozi/Message_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/haozi/userclass_server.php?reb' );

?>