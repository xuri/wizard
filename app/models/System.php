<?php

/**
 * System List
 */

use Illuminate\Database\Eloquent\SoftDeletingTrait;


class System extends BaseModel
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
    protected $table = 'system';

}