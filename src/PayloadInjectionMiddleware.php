<?php

namespace Level51\PayloadInjector;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;

class PayloadInjectionMiddleware implements HTTPMiddleware {

    /**
     * @config
     *
     * @var array
     */
    private static $dependencies = [
        'injector' => '%$Level51\PayloadInjector\PayloadInjector'
    ];

    /**
     * Injected PlayoadInjector singleton instance
     *
     * @var PayloadInjector
     */
    public $injector;

    /**
     * Processes the request and injects payload if staged
     *
     * @param HTTPRequest $request
     * @param callable    $delegate
     *
     * @return HTTPResponse
     */
    public function process(HTTPRequest $request, callable $delegate) {
        /* @var HTTPResponse $response */
        $response = $delegate($request);

        if ($response !== null && $response->getBody() && $this->injector->hasPayload()) {
            $response->setBody($this->injectIntoDom($response->getBody()));
        }

        return $response;
    }

    /**
     * Manipulates the body by injecting payload into the DOM
     *
     * @param $body
     *
     * @return mixed
     */
    private function injectIntoDom($body) {
        $replacement = $this->injector->render();

        $scriptNeedle = '<script';
        $scriptPos = strpos($body, $scriptNeedle);
        if ($scriptPos !== false) {
            return substr_replace(
                $body,
                $replacement . $scriptNeedle,
                $scriptPos,
                strlen($scriptNeedle));
        }

        $bodyNeedle = '</body>';
        $bodyPos = strpos($body, $bodyNeedle);
        if ($bodyPos !== false) {
            return substr_replace(
                $body,
                $replacement . $bodyNeedle,
                $bodyPos,
                strlen($bodyNeedle)
            );
        }

        return $body;
    }
}
