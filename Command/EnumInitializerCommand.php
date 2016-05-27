<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\EnumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class EnumInitializerCommand
 * @package Positibe\Bundle\EnumBundle\Command
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class EnumInitializerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('positibe:enum:initialize')
            ->setDescription('Cargar la configuración de los enumeradores');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inializer = $this->getContainer()->get('positibe_enum.enum_initializer');
        try {
            $inializer->initialize();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }

        $output->writeln('<info>Enumeradores cargados en base de datos</info>');
    }

} 