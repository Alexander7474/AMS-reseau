
<?php

/**
 * @brief Gère la validité des entrées de l'utilisateur
 */
class Validator
{
    /**
     * @brief Vérifie que la valeur est un entier entre 0 et 255
     */
    public static function isIpByte($value): bool
    {
        return is_int($value) && $value >= 0 && $value <= 255;
    }
 
    /**
     * @brief Vérifie que la valeur est un entier entre min et max
     */
    public static function isIntBet($value, $min, $max): bool
    {
        return is_int($value) && $value >= $min && $value <= $max;
    }
 
    /**
     * @brief Vérifie que la valeur est une IP valide 
     */
    public static function isValidIp($value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @brief Vérifie que la valeur est une chaîne valide
     *        et ne contient pas de contenu malveillant basique
     */
    public static function isSafeString($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Bloque quelques injections courantes (SQL / XSS basiques)
        $pattern = '/(script|<|>|--|\*\/|\/\*|;|\||&|\{|\}|SELECT|INSERT|DELETE|UPDATE|DROP)/i';

        if (preg_match($pattern, $value)) {
            return false;
        }

        return true;
    }
}
