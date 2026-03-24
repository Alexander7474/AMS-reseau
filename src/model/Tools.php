
<?php

/**
 * @brief Diverse fonction utile
 */
class Tools
{
        /**
        * @brief Retourne le nombre d'addresse disponnible d'un réseau
        *
        * @param mask du réseau
        */
        public static function totalAddr($netmask) {
                $bits = 0;
                foreach (explode('.', $netmask) as $octet) {
                        $bits += substr_count(decbin($octet), '1');
                }
                return pow(2, 32 - $bits);
        }
}
