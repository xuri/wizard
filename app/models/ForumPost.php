<?php

/**
 * Forum Posts Model
 */

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class ForumPost extends BaseModel
{
    /**
     * Soft delete
     * @var boolean
     */
    use SoftDeletingTrait;

    protected $softDelete = ['deleted_at'];
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'forum_posts';

}