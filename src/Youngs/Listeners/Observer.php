<?php

namespace Youngs\Listeners;

/**
 * @Author youngshuo@qq.com
 * @Create Time:   2018-09-03 16:54:54
 * @Description:
 */
interface Observer {

    public function update($event_info = null);
}
