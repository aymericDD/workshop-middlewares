<?php
/**
 * Created by PhpStorm.
 * User: Rico
 * Date: 17/11/2016
 * Time: 19:54
 */

namespace Superpress\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Superpress\Middleware;
use Zend\Diactoros\Response\TextResponse;

class ErrorHandler implements Middleware
{
    /**
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        }catch (\Throwable $throwable) {
            $whoops = $this->createWhoops();
            $output = $whoops->writeToOutput();
            return new TextResponse($output, 500);
        }
    }

    public function createWhoops()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        return $whoops;
    }
}