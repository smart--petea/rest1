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
        $rows = array(
            array(1, 'Fashion', 'Category for anything related to fashion.', '2014-06-01 00:35:07', '2014-05-30 17:34:33'),
            array(2, 'Electronics', 'Gadgets, drones and more.', '2014-06-01 00:35:07', '2014-05-30 17:34:33'),
            array(3, 'Motors', 'Motor sports and more', '2014-06-01 00:35:07', '2014-05-30 17:34:54'),
            array(5, 'Movies', 'Movie products.', '2016-01-08 13:27:26', '2016-01-08 13:27:26'),
            array(6, 'Books', 'Kindle books, audio books and more.', '2016-01-08 13:27:47', '2016-01-08 13:27:47'),
            array(13, 'Sports', 'Drop into new winter gear.', '2016-01-09 02:24:24', '2016-01-09 01:24:24')
        );

        foreach($rows as $row)
        {
            $category = new Category();
            $category->setName($row[1]);
            $category->setDescription($row[2]);
            $category->setCreated(\DateTime::createFromFormat('Y-m-d H:i:s', $row[3]));
            $category->setModified(\DateTime::createFromFormat('Y-m-d H:i:s', $row[4]));
            $manager->persist($category);
        }


        $manager->flush();
    }
}
