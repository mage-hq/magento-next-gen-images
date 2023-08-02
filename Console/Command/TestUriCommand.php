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

namespace Magehqm2\NextGenImages\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magehqm2\NextGenImages\Image\File as ImageFile;

class TestUriCommand extends Command
{
    /**
     * @var ImageFile
     */
    private $imageFile;

    /**
     * TestUriCommand constructor.
     * @param ImageFile $imageFile
     * @param string|null $name
     */
    public function __construct(
        ImageFile $imageFile,
        string $name = null
    ) {
        parent::__construct($name);
        $this->imageFile = $imageFile;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('next-gen-images:test-uri')
            ->setDescription('Test URI with convertors')
            ->addArgument('uri', InputArgument::REQUIRED, 'URI');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uri = (string)$input->getArgument('uri');
        $path = $this->imageFile->resolve($uri);
        $output->writeln('Resolved path: ' . $path);
        $output->writeln('Path exists: ' . (int)$this->imageFile->uriExists($uri));

        return -1;
    }
}