<?php
namespace Valyria\Events;

use Valyria\Events\Event as Events;
use Illuminate\Queue\SerializesModels;
use Valyria\Models\Comment as CommentModel;

class Comment extends Events
{
    use SerializesModels;

    /**
     * Entity relationship
     *
     * @var null|object
     */
    protected $entity = null;

    /**
     * Create a new event instance
     *
     * @param CommentModel $entity
     */
    public function __construct(CommentModel $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the reference of the entity
     *
     * @return null|object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}