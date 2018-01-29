<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Ui\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SWG\Info(title="Source Code Search API", version="1.0")
 */
class SwaggerController
{
    public function __invoke(Request $request): Response
    {
        $swaggerJson = \Swagger\scan(__DIR__);
        return new JsonResponse((string)$swaggerJson, 200, [], true);
    }
}
