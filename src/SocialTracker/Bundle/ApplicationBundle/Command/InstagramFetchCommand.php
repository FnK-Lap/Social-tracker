<?php

namespace SocialTracker\Bundle\ApplicationBundle\Command;

use igorw;
use SocialTracker\Bundle\ApplicationBundle\Entity\InstagramPost;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstagramFetchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('social:instagram:fetch')
            ->setDescription('Fetch instagram feeds')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $instagram = $this->getContainer()->get('instagram');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $users = $em->getRepository('SocialTrackerApplicationBundle:User')->findAll();

        // We are going to fetch the feed of many users
        foreach ($users as $user) {
            $accessToken = $user->getInstagramAccessToken();

            if ($accessToken) {
                $feed = $instagram->getUserFeed($accessToken, null, null, $user->getInstagramMaxId());

                // Sort array by creatd_time
                $tmp = array();
                foreach($feed['data'] as &$ma) {
                    $tmp[] = &$ma["created_time"];
                }

                array_multisort($tmp, $feed['data']);

                if (count($feed['data']) > 0) {
                    $user->setInstagramMaxId(igorw\get_in($feed, ['data', count($feed['data'])-1, 'id']));
                    $em->persist($user);

                    foreach ($feed['data'] as $post) {
                        $instagramPost = new InstagramPost();
                        $instagramPost->setInstagramId($post['id'])
                                      ->setUser($user)
                                      ->setCreatedTime($post['created_time'])
                                      ->setContent(json_encode($post));
                        $em->persist($instagramPost);
                    }
                    $output->writeln($user->getUsername(). ' fetch ' . count($feed['data']) . ' posts');
                } else {
                    $output->writeln($user->getUsername(). ' fetch 0 post');
                }
                $em->flush();
            } else {
                $output->writeln($user->getUsername() . ' : No Token');
            }
        }

        $output->writeln('Done');
    }
}