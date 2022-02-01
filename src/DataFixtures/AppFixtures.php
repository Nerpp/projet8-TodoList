<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $listUser = [
            [
                'displayName' => 'Anonyme',
                'email' => 'anonyme@gmail.com',
                'role' => [],
                'password' => '12345678',
            ],
            [
                'displayName' => 'Francis',
                'email' => 'francis@gmail.com',
                'role' => ['ROLE_ADMIN'],
                'password' => '12345678',
            ],
            [
                'displayName' => 'Gerald',
                'email' => 'gerald@gmail.com',
                'role' => ['ROLE_USER'],
                'password' => '12345678'
            ],
            [
                'displayName' => 'Monica',
                'email' => 'monica@free.fr',
                'role' => ['ROLE_USER'],
                'password' => '12345678'
            ],
            [
                'displayName' => 'Erica',
                'email' => 'erica@gmail.com',
                'role' => ['ROLE_USER'],
                'password' => '12345678'
            ],
            [
                'displayName' => 'Karl',
                'email' => 'regiaurelien@gmail.com',
                'role' => ['ROLE_USER'],
                'password' => '12345678'
            ],

        ];

        $listTask = [
            [
                'title' => 'Créer un article',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 1
            ],
            [
                'title' => 'Relire le Code',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 0
            ],
            [
                'title' => 'Updater la derniere version',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 1
            ],
            [
                'title' => 'Verifier le HTML',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 1
            ],
            [
                'title' => 'Configurer le YAML',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 0
            ],
            [
                'title' => 'Changer le lorem Ipsum',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam odio rerum veniam modi odit sit qui porro deserunt ea delectus accusamus consequatur, obcaecati quibusdam, tempore recusandae vel corporis dignissimos voluptatum eligendi laudantium, quisquam error. Ut facere error voluptates ratione animi velit consectetur provident magni, eius saepe ab fuga doloribus cupiditate sit, quisquam dignissimos harum, omnis nihil! Delectus eligendi similique reprehenderit soluta libero nostrum modi nihil omnis. Quam vero distinctio iste dicta? Eum aliquid ipsum vero repellendus ab nemo? Aliquam corporis, voluptatibus minima, ad praesentium, nostrum provident quam excepturi nulla hic quaerat. Ut quos aliquam consequatur obcaecati atque labore aut possimus ducimus! Nihil nemo assumenda in laboriosam veritatis, nesciunt ea ducimus voluptate quod quis culpa minus deserunt rem officiis consequuntur commodi vero, facere amet. Pariatur odio eveniet reiciendis aliquam consectetur unde deserunt iure nam optio facilis dolorem tenetur quaerat consequatur nihil possimus, temporibus recusandae, ut corrupti! Quas, magni culpa veniam recusandae a quo consequuntur accusantium deserunt dolore similique assumenda nesciunt autem, ut, quos at dolor? Exercitationem doloribus quas mollitia distinctio a neque vitae aliquid! Impedit numquam eius dolor soluta harum laboriosam consequuntur temporibus amet a voluptatum? Laboriosam voluptas ab, voluptates, corrupti molestias vitae quo, eius voluptate architecto voluptatibus velit aspernatur ut.',
                'isDone' => 0
            ],

        ];

        foreach ($listUser as $userListed) {
            $user = new User();
            $user->setEmail($userListed['email']);
            $user->setPassword($this->encoder->hashPassword($user, $userListed['password']));
            $user->setDisplayName($userListed['displayName']);
            $user->setRoles($userListed['role']);
            if ($userListed['displayName'] === 'Anonyme') {
                $user->setIsVerified(0);
            } else {
                $user->setIsVerified(1);
            }

            $manager->persist($user);
            $allUser[] = $user;
        }
        $manager->flush();

        foreach ($listUser as $key => $userListed) {
            foreach ($listTask as $keyTask => $taskListed) {
                $task = new Task();
                if ($key === 0) {
                    $task->setCreatedAt(new \DateTime());
                } else {
                    $task->setCreatedAt(new \DateTime('+' . mt_rand(5, 19) . 'days'));
                }

                $task->setTitle($taskListed['title']);
                $task->setContent($taskListed['content']);
                $task->setIsDone($taskListed['isDone']);
                $task->setUser($allUser[$key]);
                $manager->persist($task);
            }
        }
        $manager->flush();
    }
}
