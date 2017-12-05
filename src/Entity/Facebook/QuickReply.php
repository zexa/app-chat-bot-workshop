<?php

namespace Paysera\Workshop\ChatBot\Entity\Facebook;

class QuickReply
{
    const CONTENT_TYPE_TEXT = 'text';
    const CONTENT_TYPE_LOCATION = 'location';

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $payload;

    /**
     * @var string
     */
    private $imageUrl;

    public function __construct()
    {
        $this->contentType = self::CONTENT_TYPE_TEXT;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param null|string $payload
     *
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param null|string $imageUrl
     *
     * @return $this
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
}
