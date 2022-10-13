<?php

namespace App\Exceptions;

use GuzzleHttp\Exception\ServerException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        if (! str_starts_with($request->path(), 'api/')) {
            return parent::render($request, $e);
        }

        $message = '';
        switch (get_class($e)) {
            case 'Illuminate\Database\Eloquent\ModelNotFoundException':
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = '422 | Нет результатов запроса';
                break;

            case 'TypeError':
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = $e->getMessage() ?: '422 | Не корректный тип ключа';
                break;

            case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                $statusCode = Response::HTTP_NOT_FOUND;
                $message = '404 | ресурс не найден';
                break;

            case 'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException':
                $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
                $message = '405 | Данный метод не поддерживается';
                break;

            case 'Illuminate\Database\QueryException':
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = '500 | Ошибка в работе базы данных';
                break;

            case 'BadMethodCallException':
            case 'Illuminate\Http\Client\ConnectionException':
                $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
                $message = '503 | Сервис недоступен';
                break;

            case 'Illuminate\Http\Client\HttpClientException':
                $statusCode = $e->getCode() ?: Response::HTTP_UNPROCESSABLE_ENTITY;
                break;

            case 'Elasticsearch\Common\Exceptions\Forbidden403Exception':
                $message = '403 | Недостаточно прав';
                $statusCode = Response::HTTP_FORBIDDEN;
                break;

            case 'Illuminate\Auth\AuthenticationException':
                $message = '401 | Недостаточно прав';
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;

            case 'GuzzleHttp\Exception\ServerException':
                $message = $e->getResponse()->getBody()->getContents();
                $statusCode = $e->getCode() ?: Response::HTTP_UNPROCESSABLE_ENTITY;
                break;

            case 'Illuminate\Http\Exceptions\ThrottleRequestsException':
                $message = '429 | Превышен лимит запросов';
                $statusCode = Response::HTTP_TOO_MANY_REQUESTS;
                break;

            default:
                $statusCode = Response::HTTP_I_AM_A_TEAPOT;
                $message = sprintf('%s: %s', get_class($e), $e->getMessage())?: sprintf('Я чайник. Надо добавить обработку исключения "%s" в файл app/Exceptions/Handler.php (~%s строка)', get_class($e), (__LINE__ - 5));
        }

        $response = [
            'errors' => [
                'messages' => [
                    $message ?: ($e->getMessage() ?: 'Неизвестная ошибка'),
                ],
            ],
        ];

        return response()->json(
            $response,
            $statusCode,
            [],
            App::environment(['production']) ? null : JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
    }
}
