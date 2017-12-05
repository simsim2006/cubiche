<?php

/**
 * This file is part of the Cubiche/Serializer component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Tests\Units;

use Cubiche\Core\Serializer\Encoder\ArrayEncoder;
use Cubiche\Core\Serializer\Encoder\DateTimeEncoder;
use Cubiche\Core\Serializer\Encoder\MetadataObjectEncoder;
use Cubiche\Core\Serializer\Encoder\NativeEncoder;
use Cubiche\Core\Serializer\Encoder\ObjectEncoder;
use Cubiche\Core\Serializer\Encoder\ValueObjectEncoder;
use Cubiche\Core\Serializer\Serializer;
use Cubiche\Core\Serializer\Tests\Fixtures\Address;
use Cubiche\Core\Serializer\Tests\Fixtures\AddressId;
use Cubiche\Core\Serializer\Tests\Fixtures\City;
use Cubiche\Core\Serializer\Tests\Fixtures\CityId;
use Cubiche\Core\Serializer\Tests\Fixtures\Phonenumber;
use Cubiche\Core\Serializer\Tests\Fixtures\Role;
use Cubiche\Core\Serializer\Tests\Fixtures\User;
use Cubiche\Core\Serializer\Tests\Fixtures\UserId;
use Cubiche\Domain\Geolocation\Coordinate;

/**
 * Serializer class.
 *
 * Generated by TestGenerator on 2016-05-03 at 14:37:10.
 */
class SerializerTests extends ClassMetadataFactoryTests
{
    /**
     * @return Serializer
     */
    protected function createSerializer()
    {
        $metadataFactory = $this->createFactory();

        $encoders = array(
            new ValueObjectEncoder(),
            new DateTimeEncoder(),
            new MetadataObjectEncoder($metadataFactory),
            new ObjectEncoder(),
            new ArrayEncoder(),
            new NativeEncoder(),
        );

        return new Serializer($encoders);
    }

    /**
     * @return Serializer
     */
    protected function createSerializerWithCustomEncoders()
    {
        $metadataFactory = $this->createFactory();

        $encoders = array(
            new ValueObjectEncoder(),
            new DateTimeEncoder(),
            new MetadataObjectEncoder($metadataFactory),
            new ArrayEncoder(),
            new NativeEncoder(),
        );

        return new Serializer($encoders);
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        $user = new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100), $this->faker->email);

        $user->setFax(Phonenumber::fromNative($this->faker->phoneNumber));
        $user->setMainRole(Role::ROLE_ADMIN());

        $user->addPhonenumber(Phonenumber::fromNative($this->faker->phoneNumber));
        $user->addPhonenumber(Phonenumber::fromNative($this->faker->phoneNumber));

        $user->addRole(Role::ROLE_ANONYMOUS());
        $user->addRole(Role::ROLE_USER());

        $user->setLanguageLevel('Spanish', 5);
        $user->setLanguageLevel('English', 4);
        $user->setLanguageLevel('Catalan', 3);

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Home',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        $user->addAddress(
            new Address(
                AddressId::next(),
                'Work',
                $this->faker->streetName,
                $this->faker->postcode,
                $this->faker->city,
                Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
            )
        );

        return $user;
    }

    /**
     * Test serialize/deserialize object.
     */
    public function testSerialize()
    {
        $this
            ->given($serializer = $this->createSerializer())
            ->and($user = $this->createUser())
            ->and($user1 = $this->createUser())
            ->then()
                ->variable($data = $serializer->serialize($user))
                    ->isNotNull()
                ->boolean($user->equals($serializer->deserialize($data, User::class)))
                    ->isTrue()
        ;

        $this
            ->given($serializer = $this->createSerializerWithCustomEncoders())
            ->and(
                $city = new City(
                    CityId::next(),
                    $this->faker->city,
                    Coordinate::fromLatLng($this->faker->latitude, $this->faker->longitude)
                )
            )
            ->then()
                ->boolean($serializer->supports('Foo'))
                    ->isFalse()
                ->exception(function () use ($serializer, $city) {
                    $serializer->serialize($city);
                })->isInstanceOf(\RuntimeException::class)
                ->then()
                ->exception(function () use ($serializer, $city) {
                    $serializer->deserialize(array(), City::class);
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
