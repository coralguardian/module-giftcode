<?php

namespace D4rk0snet\GiftCode\Entity;

use D4rk0snet\Adoption\Entity\AdoptionEntity;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @ORM\Table(name="adoption_gift_code")
 */
class GiftCodeEntity
{
    /**
     * @Id
     * @Column(type="uuid_binary_ordered_time", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\AdoptionEntity")
     */
    private AdoptionEntity $adoptionEntity;

    /**
     * @ORM\Column(type="string", unique=true)
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private string $giftCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $uniqueUsage;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $used;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $when;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\GiftAdoption", inversedBy="giftCodes")
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private GiftAdoption $giftAdoption;

    public function __construct(AdoptionEntity $adoptionEntity, string $giftCode, bool $uniqueUsage)
    {
        $this->adoptionEntity = $adoptionEntity;
        $this->giftCode = $giftCode;
        $this->uniqueUsage = $uniqueUsage;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getAdoptionEntity(): AdoptionEntity
    {
        return $this->adoptionEntity;
    }

    public function setAdoptionEntity(AdoptionEntity $adoptionEntity): GiftCodeEntity
    {
        $this->adoptionEntity = $adoptionEntity;
        return $this;
    }

    public function getGiftCode(): string
    {
        return $this->giftCode;
    }

    public function setGiftCode(string $giftCode): GiftCodeEntity
    {
        $this->giftCode = $giftCode;
        return $this;
    }

    public function isUniqueUsage(): bool
    {
        return $this->uniqueUsage;
    }

    public function setUniqueUsage(bool $uniqueUsage): GiftCodeEntity
    {
        $this->uniqueUsage = $uniqueUsage;
        return $this;
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): GiftCodeEntity
    {
        $this->used = $used;
        return $this;
    }

    public function getWhen(): ?DateTime
    {
        return $this->when;
    }

    public function setWhen(?DateTime $when): GiftCodeEntity
    {
        $this->when = $when;
        return $this;
    }

    public function getGiftAdoption(): GiftAdoption
    {
        return $this->giftAdoption;
    }

}