<?php

namespace Level51\PayloadInjector;

use SilverStripe\Core\Injector\Injectable;

class PayloadInjector {

    use Injectable;

    /**
     * Staged payload for DOM injection
     *
     * @var array
     */
    private $payload = [];

    /**
     * Stages data for commit
     *
     * @param array $data
     *
     * @return $this
     */
    public function stage(array $data) {
        if (!is_array($data)) throw new PayloadInjectorException('Only array data can be staged.');

        $this->payload = array_merge($this->payload, $data);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPayload() {
        return count($this->payload) > 0;
    }

    /**
     * @return false|string
     */
    public function commit() {
        return json_encode($this->payload, JSON_UNESCAPED_SLASHES);
    }

    /**
     * @return string
     */
    public function render() {
        return "<script>window.payload = " . $this->commit() . "</script>";
    }
}

class PayloadInjectorException extends \Exception {
}
