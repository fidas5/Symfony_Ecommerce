<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setLastname('Doe');
        $admin->setFirstname('John');
        $admin->setAddress('345 River Street');
        $admin->setZipcode('12345');
        $admin->setCity('New York');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('luke.skywalker@jedi.com');
        $user->setLastname('Skywalker');
        $user->setFirstname('Luke');
        $user->setAddress('123 Tatooine Street');
        $user->setZipcode('54321');
        $user->setCity('Mos Eisley');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('obi.wan.kenobi@jedi.com');
        $user->setLastname('Kenobi');
        $user->setFirstname('Obi-Wan');
        $user->setAddress('456 Coruscant Street');
        $user->setZipcode('67890');
        $user->setCity('Coruscant');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('anakin.skywalker@sith.com');
        $user->setLastname('Skywalker');
        $user->setFirstname('Anakin');
        $user->setAddress('789 Mustafar Street');
        $user->setZipcode('13579');
        $user->setCity('Mustafar');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('sheev.palpatine@sith.com');
        $user->setLastname('Palpatine');
        $user->setFirstname('Sheev');
        $user->setAddress('101 Death Star Street');
        $user->setZipcode('24680');
        $user->setCity('Death Star');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('leia.organa@rebel.com');
        $user->setLastname('Organa');
        $user->setFirstname('Leia');
        $user->setAddress('789 Alderaan Avenue');
        $user->setZipcode('54321');
        $user->setCity('Alderaan');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('han.solo@smuggler.com');
        $user->setLastname('Solo');
        $user->setFirstname('Han');
        $user->setAddress('777 Millennium Falcon Street');
        $user->setZipcode('77777');
        $user->setCity('Corellia');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('darth.vader@sith.com');
        $user->setLastname('Vader');
        $user->setFirstname('Darth');
        $user->setAddress('666 Death Star Avenue');
        $user->setZipcode('66666');
        $user->setCity('Death Star');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('yoda@jedi.com');
        $user->setLastname('Yoda');
        $user->setFirstname('Master');
        $user->setAddress('123 Dagobah Swamp');
        $user->setZipcode('11111');
        $user->setCity('Dagobah');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('padme.amidala@naboo.com');
        $user->setLastname('Amidala');
        $user->setFirstname('Padmé');
        $user->setAddress('456 Theed Palace');
        $user->setZipcode('99999');
        $user->setCity('Naboo');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('grand.moff.tarkin@empire.com');
        $user->setLastname('Tarkin');
        $user->setFirstname('Wilhuff');
        $user->setAddress('101 Death Star Avenue');
        $user->setZipcode('12345');
        $user->setCity('Death Star');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('qui-gon.jinn@jedi.com');
        $user->setLastname('Jinn');
        $user->setFirstname('Qui-Gon');
        $user->setAddress('555 Jedi Temple Street');
        $user->setZipcode('88888');
        $user->setCity('Coruscant');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
