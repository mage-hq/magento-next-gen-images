<?php
/**
 * Magehqm2
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the magehq.com license that is
 * available through the world-wide-web at this URL:
 * https://magehq.com/license.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   magehqm2
 * @package    Magehqm2_NextGenImages
 * @copyright  Copyright (c) 2023 magehqm2 (https://magehq.com/)
 * @license    https://magehq.com/license.html
 */
 
declare(strict_types=1);

namespace Magehqm2\NextGenImages\Logger;

use Psr\Log\LoggerInterface;
use Magehqm2\NextGenImages\Config\Config;

class Debugger
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Debugger constructor.
     *
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        Config $config,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param string $msg
     * @param mixed $data
     *
     * @return bool
     */
    public function debug(string $msg, $data = null): bool
    {
        if ($this->config->isLogging() === false) {
            return false;
        }

        if (!empty($data)) {
            $msg .= ': ' . var_export($data, true);
        }

        $this->logger->notice($msg);
        return true;
    }
}
