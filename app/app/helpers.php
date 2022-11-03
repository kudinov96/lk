<?php

function num_declension($number, $words): string
{
    $abs = abs( $number );
    $cases = array( 2, 0, 1, 1, 1, 2 );
    return $number . " " . $words[ ( $abs % 100 > 4 && $abs % 100 < 20 ) ? 2 : $cases[ min( $abs % 10, 5 ) ] ];
}
