<?php

/**
 * Forum Reply Model
 */

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class ForumReply extends BaseModel
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
    protected $table = 'forum_reply';

}