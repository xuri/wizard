<?php

/**
 * NotificationsContent Model
 */

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class NotificationsContent extends BaseModel
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
    protected $table = 'notifications_content';

}