<?php

namespace SocialTracker\Bundle\ApplicationBundle\Command;

// use igorw;
// use SocialTracker\Bundle\ApplicationBundle\Entity\Facebook;
use Facebook\FacebookSession;
use SocialTracker\Bundle\ApplicationBundle\Entity\FacebookPost;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FacebookFetchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('social:facebook:fetch')
            ->setDescription('Fetch facebook feeds')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $facebook = $this->getContainer()->get('facebook');
        $facebookHelper = $this->getContainer()->get('facebook.authentication_helper');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $users = $em->getRepository('SocialTrackerApplicationBundle:User')->findAll();

        // We are going to fetch the feed of many users
        foreach ($users as $user) {
            $accessToken = $user->getFacebookAccessToken();

            if ($accessToken) {
                // Create FBSession with access_token
                $facebookSession = $facebookHelper->newSessionFromAccessToken($accessToken);
                $feed = $facebook->getUserFeed($facebookSession, array('since' => $user->getFacebookLastPost()));

                if ($feed) {
                    $user->setFacebookLastPost(strtotime($feed[0]->created_time));
                    $em->persist($user);

                    foreach ($feed as $post) {
                        $facebookPost = new FacebookPost();
                        $facebookPost->setFacebookId($post->id)
                                     ->setUser($user)
                                     ->setCreatedTime(strtotime($post->created_time))
                                     ->setContent(json_encode($post));
                        $em->persist($facebookPost);
                    }
                    $em->flush();
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