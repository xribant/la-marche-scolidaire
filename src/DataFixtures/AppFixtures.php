<?php


namespace App\DataFixtures;


use App\Entity\User;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $datetime = new \DateTime('NOW');
        $datetime->setTimezone(new DateTimeZone('Europe/Paris'));

        $user->setPassword($this->encoder->encodePassword($user, 'Helix2014'));

        $user->setEmail('xribant@gmail.com');
        $user->setFirstName('Xavier');
        $user->setLastName('Ribant');
        $user->setRoles((array)'ROLE_ADMIN');
        $user->setCreatedAt($datetime);
        $user->setUpdatedAt($datetime);

        $manager->persist($user);
        $manager->flush();

    }
}
