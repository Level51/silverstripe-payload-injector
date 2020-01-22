<?php

namespace Level51\PayloadInjector;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class PayloadInjector {

    use Injectable;
    use Configurable;

    /**
     * @config
     *
     * Merge payload recursively to avoid overriding existing data
     * @var bool
     */
    private static $merge_recursive = true;

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

        $this->payload = $this->config()->get('merge_recursive') ?
            array_merge_recursive($this->payload, $data) :
            array_merge($this->payload, $data);

        return $this;
    }

    /**
     * Clear previously staged data
     */
    public function clearStage() {
        $this->payload = [];
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
