<?php
/**
 * Created by LiuWebDev
 */

namespace RestBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BirthdayReminderCommand
 * @package RestBundle\Command
 *
 * Usage:
 * app/console cron:birthreminder
 */
class BirthdayReminderCommand extends ContainerAwareCommand
{
    private $output;

    protected function configure()
    {
        $this->setName('cron:birthreminder')
            ->setDescription('Send birthday reminder email');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);
        $output->writeln('<comment>Running Cron birthday reminder...</comment>');
        $this->output = $output;

        $container  = $this->getContainer();
        $em         = $container->get('doctrine.orm.entity_manager');
        $userRepo   = $em->getRepository('RestBundle:User');
        $clients    = $userRepo->getAllClients();
        $today      = new \DateTime();

        foreach ($clients as $client)
        {
            $birthdate  = $client->getProfile()->getBirthDate();
            $dateDiff   = $this->calculateDateDiff($birthdate->format('Y-m-d H:i:s'), $today->format('Y-m-d H:i:s'));

            if($dateDiff == 7){
                $admins  = $client->getAdmins();
                $subject = 'Birthdate is coming soon.';
                $body    = '';

                if($client->getProfile()->getLinkedinUrl() != ''){
                    $body .= '<a href="' . $client->getProfile()->getLinkedinUrl() . '">';
                    $body .= $client->getFullName();
                    $body .= '</a>';
                } else {
                    $body .= $client->getFullName();
                }
                $body .= '\'s birthdate is ' . $birthdate->format('m/d') . '.\n';
                $body .= 'Please send a gift card.';

                foreach ($admins as $admin){
                    if($admin->isSDR()) continue;
                    $container->get('user.mailer')->sendEmail($subject, $admin->getEmail(), $body);
                }
            }
        }

        $timeEnd = microtime(true);
        $output->writeln(sprintf('<comment>Birthday Reminder Task Done! Execution time: %.4F sec.</comment>', $timeEnd - $timeStart));
    }

    /**
     * get due date from first date to second date
     * @param $first
     * @param $second
     * @return float
     */
    private function calculateDateDiff($first, $second)
    {
        $firstDate  = date('d-m-2000', strtotime($first));
        $secondDate = date('d-m-2000', strtotime($second));
        $dateDiff   = strtotime($firstDate) - strtotime($secondDate);
        $totalDays  = floor($dateDiff / (60 * 60 * 24));
        if($dateDiff > $totalDays * 60 * 60 * 24) $totalDays++;
        return $totalDays;
    }

}



















































