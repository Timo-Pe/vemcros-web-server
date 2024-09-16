<?php

namespace App\DataFixtures;

use App\Entity\Accounts;
use App\Entity\Clients;
use App\Entity\Invoices;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $clients = [];
        for ($i = 0; $i < 20; $i++) {
            $clients[$i] = new Clients();
            $clients[$i]
                ->setFirstname($faker->firstName)
                ->setCreationDate(new DateTimeImmutable('now'))
                ->setPhone($faker->phoneNumber)
                ->setLastname($faker->lastName)
                ->setAddress($faker->address)
                ->setEmail($faker->email);
            $manager->persist($clients[$i]);

            $account = new Accounts();

            $random_account_balance = 1000 + mt_rand() / mt_getrandmax() * (20000 - 1000);

            $account
                ->setBalance($random_account_balance)
                ->setClients($clients[$i])
                ->setOpeningDate(new DateTimeImmutable('now'));
            $manager->persist($account);

            for ($j = 0; $j < rand(1, 5); $j++) {
                $invoice = new Invoices();

                $random_invoice_amount = 100 + mt_rand() / mt_getrandmax() * (5000 - 100);
                $current_date = new DateTime();
                $one_year_later = new DateTime('+1 year');
                $random_timestamp = mt_rand($current_date->getTimestamp(), $one_year_later->getTimestamp());
                $random_date = new DateTime();
                $random_date->setTimestamp($random_timestamp);

                $invoice
                    ->setClients($clients[$i])
                    ->setTotalAmount($random_invoice_amount)
                    ->setInvoiceDate(new DateTimeImmutable('now'))
                    ->setDueDate($random_date)
                    ->setInterestRate(10)
                    ->setInterestAmount($random_invoice_amount * 0.10)
                    ->setStatus('unpaid');

                $manager->persist($invoice);
            }
        }

        $manager->flush();
    }
}
