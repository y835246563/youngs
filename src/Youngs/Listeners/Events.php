<?php

namespace Youngs\Listeners;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-09-03 16:40:51
 * @Description:
 */
use Youngs\Listeners\Observer;

class Events {

    private $observers = [];

    public function add(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }

}
