<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use HashPasswordProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Loader\SimpleFileLoader;
use Faker\Factory as Faker;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadBlog($manager);
        $this->loadComment($manager);
    }

    public function loadBlog(ObjectManager $manager){

        // Generate manually
        $user = $this->getReference('admin_user');

        for ($i =0; $i < 100; $i++){
            $blog = new BlogPost();
            $blog->setTitle($this->faker->realText(30));
            $blog->setContent($this->faker->realText());
            $blog->setPublished($this->faker->dateTime);
            $blog->setAuthor($user);
            $blog->setSlug($this->faker->name);

            $this->setReference("blog_post_$i", $blog);
            $manager->persist($blog);
        }
        $manager->flush();
    }

    public function loadComment(ObjectManager $manager){

        // Generate manually
        $user = $this->getReference('admin_user');

        for ($i =0; $i < 100; $i++){
            for ($j =0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->text());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($user);
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    public function loadUser(ObjectManager $manager){

        // Generate manually
//        for ($i =0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername($this->faker->userName);
            $user->setName($this->faker->firstName . ' ' . $this->faker->lastName);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin123'));

            $this->addReference('admin_user', $user);

            $manager->persist($user);
//        }
        $manager->flush();
    }
}
