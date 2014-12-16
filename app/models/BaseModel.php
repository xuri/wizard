<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @uses        Laravel The PHP frameworks for web artisans http://laravel.com
 * @author      Ri Xu http://xuri.me
 * @copyright   Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link        http://www.jinglingkj.com
 * @since       25th Nov, 2014
 * @license     Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version     0.1
 */

class BaseModel extends Eloquent
{
    /**
     * Access control: Friendly created_at
     * @return string
     */
    public function getFriendlyCreatedAtAttribute()
    {
        return friendly_date($this->created_at);
    }

    /**
     * Access control: Friendly updated_at
     * @return string
     */
    public function getFriendlyUpdatedAtAttribute()
    {
        return friendly_date($this->updated_at);
    }

    /**
     * Access control: Friendly deleted_at
     * @return string
     */
    public function getFriendlyDeletedAtAttribute()
    {
        return friendly_date($this->deleted_at);
    }

}