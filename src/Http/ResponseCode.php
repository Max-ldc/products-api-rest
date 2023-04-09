<?php

namespace App\Http;

class ResponseCode
{
    // Success
    const OK = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;

    // Client Error
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const UNPROCESSABLE_CONTENT = 422;

    // Server Error
    const INTERNAL_SERVER_ERROR = 500;

    // public function getReponseCodeMessage(int $code): string
    // {
    //     switch ($code) {
    //         case self::BAD_REQUEST:
    //             return "The server cannot or will not process the request due to something that is perceived to be a client error (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).";
    //             break;
    //         case self::NOT_FOUND:
    //             return "The server cannot find the requested resource. In the browser, this means the URL is not recognized.";
    //             break;
    //         case self::METHOD_NOT_ALLOWED:
    //             return "The request method is known by the server but is not supported by the target resource.";
    //             break;
    //         case self::UNPROCESSABLE_CONTENT:
    //             return "The request was well-formed but was unable to be followed due to semantic errors.";
    //             break;
    //         case self::INTERNAL_SERVER_ERROR:
    //             return "The server has encountered a situation it does not know how to handle.";
    //             break;
    //         default:
    //             return "Please contact the administrator.";
    //     }
    // }
}
