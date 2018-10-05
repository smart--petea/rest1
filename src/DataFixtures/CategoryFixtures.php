<?php
//src/DataFixtures/CategoryFixtures.php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
        (1, 'Fashion', '',, '2014-05-30 17:34:33'),
        (2, 'Electronics', 'Gadgets, drones and more.', '2014-06-01 00:35:07', '2014-05-30 17:34:33'),
        (3, 'Motors', 'Motor sports and more', '2014-06-01 00:35:07', '2014-05-30 17:34:54'),
        (5, 'Movies', 'Movie products.', '0000-00-00 00:00:00', '2016-01-08 13:27:26'),
        (6, 'Books', 'Kindle books, audio books and more.', '0000-00-00 00:00:00', '2016-01-08 13:27:47'),
        (13, 'Sports', 'Drop into new winter gear.', '2016-01-09 02:24:24', '2016-01-09 01:24:24');
         */

        $category = new Category();
        $category->setName('Fashion');
        $category->setDescription('Category for anything related to fashion.');
        $category->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2014-06-01 00:35:07'));
        $category->setModified(\DateTime::createFromFormat('Y-m-d H:i:s', '2014-06-01 00:35:07'));
        $manager->persist($category);

        $manager->flush();
    }
}
