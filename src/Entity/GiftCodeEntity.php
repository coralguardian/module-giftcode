<?php

namespace D4rk0snet\GiftCode\Entity;

use D4rk0snet\Adoption\Entity\AdopteeEntity;
use D4rk0snet\Adoption\Entity\Friend;
use D4rk0snet\Adoption\Entity\GiftAdoption;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\D4rk0snet\GiftCode\Repository\GiftCodeRepository")
 * @ORM\Table(name="adoption_gift_code")
 */
class GiftCodeEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary_ordered_time", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", unique=true)
     * @ORM\JoinColumn(referencedColumnName="uuid")
     */
    private string $giftCode;

    /**
     * @ORM\Column(type="integer")
     */
    private int $productQuantity;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $used;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $usedOn;

    /**
     * @ORM\ManyToOne(targetEntity="\D4rk0snet\Adoption\Entity\GiftAdoption", inversedBy="giftCodes")
     * @ORM\JoinColumn(referencedColumnName="uuid", name="giftAdoption")
     */
    private GiftAdoption $giftAdoption;

    /**
     * @ORM\OneToOne(targetEntity="\D4rk0snet\Adoption\Entity\Friend", mappedBy="giftCode")
     */
    private ?Friend $friend = null;

    /**
     * @ORM\OneToMany(mappedBy="giftCode", targetEntity="\D4rk0snet\Adoption\Entity\AdopteeEntity")
     */
    private Collection $adoptees;

    public function __construct(
        string       $giftCode,
        GiftAdoption $giftAdoption,
        int $productQuantity
    )
    {
        $this->used = false;
        $this->usedOn = null;
        $this->giftCode = $giftCode;
        $this->setGiftAdoption($giftAdoption);
        $this->productQuantity = $productQuantity;
        $this->adoptees = new ArrayCollection();
    }

    public function getUuid()
    {
        return $this->uuid;
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

    /**
     * @return int
     */
    public function getProductQuantity(): int
    {
        return $this->productQuantity;
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

    public function getUsedOn(): ?DateTime
    {
        return $this->usedOn;
    }

    public function setUsedOn(?DateTime $usedOn): GiftCodeEntity
    {
        $this->usedOn = $usedOn;
        return $this;
    }

    public function getGiftAdoption(): GiftAdoption
    {
        return $this->giftAdoption;
    }

    public function setGiftAdoption(GiftAdoption $giftAdoption): GiftCodeEntity
    {
        $this->giftAdoption = $giftAdoption;
        $giftAdoption->addGiftCode($this);
        return $this;
    }

    /**
     * @return Friend|null
     */
    public function getFriend(): ?Friend
    {
        return $this->friend;
    }

    /**
     * @param Friend|null $friend
     * @return GiftCodeEntity
     */
    public function setFriend(?Friend $friend): GiftCodeEntity
    {
        $this->friend = $friend;
        return $this;
    }

    public function getAdoptees(): Collection
    {
        return $this->adoptees;
    }

    /**
     * @param ArrayCollection|Collection $adoptees
     * @return GiftCodeEntity
     */
    public function setAdoptees(ArrayCollection|Collection $adoptees): GiftCodeEntity
    {
        $this->adoptees = $adoptees;
        /** @var AdopteeEntity $adoptee */
        foreach ($adoptees as $adoptee) {
            $adoptee->setGiftCode($this);
        }
        return $this;
    }
}