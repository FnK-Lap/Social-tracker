<?php

namespace SocialTracker\Bundle\ApplicationBundle\Command;

use SocialTracker\Bundle\ApplicationBundle\Entity\YoutubePost;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class YoutubeFetchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('social:youtube:fetch')
            ->setDescription('Fetch youtube feeds')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $youtube = $this->getContainer()->get('youtube');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $users = $em->getRepository('SocialTrackerApplicationBundle:User')->findAll();

        // We are going to fetch the feed of many users
        foreach ($users as $user) {
            $accessToken = $user->getYoutubeAccessToken();

            if ($accessToken) {
                $feed = $youtube->getUserFeed($accessToken);

                if ($feed) {
                    
                    $output->writeln($user->getUsername(). ' fetch <fg=cyan>' . count($feed) . '</fg=cyan> posts');
                } else {
                    $output->writeln($user->getUsername(). ' fetch <fg=cyan>0</fg=cyan> posts');
                }
            } else {
                $output->writeln($user->getUsername() . ' : <fg=red>No Token</fg=red>');
            }
        }

        $output->writeln('Done');
    }
}