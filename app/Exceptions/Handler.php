<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(Throwable $e, Request $request): Response
        {
            return $this->customException($e, $request);
        });
    }

    public function customException(Throwable $e, Request $request): Response
    {
        /**
         * @TODO
         * 1. 응답 종류별 케이스를 나누어 처리를 할 필요가 있어보임.
         * 2. customException을 내부 method가 아닌 외부 파일로 따로 빼야하는지?
         * 3. render 이외에도 report에 대한 작업 처리는 어떻게 해야하는지?
         */
        return response('Exception Handleing Test', 404);
    }
}
