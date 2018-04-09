<?php

namespace RestBundle\Command;

use Doctrine\ORM\EntityManager;
use Fetch\Server;
use Fetch\Message as ImapMessage;
use RestBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DeployCommand
 * @package AppBundle\Command
 *
 * Usage:
 * app/console c:d
 */
class MailCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this
            ->setName('cron:mail')
            ->setDescription('Deploy on prod')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time_start = microtime(true);
        $output->writeln('<comment>Running Cron...</comment>');
        $this->output = $output;

        $container = $this->getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine.orm.entity_manager');
        $messages = $em->getRepository('RestBundle:Message')->getUnreadMessages();
        $userRepository = $em->getRepository('RestBundle:User');

        foreach ($messages as $message) {
            $author     = $userRepository->find($message->getAuthor());
            $recipients  = $message->getUnreadUsers();

            if (!$author || $recipients->count() == 0) {
                continue;
            } 

            foreach( $recipients as $recipient )
            {
                $result = $container->get('user.mailer')
                    ->sendAdminChatMessage($author, $message, $recipient);
             
                if ($result !== 0) {
                    $message->removeUnreadUser($recipient);
                }
            }
        }

        $messages = $container->get('imap.mailbox')->import();

        foreach ($messages as $message) {
            $em->persist($message);
        }

        $em->flush();

        $time_end = microtime(true);
        $output->writeln(sprintf('<comment>Done! Execution time: %.4F sec. </comment>', $time_end - $time_start));
    }
}
