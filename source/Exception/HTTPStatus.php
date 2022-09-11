<?php

namespace Source\Exception;

enum HTTPStatus : int {
    CASE OK = 200;
    CASE BAD_REQUEST = 400;
    CASE UNAUTHORIZED = 401;
    CASE FORBIDDEN = 403;
    CASE NOT_FOUND = 404;
    CASE UNPROCESSABLE_ENTITY  = 422;
    CASE INTERNAL_SERVER_ERROR = 500;
    
}   