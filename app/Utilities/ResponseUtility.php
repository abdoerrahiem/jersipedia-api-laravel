<?php

namespace App\Utilities;

use Illuminate\Http\JsonResponse;

class ResponseUtility
{
    /**
     * @param $data
     * @param $code
     * @param false $use_pagination
     * @param int $total
     * @param int $limit
     * @param int $page
     * @return JsonResponse
     */
    public static function makeResponse($data, $code, bool $use_pagination = false, int $limit = 10, int $page = 1, int $max_page = 1,): JsonResponse
    {
        if ($use_pagination) {
            return response()->json([
                "code" => $code,
                "data" => $data,
                "pagination" => [
                    "max_page" => $max_page,
                    "limit" => $limit,
                    "page" => $page
                ]
            ], $code);
        }

        return response()->json([
            "code" => $code,
            "data" => $data
        ], $code);
    }

    // public static function toJson(array $res = [], int $code = 0, bool $use_pagination = false, int $total = 0, int $limit = 10, int $page = 1): JsonResponse
    // {
    //     if ($use_pagination) {
    //         return response()->json([
    //             "code" => $code,
    //             "data" => $res,
    //             "meta" => [
    //                 "total" => $total,
    //                 "limit" => $limit,
    //                 "page" => $page
    //             ]
    //         ], $code);
    //     }
    //     return response()->json($res, $code);
    // }
}
