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

namespace Magehqm2\NextGenImages\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Filesystem\Directory\ReadFactory as DirectoryReadFactory;
use Magento\Framework\Filesystem\File\ReadFactory as FileReadFactory;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Backend\Block\Template\Context;

class ModuleVersion extends Field
{
    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var FileReadFactory
     */
    private $fileReadFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var DirectoryReadFactory
     */
    private $directoryReadFactory;
    /**
     * @var string
     */
    private $moduleName;

    /**
     * ModuleVersion constructor.
     * @param ComponentRegistrar $componentRegistrar
     * @param ModuleListInterface $moduleList
     * @param FileReadFactory $fileReadFactory
     * @param SerializerInterface $serializer
     * @param DirectoryReadFactory $directoryReadFactory
     * @param Context $context
     * @param array $data
     * @param string $moduleName
     */
    public function __construct(
        ComponentRegistrar $componentRegistrar,
        ModuleListInterface $moduleList,
        FileReadFactory $fileReadFactory,
        SerializerInterface $serializer,
        DirectoryReadFactory $directoryReadFactory,
        Context $context,
        array $data = [],
        string $moduleName = 'Magehqm2_NextGenImages'
    ) {
        parent::__construct($context, $data);
        $this->componentRegistrar = $componentRegistrar;
        $this->moduleList = $moduleList;
        $this->fileReadFactory = $fileReadFactory;
        $this->serializer = $serializer;
        $this->directoryReadFactory = $directoryReadFactory;
        $this->moduleName = $moduleName;
    }

    /**
     * Override to set a different PHTML template
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('config/module_version.phtml');

        return $this;
    }

    /**
     * Override to render the template instead of the regular output
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * Check if GD supports WebP
     *
     * @return string
     */
    public function getModuleVersion(): string
    {
        $moduleVersion = $this->getModuleVersionFromComposer();
        if ($moduleVersion) {
            return $moduleVersion;
        }

        return $this->getModuleVersionFromModuleXml();
    }

    /**
     * @return string
     */
    private function getModuleVersionFromComposer(): string
    {
        $modulePath = $this->componentRegistrar->getPath('module', $this->moduleName);
        $composerJsonFile = $modulePath . '/composer.json';

        $directoryRead = $this->directoryReadFactory->create($modulePath);
        if (!$directoryRead->isExist($composerJsonFile)) {
            return '';
        }

        $fileRead = $this->fileReadFactory->create($composerJsonFile, 'file');
        $jsonContents = (string)$fileRead->readAll();
        $data = $this->serializer->unserialize($jsonContents);
        if (isset($data['version'])) {
            return (string)$data['version'];
        }

        return '';
    }

    /**
     * @return string
     */
    private function getModuleVersionFromModuleXml(): string
    {
        $module = $this->moduleList->getOne($this->moduleName);
        if (isset($module['setup_version'])) {
            return (string)$module['setup_version'];
        }

        return '';
    }
}
