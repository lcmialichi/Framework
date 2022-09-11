<?php

namespace Source\Enum;

enum State : string{

    case AC = "AC";
    case AM = "AM";
    case RR = "RR";
    case PA = "PA";
    case AP = "AP";
    case TO = "TO";
    case MA = "MA";
    case PI = "PI";
    case CE = "CE";
    case RN = "RN";
    case PB = "PB";
    case PE = "PE";
    case AL = "AL";
    case SE = "SE";
    case BA = "BA";
    case MG = "MG";
    case ES = "ES";
    case RJ = "RJ";
    case SP = "SP";
    case PR = "PR";
    case SC = "SC";
    case RS = "RS";
    case MS = "MS";
    case MT = "MT";
    case GO = "GO";
    case DF = "DF";

    public static function casesValue()
    {
        return array_column(self::cases(), 'value');
    }
}

