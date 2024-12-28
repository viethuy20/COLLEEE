<?php
namespace App\Exceptions;

class LockException extends \Exception {
    const LOCK1_TYPE = 1;
    const LOCK2_TYPE = 2;

    private $type = null;

    function __construct(int $type) {
        parent::__construct();
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }
}