<?php
/**
 * Created by LiuWebDev.
 */

namespace RestBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InactiveReminderCommand
 * @package RestBundle\Command
 *
 * Usage:
 * app/console cron:inactivereminder
 */
class InactiveReminderCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this->setName('cron:inactivereminder')
            ->setDescription('Send inactive client reminder email');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);
        $output->writeln('<comment>Running Cron inactive client reminder...</comment>');
        $this->output = $output;

        $container  = $this->getContainer();
        $em         = $container->get('doctrine.orm.entity_manager');
        $userRepo   = $em->getRepository('RestBundle:User');
        $clients    = $userRepo->getAllClients();
        $now        = new \DateTime();

        foreach ($clients as $client)
        {
            $last_active = $client->getLastActive();

            if(! $client->getLastActive()) continue;

            $due_time = $this->calculateTimeDiff($now->format('Y-m-d H:i:s'), $last_active->format('Y-m-d H:i:s'), 6);

            if(! $due_time) continue;

            $admins     = $client->getAdmins();
            $subject    = 'Inactive User Reminder';
            $body       = '';

            if($client->getProfile()->getLinkedinUrl() != '')
            {
                $body .= '<a href="' . $client->getProfile()->getLinkedinUrl() . '">';
                $body .= $client->getFullName();
                $body .= '</a>';
            } else {
                $body .= $client->getFullName();
            }

            $body .= ' is inactive during ' . $due_time . '.';

            foreach($admins as $admin)
            {
                if($admin->isSDR()) continue;
                $container->get('user.mailer')->sendEmail($subject, $admin->getEmail(), $body);
            }
        }

        $timeEnd = microtime(true);
        $output->writeln(sprintf('<comment>Inactive Client Reminder Task Done! Execution time: %.4F sec.</comment>', $timeEnd - $timeStart));
    }

    /**
     * Calculate difference of time as days and hours
     * @param $now
     * @param $last
     * @param $interval
     * @return null|string
     */
    public function calculateTimeDiff($now, $last, $interval)
    {
        $secDiff    = strtotime($now) - strtotime($last);
        $hourDiff   = floor($secDiff / (60 * 60));
        $times      = floor($hourDiff / $interval);

        if($hourDiff != $times * $interval) return null;

        if($hourDiff <= 24)
        {
            return $hourDiff . ' hours';
        } else {
            $daysDiff   = floor($hourDiff / 24);
            $hour       = $hourDiff - $daysDiff * 24;
            return $daysDiff . ' days and ' . $hour . ' hours';
        }
    }

}

