<?php

/**
 * notification_center extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2015, terminal42
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    LGPL
 */

namespace HeimrichHannot\NotificationCenterPlus;

use NotificationCenter\Model\QueuedMessage;

class QueuedMessagePlus extends QueuedMessage
{

    /**
     * Get the tokens
     *
     * @return array
     */
    public function getTokens()
    {
        return (array)json_decode($this->tokens, true);
    }
}
